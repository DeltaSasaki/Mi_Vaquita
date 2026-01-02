<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Mi Vaquita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root { --primary-red: #8B0000; --dark-bg: #1a1a1a; }
        body { 
            font-family: 'Roboto', sans-serif; 
            background: url('https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Capa oscura para que se lea el texto */
        .overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); z-index: 1;
        }
        
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }
        
        .login-title { font-family: 'Oswald', sans-serif; color: var(--primary-red); text-transform: uppercase; font-weight: 700; text-align: center; margin-bottom: 30px; }
        .form-control:focus { border-color: var(--primary-red); box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.25); }
        .btn-login { background-color: var(--primary-red); color: white; width: 100%; font-weight: bold; padding: 12px; text-transform: uppercase; transition: 0.3s; }
        .btn-login:hover { background-color: #600000; color: white; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #666; }
        .back-link:hover { color: var(--primary-red); }
    </style>
</head>
<body>

    <div class="overlay"></div>

    <div class="login-card">
        <h2 class="login-title"><i class="fas fa-drumstick-bite me-2"></i>Mi Vaquita</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
            </div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label small text-muted" for="remember">Recordarme</label>
    </div>
    <a href="{{ route('password.request') }}" class="small text-danger text-decoration-none fw-bold">
        ¿Olvidaste tu contraseña?
    </a>
</div>

            <button type="submit" class="btn btn-login">Ingresar</button>
            <div class="text-center mb-3">
    <small>¿Eres nuevo? <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none">Regístrate aquí</a></small>
</div>
        </form>

        <a href="{{ route('home') }}" class="back-link"><i class="fas fa-arrow-left me-1"></i> Volver a la tienda</a>
    </div>

</body>
</html>