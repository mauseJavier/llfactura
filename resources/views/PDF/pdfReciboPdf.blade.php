<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            width: 250px;
            height: 300px;
        }
        .container {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            box-sizing: border-box;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1, .footer p {
            margin: 0;
            font-size: 14px;
        }
        .content {
            margin-bottom: 10px;
        }
        .content h2 {
            margin: 0 0 5px 0;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 2px;
            font-size: 12px;
        }
        .content p {
            margin: 3px 0;
            font-size: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #dddddd;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        @page {
            margin: 1 !important;
            padding: 1 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recibo de Pago</h1>
        </div>
        <div class="content">
            <h2>Detalles del Recibo</h2>
            <p><strong>Fecha: </strong>{{$recibo_id->created_at}}</p>
            <p><strong>Cliente:</strong> {{$cliente->razonSocial}}</p>
            <p><strong>Usuario:</strong> {{$recibo_id->usuario}}</p>
            <p><strong>Número de Recibo:</strong> {{$recibo_id->id}}</p>
            <p><strong>Comentario:</strong> {{$recibo_id->comentario}}</p>
            <p><strong>Forma de Pago:</strong> {{$recibo_id->formaPago}}</p>


        </div>
        <div class="content">
            <h2>Información de Pago</h2>
            <table class="table">
                <tr>
                    <th>Concepto</th>
                    <th>Importe</th>
                    <th>Saldo Resultante</th>
                </tr>
                <tr>
                    <td>Pago realizado</td>
                    <td>${{$recibo_id->haber}}</td>
                    <td>${{$recibo_id->saldo}}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>Gracias por su pago</p>
        </div>
    </div>
</body>
</html>
