<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Mi Vaquita</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        :root { --admin-dark: #1a1a1a; --admin-red: #8B0000; --admin-bg: #f4f6f9; }
        
        body { font-family: 'Roboto', sans-serif; background-color: var(--admin-bg); overflow-x: hidden; }
        h1, h2, h3, h4, h5 { font-family: 'Oswald', sans-serif; text-transform: uppercase; }
        
        /* === SIDEBAR === */
        .sidebar {
            height: 100vh;
            background-color: var(--admin-dark);
            color: white;
            position: fixed;
            top: 0; left: 0;
            width: 250px;
            padding-top: 20px;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 10px rgba(0,0,0,0.3);
        }

        .sidebar-brand {
            text-align: center; font-size: 1.5rem; color: var(--admin-red);
            font-weight: bold; margin-bottom: 30px; letter-spacing: 1px;
        }

        .sidebar a {
            padding: 15px 25px; text-decoration: none; font-size: 1rem;
            color: #ccc; display: block; transition: 0.2s; border-left: 4px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #2c2c2c; color: white; border-left-color: var(--admin-red);
        }
        .sidebar i { width: 30px; text-align: center; margin-right: 10px; }

        .btn-logout { width: 100%; text-align: left; padding: 15px 25px; color: #bbb; }
        .btn-logout:hover { color: var(--admin-red); background: #2c2c2c; }

        /* === MAIN CONTENT === */
        .main-content {
            margin-left: 250px; 
            padding: 20px; /* Padding normal en PC */
            transition: all 0.3s ease;
        }

        /* === NAVBAR (Estilo PC) === */
        .admin-navbar {
            background-color: var(--admin-dark);
            color: white;
            border-radius: 10px; 
            padding: 15px 20px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); 
            border-bottom: 4px solid var(--admin-red);
            margin-bottom: 20px;
        }

        .admin-navbar .small-text { color: #aaa; } 

        .btn-toggle-menu {
            color: white; border: 1px solid #444; background: #333;
            border-radius: 5px; padding: 5px 10px; transition: 0.2s;
        }
        .btn-toggle-menu:hover { background: var(--admin-red); border-color: var(--admin-red); }

        /* === CORRECCIONES PARA MÓVIL (Full Width) === */
        @media (max-width: 768px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            
            /* Quitamos el margen izquierdo del contenido */
            .main-content { 
                margin-left: 0; 
                padding: 0; /* <--- TRUCO: Quitamos padding general para pegar todo al borde */
            } 

            /* Ajustamos el Navbar para que toque los bordes */
            .admin-navbar {
                border-radius: 0; /* Esquinas cuadradas */
                margin-bottom: 20px;
                width: 100%;
                position: sticky; top: 0; z-index: 900; /* Fijamos el navbar arriba */
            }

            /* Creamos un contenedor interno para dar aire al contenido (tablas, cards) */
            .content-wrapper {
                padding: 0 15px; /* Aire solo a los lados del contenido */
            }
            
            .overlay {
                display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
                background: rgba(0,0,0,0.6); z-index: 999; backdrop-filter: blur(2px);
            }
            .overlay.active { display: block; }
        }

        /* Tarjetas */
        .stat-card { 
            border: none; border-radius: 12px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
            background: white; transition: 0.3s; 
        }
    </style>
</head>
<body>

    <div class="overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-drumstick-bite me-2"></i>ADMIN
        </div>
        
        <nav class="mt-2">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Productos
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Categorías
            </a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Pedidos
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Usuarios
            </a>
            <a href="{{ route('admin.schedules.index') }}" class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Horarios
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Cupones
            </a>
        </nav>
        
        <div class="mt-auto pt-5 pb-3 border-top border-secondary">
            <a href="{{ route('home') }}"><i class="fas fa-store"></i> Ver Tienda</a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Salir
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        
        <nav class="admin-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-toggle-menu d-md-none me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="h5 mb-0 fw-bold font-monospace text-uppercase">Panel de Control</span>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="text-end me-3 d-none d-sm-block">
                    <div class="fw-bold small">{{ Auth::user()->name }}</div>
                    <div class="small-text" style="font-size: 0.75rem;">Administrador</div>
                </div>
                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </nav>

        <div class="content-wrapper px-md-0 px-2">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
    </script>
</body>
</html>