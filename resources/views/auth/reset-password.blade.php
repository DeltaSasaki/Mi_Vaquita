<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña | Mi Vaquita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-red: #8B0000; }
        body { font-family: 'Roboto', sans-serif; background: url('https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?q=80&w=1470&auto=format&fit=crop'); background-size: cover; background-position: center; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 1; }
        .login-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); width: 100%; max-width: 400px; position: relative; z-index: 2; }
        .login-title { font-family: 'Oswald', sans-serif; color: var(--primary-red); text-transform: uppercase; font-weight: 700; text-align: center; margin-bottom: 20px; }
        .btn-login { background-color: var(--primary-red); color: white; width: 100%; font-weight: bold; padding: 12px; text-transform: uppercase; transition: 0.3s; }
        .btn-login:hover { background-color: #600000; color: white; }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="login-card">
        <h3 class="login-title"><i class="fas fa-key me-2"></i>Nueva Contraseña</h3>
        
        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required readonly>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Nueva Contraseña</label>
                <input type="password" class="form-control" name="password" required autofocus placeholder="Mínimo 8 caracteres">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-bold">Confirmar Contraseña</label>
                <input type="password" class="form-control" name="password_confirmation" required placeholder="Repite la contraseña">
            </div>

            <button type="submit" class="btn btn-login">Restablecer Clave</button>
        </form>
    </div>
</body>
</html>