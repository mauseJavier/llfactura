<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$titulo}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .recibo {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            background-color: #ffffff;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }
        .datos-cliente {
            margin-bottom: 20px;
        }
        .datos-cliente h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            border-bottom: 2px solid #6a11cb;
            padding-bottom: 5px;
        }
        .datos-cliente p {
            margin: 5px 0;
            color: #555;
        }
        .detalle-pago {
            margin-bottom: 20px;
        }
        .detalle-pago h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            border-bottom: 2px solid #6a11cb;
            padding-bottom: 5px;
        }
        .detalle-pago table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f9f9f9;
        }
        .detalle-pago th, .detalle-pago td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: left;
        }
        .detalle-pago th {
            background-color: #6a11cb;
            color: #ffffff;
            font-weight: bold;
        }
        .detalle-pago td {
            background-color: #ffffff;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
        }
        .total p {
            margin: 0;
            padding: 10px;
            background-color: #f9f9f9;
            display: inline-block;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="recibo">
        <div class="logo">
            <img src="{{$logo}}" alt="Logo de la Empresa">
        </div>
        <div class="datos-cliente">
            <h2>Datos del Cliente</h2>
            <p><strong>Nombre:</strong> {{$cliente->razonSocial}}</p>
            <p><strong>Dirección:</strong> {{$cliente->domicilio}}</p>
            <p><strong>Teléfono:</strong> {{$cliente->telefono}}</p>
            <p><strong>Email:</strong> {{$cliente->correo}}</p>
        </div>
        <div class="detalle-pago">
            <h2>Detalle del Pago</h2>
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pago Servicio</td>
                        <td>1</td>
                        <td>${{$importe}}</td>
                        <td>${{$importe}}</td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="total">
            <p>Total: ${{$importe}}</p>
        </div>
        <div class="footer">
            <p>Gracias por su Pago.</p>
        </div>
    </div>
</body>
</html>