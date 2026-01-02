<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta | Mi Vaquita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root { --primary-red: #8B0000; }
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
            max-width: 500px; /* Un poco más ancho para que quepan bien los datos */
            position: relative;
            z-index: 2;
        }
        .login-title { font-family: 'Oswald', sans-serif; color: var(--primary-red); text-transform: uppercase; font-weight: 700; text-align: center; margin-bottom: 20px; }
        .form-control:focus { border-color: var(--primary-red); box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.25); }
        .btn-login { background-color: var(--primary-red); color: white; width: 100%; font-weight: bold; padding: 12px; text-transform: uppercase; transition: 0.3s; }
        .btn-login:hover { background-color: #600000; color: white; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #666; }
    </style>
</head>
<body>

    <div class="overlay"></div>

    <div class="login-card">
        <h2 class="login-title">Únete a Mi Vaquita</h2>
        <p class="text-center text-muted mb-4">Regístrate para realizar tus pedidos</p>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nombre Completo</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="name" placeholder="Juan Pérez" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="ejemplo@correo.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Cédula</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                        <input type="text" class="form-control" name="cedula" placeholder="V-12345678" value="{{ old('cedula') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Teléfono</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                        <input type="text" class="form-control" name="phone" placeholder="0412-..." value="{{ old('phone') }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Min 8 caracteres" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Confirmar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Repetir" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-login mt-2">Crear Cuenta</button>
        </form>

        <div class="text-center mt-3">
            <small>¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">Inicia Sesión aquí</a></small>
        </div>
        
        <a href="{{ route('home') }}" class="back-link"><i class="fas fa-arrow-left me-1"></i> Volver a la tienda</a>
    </div>

</body>
</html>