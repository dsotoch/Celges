<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JAMB Technology</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #f39c12, orangered);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-card img {
            width: 150px;
            margin-bottom: 1rem;
        }

        .btn-orange {
            background-color: orangered;
            color: white;
        }

        .btn-orange:hover {
            background-color: #cf711c;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="https://jamb.pe/wp-content/uploads/2020/08/JAMB-TEHNOLOGY-CALIDAD-Y-GARANTIA-A-TU-SERVICIO-01.svg" alt="Logo JAMB" class="img-fluid">
    <h4 class="mb-4 text-orange">Iniciar Sesión</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('usuarios.login') }}" method="POST">
        @csrf
        <div class="form-group text-left">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required placeholder="ejemplo@jamb.pe">
        </div>

        <div class="form-group text-left">
            <label for="password">Contraseña</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-orange btn-block">Ingresar</button>
    </form>

    <p class="mt-4 text-muted">© 2025 JAMB Technology</p>
</div>

</body>
</html>
