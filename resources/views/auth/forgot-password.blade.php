<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Mi Vaquita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root { --primary-red: #8B0000; }
        body { 
            font-family: 'Roboto', sans-serif; 
            background: url('https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?q=80&w=1470&auto=format&fit=crop');
            background-size: cover; background-position: center;
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 1; }
        .login-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); width: 100%; max-width: 400px; position: relative; z-index: 2; }
        .login-title { font-family: 'Oswald', sans-serif; color: var(--primary-red); text-transform: uppercase; font-weight: 700; text-align: center; margin-bottom: 20px; }
        .btn-login { background-color: var(--primary-red); color: white; width: 100%; font-weight: bold; padding: 12px; text-transform: uppercase; transition: 0.3s; }
        .btn-login:hover { background-color: #600000; color: white; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #666; }
        .back-link:hover { color: var(--primary-red); }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="login-card">
        <h3 class="login-title"><i class="fas fa-lock me-2"></i>Recuperar Acceso</h3>
        <p class="text-muted text-center small mb-4">Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.</p>
        
        @if (session('status'))
            <div class="alert alert-success small" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus placeholder="ejemplo@correo.com">
            </div>

            <button type="submit" class="btn btn-login">Enviar Enlace</button>
        </form>

        <a href="{{ route('login') }}" class="back-link"><i class="fas fa-arrow-left me-1"></i> Volver al Login</a>
    </div>
</body>
</html>