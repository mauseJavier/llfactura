<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #454647;
            color: #0c2532;
        }
        .content {
            text-align: center;
            color: black;
        }
        .title {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <img src="{{ asset('logosLLFactura/LLFACTURA.png') }}" alt="LLFactura Logo">
    <div class="content">
        <div class="title">Estamos trabajando en el servidor</div>
        <p>Momentáneamente estamos realizando tareas de mantenimiento.</p>

        {{-- <h1>
            <p>Por favor, accede al servicio en: 
                <a href="https://llf.llservicios.ar" class="link" target="_blank">llf.llservicios.ar</a>
            </p>
        </h1> --}}
        <p>Gracias por tu paciencia.</p>
    </div>
</body>
</html>
