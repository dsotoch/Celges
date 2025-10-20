{{-- resources/views/errors/419.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>419 - Sesión Expirada | Jambc Technology</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary-color: #3498db;
            --hover-color: #2980b9;
            --error-color: #e67e22;
            --background: #f4f4f4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--background);
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .container {
            text-align: center;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 100%;
        }

        img {
            max-width: 120px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 5rem;
            color: var(--error-color);
            margin: 0;
        }

        h2 {
            font-size: 1.8rem;
            margin: 0.5rem 0 1rem;
        }

        p {
            font-size: 1rem;
            color: #666;
        }

        button {
            display: inline-block;
            margin-top: 20px;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--hover-color);
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="https://jamb.pe/wp-content/uploads/2020/08/JAMB-TEHNOLOGY-CALIDAD-Y-GARANTIA-A-TU-SERVICIO-01.svg"
            alt="Jambc Technology Logo">
        <h1>419</h1>
        <h2>Sesión Expirada</h2>
        <p>Tu sesión ha expirado o el token CSRF no es válido. Por favor, vuelve a intentarlo.</p>
        <button onclick="window.location.reload()">Recargar Página</button>
    </div>
</body>

</html>
