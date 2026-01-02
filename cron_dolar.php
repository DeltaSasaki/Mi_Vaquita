<?php

/**
 * Script de Actualización Automática de Tasa (DolarVzla API)
 * Ejecutar preferiblemente vía CLI o Cron Job
 */

use App\Modelos\Divisafactor;
use Core\Database;

// 1. CARGA DE DEPENDENCIAS Y CONFIGURACIÓN
// =======================================================

// Aseguramos que cargue el autoload desde el directorio actual
require __DIR__ . '/vendor/autoload.php';

// Si tienes funciones globales en helpers, las cargamos
if (file_exists(__DIR__ . '/core/Helpers/funciones.php')) {
    require __DIR__ . '/core/Helpers/funciones.php';
}

// Cargar configuración
if (!file_exists(__DIR__ . '/config.ini')) {
    die("Error: No se encuentra el archivo config.ini");
}

$options = parse_ini_file(__DIR__ . '/config.ini', false);
foreach ($options as $constant => $value) {
    if (!defined($constant)) {
        define($constant, $value);
    }
}

// Configurar include path si es necesario según tu framework
if (defined('APP_DIR')) {
    ini_set('include_path', constant('APP_DIR'));
}

// 2. INICIALIZACIÓN
// =======================================================
try {
    $newDBIni = new Database();
    $newDivisaFactor = new Divisafactor();
    
    echo "[" . date('Y-m-d H:i:s') . "] Iniciando sincronización...\n";

} catch (Exception $e) {
    die("Error de conexión BD: " . $e->getMessage());
}

// Configuración de IDs (Ajustar si tu ID de Dólar cambia)
$ID_DOLAR_MASTER = 2; 
$ID_LOCAL = 1; // Bolívares

// Variables auxiliares
$divisas = $newDivisaFactor->consultar("SELECT * FROM app_divisas")->all();
$unico = uniqid();
$fecha = date('Y-m-d H:i:s');

// Verificar si ya existe relación USD -> VES
$id_encontrado = $newDivisaFactor->divisaExiste($ID_DOLAR_MASTER, $ID_LOCAL);


// 3. CONSULTA A LA API
// =======================================================
$url = "https://api.dolarvzla.com/public/exchange-rate";
$curl = curl_init($url);

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// OJO: En producción idealmente deberías tener certificados SSL válidos, 
// pero mantenemos false para evitar errores en servidores locales/antiguos.
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);

if (curl_error($curl)) {
    echo "Error crítico cURL: " . curl_error($curl) . "\n";
    curl_close($curl);
    exit;
}

curl_close($curl);
$json = json_decode($resp, true);


// 4. PROCESAMIENTO Y GUARDADO
// =======================================================

// Validamos que la respuesta tenga el dato que necesitamos
if (isset($json['current']['usd']) && is_numeric($json['current']['usd'])) {

    $factor = $json['current']['usd'];
    echo " > Tasa obtenida de API: " . $factor . "\n";

    // --- A. Actualizar USD -> VES (Dólar a Bolívares) ---
    if ($id_encontrado) {
        $newDivisaFactor->editar($id_encontrado['df_id'], [
            'df_factor' => $factor
        ], 'app_divisas_factor', 'df_id');
        echo " > Actualizado USD -> VES.\n";
    } else {
        $newDivisaFactor->guardar([
            'df_divid_master' => $ID_DOLAR_MASTER,
            'df_divid' => $ID_LOCAL,
            'df_factor' => $factor
        ], true, 'app_divisas_factor');
        echo " > Creado USD -> VES.\n";
    }

    // --- B. Actualizar VES -> USD (Bolívares a Dólar - Inversa) ---
    $id_encontrado_dos = $newDivisaFactor->divisaExiste($ID_LOCAL, $ID_DOLAR_MASTER);
    $factor_inverso = (1 / $factor);

    if ($id_encontrado_dos) {
        $newDivisaFactor->editar($id_encontrado_dos['df_id'], [
            'df_factor' => $factor_inverso
        ], 'app_divisas_factor', 'df_id');
        echo " > Actualizado VES -> USD (Inversa).\n";
    } else {
        $newDivisaFactor->guardar([
            'df_divid_master' => $ID_LOCAL,
            'df_divid' => $ID_DOLAR_MASTER,
            'df_factor' => $factor_inverso
        ], true, 'app_divisas_factor');
        echo " > Creado VES -> USD (Inversa).\n";
    }

    // --- C. Generar Bitácora de Factores Cruzados ---
    // Esto calcula la relación de todas las monedas contra todas basándose en el pivote
    echo " > Generando matriz de conversión (Bitácora)...\n";
    
    $bitacora_factor = $newDivisaFactor->todos();

    foreach ($divisas as $div) {
        $comparado = []; // Resetear array de comparación por cada divisa
        
        foreach ($bitacora_factor as $row) {
            // Evitar comparar la moneda consigo misma
            if ($div['div_id'] != $row['df_divid']) {
                
                // Evitar duplicados en esta iteración
                if (!in_array($row['df_divid'], $comparado)) {
                    
                    // Buscamos si existe factor directo
                    $consulta = "SELECT df_factor FROM app_divisas_factor WHERE df_divid_master = {$div['div_id']} AND df_divid = {$row['df_divid']}";
                    $div_encontro = $newDivisaFactor->consultar($consulta)->row();

                    if ($div_encontro) {
                        // Si existe relación directa, guardamos ese factor
                        $newDivisaFactor->guardar([
                            'bifac_divid_uno' => $div['div_id'],
                            'bifac_divid_dos' => $row['df_divid'],
                            'bifac_factor'    => $div_encontro['df_factor'],
                            'bifac_fecha'     => $fecha,
                            'bitfac_relacion' => $unico
                        ], true, 'app_bitacora_factor');
                    } else {
                        // Si NO existe relación directa, calculamos la tasa cruzada usando el Dólar como pivote
                        $q1 = "SELECT df_factor FROM app_divisas_factor WHERE df_divid_master = $ID_DOLAR_MASTER AND df_divid = {$div['div_id']}";
                        $q2 = "SELECT df_factor FROM app_divisas_factor WHERE df_divid_master = $ID_DOLAR_MASTER AND df_divid = {$row['df_divid']}";
                        
                        $div_factor_uno = $newDivisaFactor->consultar($q1)->row();
                        $div_factor_dos = $newDivisaFactor->consultar($q2)->row();

                        // Solo procedemos si tenemos ambos factores para calcular
                        if ($div_factor_uno && $div_factor_dos && $div_factor_dos['df_factor'] != 0) {
                            $calculo_cruzado = ($div_factor_uno['df_factor'] / $div_factor_dos['df_factor']);
                            
                            $newDivisaFactor->guardar([
                                'bifac_divid_uno' => $div['div_id'],
                                'bifac_divid_dos' => $row['df_divid'],
                                'bifac_factor'    => $calculo_cruzado,
                                'bifac_fecha'     => $fecha,
                                'bitfac_relacion' => $unico
                            ], true, 'app_bitacora_factor');
                        }
                    }
                }
                $comparado[] = $row['df_divid'];
            }
        }
    }
    
    echo " > ¡Proceso finalizado con éxito!\n";

} else {
    echo "Error: La API no devolvió un valor numérico válido en ['current']['usd'].\n";
    // Opcional: Loguear respuesta para depuración
    // file_put_contents('error_log_api.txt', $resp);
}
?>