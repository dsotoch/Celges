<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>403 - Acceso Denegado | Jambc Tecnology</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        img {
            max-width: 150px;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 5rem;
            margin-bottom: 0;
            color: #e74c3c;
        }

        h2 {
            margin-top: 0;
            font-size: 2rem;
        }

        a {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 24px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="https://jamb.pe/wp-content/uploads/2020/08/JAMB-TEHNOLOGY-CALIDAD-Y-GARANTIA-A-TU-SERVICIO-01.svg"
            alt="Jambc Tecnology Logo">
        <h1>403</h1>
        <h2>Acceso Denegado</h2>
        <p>No tienes permiso para realizar esta operación.</p>
        <a href="{{ url()->previous() }}">Volver</a>
    </div>
</body>

</html>
