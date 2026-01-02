<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Carnicería Mi Vaquita')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary-red: #8B0000; --dark-bg: #1a1a1a; --dark-lighter: #2c2c2c; }
        
        body { 
            font-family: 'Roboto', sans-serif; 
            background-color: #f8f9fa; 
            display: flex; flex-direction: column; min-height: 100vh; 
        }
        
        main { flex: 1; display: flex; flex-direction: column; }
        h1, h2, h3, h4, h5, .navbar-brand, .offcanvas-title { font-family: 'Oswald', sans-serif; text-transform: uppercase; }

        /* === ESTILOS PC (Intactos) === */
        .navbar { background-color: var(--dark-bg); box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        .navbar-brand { color: var(--primary-red) !important; font-weight: 700; font-size: 1.5rem; }
        .nav-link { color: white !important; transition: 0.3s; }
        .nav-link:hover { color: var(--primary-red) !important; }
        
        /* Buscador PC */
        .search-form-desktop input { border-radius: 20px 0 0 20px; border: none; padding-left: 15px; }
        .search-form-desktop button { border-radius: 0 20px 20px 0; background-color: var(--primary-red); color: white; border: none; padding: 0 15px; }
        
        /* === ESTILOS MÓVIL (Offcanvas / Lateral) === */
        .offcanvas { background-color: var(--dark-bg); color: white; }
        .offcanvas-header { border-bottom: 1px solid #333; }
        .btn-close-white { filter: invert(1); } /* X blanca */

        /* Perfil en Móvil */
        .mobile-user-profile {
            background-color: var(--dark-lighter);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #444;
        }
        .mobile-user-avatar {
            width: 50px; height: 50px;
            background-color: var(--primary-red);
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        /* Enlaces Móvil */
        .mobile-nav-link {
            display: block;
            padding: 12px 15px;
            color: #ccc;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
            font-size: 1.1rem;
        }
        .mobile-nav-link:hover, .mobile-nav-link.active {
            background-color: var(--dark-lighter);
            color: var(--primary-red);
            padding-left: 20px; /* Efecto de movimiento */
        }
        .mobile-nav-link i { width: 25px; text-align: center; margin-right: 10px; }

        /* Buscador Móvil */
        .mobile-search input { background: #333; border: none; color: white; padding: 12px; border-radius: 8px; }
        .mobile-search input::placeholder { color: #888; }
        .mobile-search button { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #888; }

        /* Footer */
        footer { background-color: var(--dark-bg); color: #ccc; padding: 40px 0; margin-top: auto; }

        /* Animación Carrito */
        @keyframes bounce { 0% { transform: scale(1); } 50% { transform: scale(1.5); color: #ffeb3b; } 100% { transform: scale(1); } }
        .cart-anim { animation: bounce 0.4s ease; }

    </style>
    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top navbar-dark">
        <div class="container">
            
            <a class="navbar-brand" href="{{ route('home') }}"><i class="fas fa-drumstick-bite me-2"></i>MI VAQUITA</a>
            
            <div class="d-flex align-items-center d-lg-none">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm position-relative me-3 border-0">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <span id="cart-count-mobile" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ array_sum(array_column(session('cart', []), 'quantity')) }}
                    </span>
                </a>
                
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse d-none d-lg-block" id="desktopMenu">
                <form action="{{ route('search') }}" method="GET" class="d-flex mx-auto search-form-desktop">
                    <input class="form-control" type="search" name="q" placeholder="Buscar cortes..." value="{{ request('q') }}">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#productos">Productos</a></li>
                    
                    <li class="nav-item ms-3">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm position-relative" id="cartButton">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ array_sum(array_column(session('cart', []), 'quantity')) }}
                            </span>
                        </a>
                    </li>

                    @auth
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-check me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                @if(Auth::user()->is_admin)
                                    <li><a class="dropdown-item fw-bold text-danger" href="{{ route('admin.dashboard') }}">Panel Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">Mis Pedidos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-3"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                        <li class="nav-item ms-2"><a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">Registro</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-danger"><i class="fas fa-bars me-2"></i>MENÚ</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body">
            
            @auth
                <div class="mobile-user-profile">
                    <div class="mobile-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h5 class="mb-0">{{ Auth::user()->name }}</h5>
<small class="text-white-50">{{ Auth::user()->email }}</small>
                    @if(Auth::user()->is_admin)
                        <div class="mt-2">
                            <span class="badge bg-danger">ADMINISTRADOR</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="mobile-user-profile">
                    <i class="fas fa-user-circle fa-3x mb-2 text-secondary"></i>
                    <p class="mb-2">¡Bienvenido!</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-danger btn-sm">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">Registrarse</a>
                    </div>
                </div>
            @endauth

            <form action="{{ route('search') }}" method="GET" class="position-relative mb-4 mobile-search">
                <input class="form-control" type="search" name="q" placeholder="¿Qué se te antoja hoy?" value="{{ request('q') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>

            <nav class="nav flex-column">
                <a class="mobile-nav-link" href="{{ route('home') }}"><i class="fas fa-home"></i> Inicio</a>
                <a class="mobile-nav-link" href="{{ route('home') }}#productos"><i class="fas fa-drumstick-bite"></i> Productos</a>
                
                @auth
                    @if(Auth::user()->is_admin)
                        <a class="mobile-nav-link text-danger fw-bold" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Panel Admin</a>
                    @endif
                    <a class="mobile-nav-link" href="{{ route('profile.index') }}"><i class="fas fa-user-cog"></i> Mi Perfil</a>
                    <a class="mobile-nav-link" href="{{ route('orders.index') }}"><i class="fas fa-receipt"></i> Mis Pedidos</a>
                    
                    <div class="mt-4 pt-4 border-top border-secondary">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="mobile-nav-link w-100 text-start text-danger bg-transparent border-0">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                @endauth
            </nav>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="text-center">
        <div class="container">
            <h4 class="mb-3">CARNICERÍA MI VAQUITA</h4>
            <div class="my-3">
                <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-white"><i class="fab fa-whatsapp fa-lg"></i></a>
            </div>
            <small>&copy; 2024 Mi Vaquita.</small>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function addToCart(productId) {
            fetch('/add-to-cart/' + productId, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar contador PC
                    let cartBadge = document.getElementById('cart-count');
                    if(cartBadge) {
                        cartBadge.innerText = data.cartCount;
                        cartBadge.classList.add('cart-anim');
                        setTimeout(() => { cartBadge.classList.remove('cart-anim'); }, 400);
                    }
                    
                    // Actualizar contador Móvil
                    let cartBadgeMobile = document.getElementById('cart-count-mobile');
                    if(cartBadgeMobile) {
                        cartBadgeMobile.innerText = data.cartCount;
                        cartBadgeMobile.classList.add('cart-anim');
                        setTimeout(() => { cartBadgeMobile.classList.remove('cart-anim'); }, 400);
                    }

                    // Toast (se mantiene igual, no incluyo el código html aquí por espacio)
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    @stack('scripts')
</body>
</html>