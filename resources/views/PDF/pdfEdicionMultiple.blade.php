
                {{-- 0 => {#548 ▼
                    +"id": 1
                    +"codigo": "9011861455640"
                    +"detalle": "Toallitas húmedas prueba porcentaje"
                    +"costo": 480.34
                    +"precio1": 24.2
                    +"precio2": 12.0
                    +"precio3": 0.0
                    +"iva": 21.0
                    +"rubro": "General"
                    +"proveedor": "MAUSE"
                    +"marca": "General"
                    +"pesable": "no"
                    +"controlStock": "no"
                    +"imagen": null
                    +"empresa_id": 1
                    +"created_at": "2024-06-09 22:35:43"
                    +"updated_at": "2024-06-16 21:08:49"
                    +"NuevoPrecio1": 24.2
                    +"NuevoPrecio2": 12.0
                    +"NuevoPrecio3": 0.0
                  } --}}

                  <!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rerporte Edicion Multiple</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        /* font-size: x-small; */

    }

    .gray {
        background-color: lightgray
    }

    .centered-cell {
        text-align: center; /* Centers content horizontally */
        margin: auto;
        
        /* border: 3px solid black; */
        }

        .centered-cell:nth-child(2) { /* Target the second td */
        display: flex;
        align-items: center;
        }


</style>

</head>
<body>


    <h1>Reporte Edicion Multiple</h1>
  

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr  style="">
        <th>Codigo</th>
        <th>Detalle</th>


        <th>NP1</th>


        <th>NP2</th>


        <th>NP3</th>

      </tr>
    </thead>

    <tbody>


        @foreach ($datos as $item)

        <tr>
        <th scope="row">{{$item->codigo}}</th>
        <td>{{$item->detalle}}</td>

        <td align="right">${{$item->precio1}}</td>


        <td align="right">${{$item->precio2}}</td>


        <td align="right">${{$item->precio3}}</td>


        </tr>
            
        @endforeach
    



    </tbody>


  </table>
  
</body>
</html>
