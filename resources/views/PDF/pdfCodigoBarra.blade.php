{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Artículos</title>
        <style>
            body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .items {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Tres columnas de igual ancho */
            gap: 10px;
        }

        .item {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .item img {
            max-width: 100%;
            height: auto;
        }

        .item p {
            margin: 8px 0;
            font-weight: bold;
        }

        .item span {
            display: block;
            margin-top: 5px;
            font-size: 10px;
        }

        </style>

</head>
<body>
    <div class="container">
        <h1>Listado de Artículos</h1>

        <table>
            <tbody>

                @foreach ($arrayInventario as $key => $item)
                    
                    <tr>
                        <td>
                            <div class="item">
                                <img src="https://barcode.tec-it.com/barcode.ashx?data={{$item['codigo']}}&code={{$item['tipo']}}" alt="Código de Barras">
                                <p>{{$item['detalle']}}</p>
                                <span>Precio: ${{$item['precio']}}</span>
                            </div>
                        </td>
                        <td>
                            <div class="item">
                                <img src="https://barcode.tec-it.com/barcode.ashx?data=123456789012&code=EAN13" alt="Código de Barras">
                                <p>Artículo 1 - Detalle del producto</p>
                                <span>Precio: $10.00</span>
                            </div>
                        </td>
                        <td>
                            <div class="item">
                                <img src="https://barcode.tec-it.com/barcode.ashx?data=123456789012&code=EAN13" alt="Código de Barras">
                                <p>Artículo 1 - Detalle del producto</p>
                                <span>Precio: $10.00</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codigo De Barras</title>

    <style>
        body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 10px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 10px;
    }

    .items {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* Tres columnas de igual ancho */
        gap: 10px;
    }

    .item {
        
        padding: 5px;
        text-align: center;
        border: 1px solid black;
        border-radius: 5px;
    }

    .item img {
        max-width: 100%;
        height: auto;
    }

    .item p {
        margin: 2px 0;
        font-weight: bold;
        font-size: 12px;
    }

    .item span {
        display: block;
        margin-top: 2px;
        font-size: 10px;
    }
    tr.{
        width: 100%;
    }
    td.{
        width: 33%;
    }

    </style>

</head>
<body>
    <div class="container">
        <table class="tarjetas-table">
            <tr>
                @foreach ($arrayInventario as $index => $item)

                <td>
                    <div class="item">
                        <img src="https://barcode.tec-it.com/barcode.ashx?data={{$item['codigo']}}&code={{$item['tipo']}}" alt="Código de Barras">
                        <p>{{$item['detalle']}}</p>
                        <span>Precio: ${{$item['precio']}}</span>
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
