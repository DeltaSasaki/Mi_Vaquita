<?php

use Illuminate\Support\Facades\Route;

// --- Controladores Públicos y Generales ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;

// --- Controladores de Cliente y Procesos ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;

// --- Controladores de Administración ---
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\AdminCouponController;

// --- Middleware ---
use App\Http\Middleware\CheckStoreHours;

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS (TIENDA)
|--------------------------------------------------------------------------
| Acceso libre para cualquier visitante (Home, Catálogo, Buscador, etc.)
*/

// Página de Inicio
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catálogo General (ESTA ES LA QUE TE FALTABA PARA EL ERROR)
Route::get('/catalogo', [ProductController::class, 'index'])->name('catalogo');

// Detalle de Producto Individual
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('products.show');

// Detalle de Categoría
Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Buscador
Route::get('/buscar', [SearchController::class, 'index'])->name('search');


/*
|--------------------------------------------------------------------------
| 2. CARRITO DE COMPRAS Y CUPONES
|--------------------------------------------------------------------------
| Gestión del carrito (Agregar, quitar, actualizar, cupones)
*/

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/add-to-cart/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('update-cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

// Gestión de Cupones
Route::post('/coupon', [CartController::class, 'applyCoupon'])->name('coupon.apply');
Route::delete('/coupon', [CartController::class, 'removeCoupon'])->name('coupon.remove');


/*
|--------------------------------------------------------------------------
| 3. RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
| Login, Registro, Logout y Recuperación de Contraseña
*/

// Iniciar Sesión
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registro de Usuario
Route::get('/registro', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');

// Recuperar Contraseña
Route::get('/olvide-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/olvide-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');


/*
|--------------------------------------------------------------------------
| 4. ZONA CLIENTE (PROTEGIDAS)
|--------------------------------------------------------------------------
| Solo accesibles para usuarios logueados (Perfil, Pedidos, Checkout)
*/

Route::middleware('auth')->group(function () {
    
    // --- Perfil de Usuario ---
    Route::get('/perfil', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/perfil/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // --- Historial de Pedidos ---
    Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/mis-pedidos/{id}', [OrderController::class, 'show'])->name('orders.show');

    // --- Proceso de Checkout (Pago) ---
    // Vista del formulario de checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    
    // Procesar la compra (Validamos que la tienda esté abierta con Middleware)
    Route::post('/checkout', [OrderController::class, 'store'])
        ->middleware(CheckStoreHours::class)
        ->name('orders.store');      
        
    // Pantalla de éxito tras la compra
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success'); 
});


/*
|--------------------------------------------------------------------------
| 5. PANEL DE ADMINISTRACIÓN
|--------------------------------------------------------------------------
| Rutas protegidas para Administradores (middleware 'admin')
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // Dashboard Principal
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    // --- Gestión de Productos ---
    Route::resource('productos', AdminProductController::class)->names('admin.products');

    // --- Gestión de Categorías ---
    Route::resource('categorias', AdminCategoryController::class)
        ->except(['show']) // No necesitamos vista individual en admin
        ->names('admin.categories');

    // --- Gestión de Pedidos ---
    Route::get('/pedidos', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/pedidos/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/pedidos/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update');

    // --- Gestión de Usuarios ---
    Route::get('/usuarios', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::put('/usuarios/{id}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('admin.users.toggle');
    Route::delete('/usuarios/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // --- Gestión de Horarios ---
    Route::get('/horarios', [AdminScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::put('/horarios', [AdminScheduleController::class, 'update'])->name('admin.schedules.update');

    // --- Gestión de Cupones ---
    Route::resource('cupones', AdminCouponController::class)->names('admin.coupons');

});