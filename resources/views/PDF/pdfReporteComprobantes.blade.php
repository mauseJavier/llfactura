<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Comprobantes</title>

    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        /* Encabezado */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        /* Información general */
        .info {
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Estilo para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>

</head>
<body>

    <h2>Comprobantes</h2>

    <label for="">Fecha Desde: {{$fechas['fdesde']}}</label>
    <label for="">Fecha Hasta: {{$fechas['fhasta']}}</label>

    <hr>
    <hr>


    <div class="grid">
        <div class="col">
          <article>
            @foreach ($totales as $item)  
            <label for="">{{$item['nombre']}}: ${{number_format($item['total'], 2, ',', '.')}}</label>                           
            <br>                         
            @endforeach   
          </article>

          <hr>
          
        </div>
        <div class="col">
          <article>
            @foreach ($sumComprobantes as $item)

              @switch($item->tipoComp)
                @case(1)
                  <label for="">Factura A: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break
                @case(6)
                  <label for="">Factura B: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break
                @case(11)
                  <label for="">Factura C: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break
                @case(51)
                  <label for="">Factura M: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break

                @case('remito')
                  <label for="">Remito: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break

                @case('3')
                <label for="">NC A: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break
                @case('8')
                  <label for="">NC B: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break
                @case('13')
                  <label for="">NC C: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break
              @case('notaRemito')
                  <label for="">NC R: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                @break
              @default

              @endswitch
                <br>
            @endforeach
          </article>

        </div>
      </div>

      <hr>

      <article>

        <label for="">Total: ${{number_format($sumTotal, 2, ',', '.')}}</label>
        <hr>
        <hr>

      </article>


      <table class="">
        <thead>
          <tr>

            <th scope="col">Fecha</th>
            <th scope="col">
              Comp.
            </th>
            <th scope="col">Numero</th>
            <th scope="col">Cae</th>
            <th scope="col">Cliente</th>
            <th scope="col">CuitCliente</th>
            <th scope="col">
              Usuario
            </th>
            <th scope="col">Importe</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($comprobantes as $item)
                <tr>

                    <td>{{$item->fecha}}</td>
                    <td>
                      @switch($item->tipoComp)
                          @case(11)
                              C
                              @break
                          @case(6)
                              B
                              @break
                          @case(1)
                              A
                              @break
                          @case(51)
                              M
                              @break
                          @case('remito')
                              R
                            @break
                          @case(3)
                            NC A
                            @break
                          @case(8)
                            NC B
                            @break
                          @case(13)
                            NC C
                            @break
                          @default
                          {{$item->tipoComp}}
                      @endswitch
                      
                    </td>
                    <td>{{$item->numero}}</td>
                    <td>{{$item->cae}}</td>
                    <td>{{$item->razonSocial}}</td>
                    <td>{{$item->cuitCliente}}</td>
                    <td>{{$item->usuario}}</td>
                    <td>${{$item->total}}</td>
                  </tr>
            @endforeach

        
        </tbody>
        <tfoot>
          <tr>

          </tr>
        </tfoot>
    </table>
    
</body>
</html>