<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Repoerte Venta Por Articulos</title>

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
            padding: 2px;
            text-align: left;
            font-size: 6px;
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


    <div>

        <div class="container">
            <h3>Ventas por Articulo</h3>
        
            <article>

                <label for="">Fecha Desde: {{$fechas['fdesde']}}</label>
                <label for="">Fecha Hasta: {{$fechas['fhasta']}}</label>

        
                  <h3>Suma de Venta: ${{$totales['precioVenta']}}</h3>
                  <h4>IVA: ${{$iva}}</h4>
                  <h4>Suma de Costo: ${{$totales['costoVenta']}}</h4>
                  <h4>Resultado: ${{$totales['resultadoVenta']}} (%{{$totales['resultadoPorcentaje']}})</h4>

                  <hr>

        
        
            </article>
        
            {{-- <article>
                <h3>Suma de Venta: ${{$precioVenta}}</h3>
            </article> --}}
        
            <div class="overflow-auto">
                <table class="striped">
                    <thead>
        
                      <tr>
                        <th scope="col">Codigo</th>
                        <th scope="col">Detalle</th>
                        <th scope="col">Cant.</th>
                        <th scope="col">Costo</th>
                        <th scope="col">P.Lista</th>
                        <th scope="col">Descuento</th>
                        <th scope="col">P.Venta</th>
                        <th scope="col">Rubro</th>
                        <th scope="col">Proveedor</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Fecha</th>
        
        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($articulos as $a)
                            
                            {{-- 3, 8, 13, "notaRemito" documentos de nota de credito --}}
                            @if ($a->tipoComp == 3 OR $a->tipoComp == 8 OR $a->tipoComp == 13 OR $a->tipoComp == "notaRemito")
                                <tr>
                                    <th scope="row">{{$a->codigo}} ({{$a->tipoComp}})</th>
                                    <td>{{$a->detalle}}</td>
                                    <td>{{$a->cantidad}}</td>
                    
                                    <td>$-{{number_format($a->costo,2,',','.')}}</td>
                                    <td>$-{{number_format($a->precio,2,',','.')}}</td>
                                    <td>${{number_format($a->descuento)}}</td>
                                    <td>${{number_format($a->precioLista,2,',','.')}}</td>
                                    <td>{{$a->rubro}}</td>
                                    <td>{{$a->proveedor}}</td>
                                    <td>{{$a->marca}}</td>
                                    <td>{{$a->fecha}}</td>    
                                </tr>
                            @else
                                
                                <tr>
                                    <th scope="row">{{$a->codigo}}</th>
                                    <td>{{$a->detalle}}</td>
                                    <td>{{$a->cantidad}}</td>
                        
                                    <td>${{number_format($a->costo,2,',','.')}}</td>
                                    <td>${{number_format($a->precioLista,2,',','.')}}</td>
                                    <td>${{number_format($a->descuento)}}</td>
                                    <td>${{number_format($a->precio,2,',','.')}}</td>
                                    <td>{{$a->rubro}}</td>
                                    <td>{{$a->proveedor}}</td>
                                    <td>{{$a->marca}}</td>
                                    <td>{{$a->fecha}}</td>
                                </tr>
                            @endif
                        @endforeach
            
                    </tbody>
                    <tfoot>
                      <tr>
            
                      </tr>
                    </tfoot>
                  </table>
              </div>
              
        
         
        
        
        </div>
        
        
        </div>
        
    
</body>
</html>