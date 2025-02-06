<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resumen Mesa {{$mesa->nombre}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            
            margin: 0;
            padding: 5px;
        }
        .ticket {
            text-align: center;
        }
        h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .info {
            text-align: left;
            margin-bottom: 10px;
        }
        .info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border-bottom: 1px dashed #000;
            padding: 5px;
            text-align: left;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <h4>Resumen</h4>
        <h2>{{$mesa->nombre}}</h2>
        <div class="info">
            <p><strong>Numero:</strong> {{$mesa->numero}}</p>
            <p><strong>Cliente:</strong> {{$mesa->razonSocial}}</p>
            <p><strong>Fecha:</strong> {{$mesa->created_at}}</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Producto</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data->mesaCarrito as $item)
                    
                    <tr>
                        <td>{{$item->cantidad}}</td>
                        <td>{{$item->detalle}}</td>
                        <td>${{$item->precio}}</td>
                    </tr>
                    
                @empty
                    <tr>
                        <td colspan="3">Sin resultados</td>
                    </tr>
                    
                @endforelse
            </tbody>
        </table>
        <p class="total">Total: ${{$data->total}}</p>
        <p class="footer">Gracias por su visita</p>
    </div>
</body>
</html>
