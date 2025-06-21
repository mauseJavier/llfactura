<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Barras</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0mm;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 100%;
            margin: auto;
            padding: 0;
        }

        .tarjetas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tarjetas-table td {
            padding: 2px;
            vertical-align: top;
        }

        .item {
            width: 6cm;              /* Tarjeta más ancha */
            height: 2.5cm;
            padding: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .item-content span.precio {
            font-size: 20px;         /* Precio más grande */
            font-weight: bold;
            color: black;
        }

        .item-content p {
            margin: 2px 0 0;
            font-size: 10px;
            color: #555;
        }

        .item-bottom {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            padding-top: 2px;
        }

        .item-bottom img {
            max-height: 0.8cm;       /* Código de barras más pequeño */
            width: 70%;
            margin-top: 2px;
        }

        .item-bottom .codigo {
            font-size: 9px;
            color: #444;
            margin-top: 1px;
            font-weight: normal;
            letter-spacing: 0.3px;
        }

        @media print {
            body {
                background: none;
                margin: 0;
            }

            @page {
                size: A4 portrait;
                margin: 5mm;
            }

            .item {
                border: 1px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <table class="tarjetas-table">
            <tr>
                @php use Illuminate\Support\Str; @endphp
                @foreach ($arrayInventario as $index => $item)
                <td>
                    <div class="item">
                        <div class="item-content">
                            <span class="precio">${{ $item['precio'] }}</span>
                            <p>{{ Str::limit($item['detalle'], 20) }}</p>
                        </div>
                        <div class="item-bottom">
                            <img src="data:image/png;base64,{{ $item['barcode'] }}" alt="Código de barras">
                            <br>
                            <span class="codigo">{{ $item['codigo'] }}</span>
                        </div>
                    </div>
                </td>
                @if (($index + 1) % 3 == 0)
                    </tr><tr>
                @endif
                @endforeach
            </tr>
        </table>
    </div>

</body>
</html>
