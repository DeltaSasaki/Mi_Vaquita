<div align="center">
  <h1>🥩 Mi Vaquita | E-Commerce de Carnicería</h1>
  <p>
    <strong>Sistema integral de ventas online con gestión de inventario, pedidos geolocalizados y panel administrativo.</strong>
  </p>

  <p>
    <a href="#-características">Características</a> •
    <a href="#-tecnologías">Tecnologías</a> •
    <a href="#-capturas">Capturas</a> •
    <a href="#-instalación">Instalación</a> •
    <a href="#-contacto">Contacto</a>
  </p>

  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap" />
  <img src="https://img.shields.io/badge/Leaflet_Maps-199900?style=for-the-badge&logo=leaflet&logoColor=white" alt="Leaflet" />
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
</div>

<br />

## 📖 Sobre el Proyecto

**Mi Vaquita** es una solución Full-Stack desarrollada para modernizar el proceso de venta de una carnicería. El sistema resuelve la necesidad de gestionar pedidos online con métodos de pago locales (Pago Móvil, Zelle, Efectivo) y logística de entrega precisa mediante mapas interactivos.

El proyecto se divide en dos áreas principales: una **Tienda Pública** (Front-End) optimizada para la conversión y un **Panel Administrativo** (Back-End) para la gestión total del negocio.

---

## 🚀 Características Principales

### 🛒 Experiencia de Compra (Cliente)
* **Catálogo Dinámico:** Filtrado por categorías, búsqueda en tiempo real y visualización de stock disponible.
* **Carrito Inteligente:**
    * Actualización de cantidades y precios sin recargar la página (AJAX/Fetch).
    * Validación de stock en tiempo real.
* **Checkout & Geolocalización:**
    * Integración con **Leaflet.js** para seleccionar la ubicación exacta de entrega en el mapa (Drag & Drop).
    * Opción de elegir entre *Delivery* o *Retiro en Tienda*.
* **Métodos de Pago Adaptados:** Soporte lógico para referenciar pagos vía Zelle, Pago Móvil (Venezuela) o Efectivo.
* **Gestión de Usuario:** Registro, recuperación de contraseña, historial de pedidos y estados de compra.

### 🛠 Panel Administrativo (Dueño)
* **Dashboard de Métricas:** Visualización rápida de ganancias, pedidos pendientes, alertas de stock bajo (< 10kg) y nuevos usuarios.
* **Gestión de Pedidos (Workflow):**
    * Visualización de detalles de compra.
    * **Mapa Administrativo:** Ver la ubicación exacta del cliente en un mapa interactivo.
    * Cambio de estados: *Pendiente → Completado → Cancelado*.
* **Inventario Completo:** CRUD de productos, imágenes y categorías.
* **Marketing:** Sistema de **Cupones de Descuento** (Porcentaje o Monto Fijo) con restricciones por fecha o producto.
* **Control de Horarios:** Configuración de apertura/cierre de la tienda con validación automática en el checkout.
* **Roles y Permisos:** Sistema de roles (Admin/Cliente) para proteger rutas críticas.

---

## 💻 Tecnologías Utilizadas

Este proyecto fue construido siguiendo el patrón **MVC** y las mejores prácticas de desarrollo web.

| Área | Tecnología | Detalles |
| :--- | :--- | :--- |
| **Backend** | **Laravel 10/11** | Framework PHP, Eloquent ORM, Autenticación, Middleware. |
| **Frontend** | **Blade & Bootstrap 5** | Diseño responsivo (Mobile-First), componentes UI. |
| **Scripting** | **JavaScript (Vanilla)** | Lógica del carrito, Fetch API, manipulación del DOM. |
| **Mapas** | **Leaflet.js + OSM** | OpenStreetMap para geolocalización sin costo de API. |
| **Base de Datos** | **MySQL** | Relaciones, transacciones ACID para pedidos y stock. |

---

## 📸 Capturas de Pantalla

> *Nota: Las imágenes a continuación muestran el flujo real del sistema.*

### 📱 Vista Móvil & Mapa
<div align="center">
  <img src="https://via.placeholder.com/300x600?text=Vista+Movil+Catalogo" alt="Mobile View" width="30%" />
  <img src="https://via.placeholder.com/300x600?text=Seleccion+Mapa" alt="Map View" width="30%" />
  <img src="https://via.placeholder.com/300x600?text=Carrito+Compras" alt="Cart View" width="30%" />
</div>

### 🖥️ Panel Administrativo
<div align="center">
  <img src="https://via.placeholder.com/800x400?text=Dashboard+Admin" alt="Admin Dashboard" width="100%" />
  <br/><br/>
  <img src="https://via.placeholder.com/800x400?text=Detalle+Pedido+Admin" alt="Order Detail" width="100%" />
</div>

---

## ⚙️ Instalación y Despliegue Local

Sigue estos pasos para ejecutar el proyecto en tu entorno local:

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/DeltaSasaki/Mi_Vaquita.git](https://github.com/DeltaSasaki/Mi_Vaquita.git)
    cd Mi_Vaquita
    ```

2.  **Instalar dependencias de PHP y Node:**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Configurar el entorno:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Asegúrate de configurar tus credenciales de base de datos en el archivo `.env`.*

4.  **Migrar la base de datos:**
    ```bash
    php artisan migrate --seed
    ```
    *(El seeder creará un usuario administrador por defecto).*

5.  **Crear el enlace simbólico para imágenes:**
    ```bash
    php artisan storage:link
    ```

6.  **Ejecutar el servidor:**
    ```bash
    php artisan serve
    ```

¡Visita `http://127.0.0.1:8000` y listo!

---

## 👨‍💻 Autor

**Lisandro Corro**
* **Rol:** Desarrollador Full-Stack
* **Especialidad:** PHP, Laravel, HTML5, CSS3, JS.
* **Portafolio:** [github.com/DeltaSasaki](https://github.com/DeltaSasaki)

---
<div align="center">
  Desarrollado con ❤️ para impulsar el comercio local.
</div>
