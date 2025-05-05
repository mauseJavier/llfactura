<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{$titulo}}</title>

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
<body >

  <img style="
  position: absolute;
  top: 50%;
  left: 50%;
  width: 50%;
  /* height: 50%; */
  z-index: -1; /* Behind the content */
  opacity: 0.5; /* Set the transparency */
  transform: translate(-50%, -50%); 
  /* Moves the image back to the exact center */
  background-size: 50% 50%;
  background-position: center;
" src="{{$logoAgua}}" alt="">


  <table width="100%">

    {{-- <tr >
        <td colspan="3" style="text-align: center;">
            <h1 class="centered-cell" style="border: 1px solid black; width: 100px;" >A</h1> 
            <small style="text-align: center;">Cod.11</small> 
        </td> 
    </tr> --}}
    <tr aa="align-content: center; align-items: center;">

      <td  style="width: 250px; "  >
          <img src="{{$logo}}"  alt="" style="width: 300px; margin-top: -30px; margin-left: auto; background-size: cover;"/>

          <table aa="text-align: center;">
            {{-- <tr>
              <td><h1>{{$empresaNombre}}</h1></td>              
            </tr> --}}
            <tr>
              <td>{{$empresaIva}}</td>
           </tr>
            <tr>
               <td>{{$direccionEmpresa}}</td>
            </tr>
            <tr>
              <td>Tel: {{$telefonoEmpresa}}</td>
            </tr>
            <tr>
              <td>Correo: {{$empresaCorreo}}</td>
            </tr>
            <tr>
               <td>DE: {{$titularEmpresa}}</td>
            </tr>
            
          </table>

      </td>
        
      <td colspan="3" class="centered-cell" style="text-align: center; padding-top: 0%;">
        
          <h1 class="centered-cell" style=" width: 100px; height: 50px; margin-top: -20px; font-size: 60px; padding-bottom: 10px;" >{{$abreviatura}}</h1> 
          <small style="text-align: center; margin-top: -60px;">Cod.{{$codigoFactura}}</small> 

      </td> 


      <td class="centered-cell">  
        <ul style="list-style: none;"">
          <li>
            <h2 style="font-size: 300%; margin-top: -40px; margin-left: auto; padding-bottom: -50px;">{{$tipoFactura}}
              <br>
            <small style="font-size: 10px;">Original</small>
          </li>

        </ul>
          
        </h2>    
          <ul style="list-style: none; font-size: 80%;   margin-left: auto;">
            <li>
              NRO: {{$numeroFactura}}
            </li>
            <li>
              Cuit: {{$cuitEmpresa}}
            </li>
            <li>
              Ingresos Brutos: {{$ingresosBrutos}}
            </li>
            <li>
              Inicio Actividades: {{$inicioActividades}}
            </li>
          </ul>

          <div style="width: 100px;" style="height: 40px; " style="border: 1px solid black;" style="background-color:rgb(191, 182, 182)" >
            <small>Fecha:</small>
            <hr>
            <h3 style="margin: 5px;">{{$fechaFactura}}</h3>
          </div>
      </td>
    </tr>    

  </table>
  <hr>
  

  <table width="100%" style="text-align: center;">
    <tr>
        <td align="left">  
            <ul style="list-style-type: none;">
                <li><small> <strong>Cliente: </strong>{{$nombreCliente}}</small></li>
                <li><small>Cuit: <strong>{{$cuitCliente}}</strong></small></li>
                <li><small>Domicilio; <strong>{{$domicilioCliente}}</strong></small></li>

            </ul>
        </td>
        <td align="left">  
            <ul style="list-style-type: none;">
                <li>Condicion IVA: <strong>{{$tipoContribuyente}}</strong></li>
                <li>{{$leyenda}}</li>
                <li>				
                    <p>Forma Pago: {{$nombreFormaPago == $nombreFormaPago2 ? $nombreFormaPago : $nombreFormaPago .'/'.$nombreFormaPago2}}</p>
                </li>
                

            </ul>
        </td>
    </tr>

  </table>

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr  style="">
        <th>Cod.</th>
        <th>Descripcion</th>
        <th>Cant.</th>
        <th>Precio U.$</th>
        <th>Bon.%</th>
        <th>Precio B.$</th>
        <th>Sub.Total.$</th>
      </tr>
    </thead>

    <tbody>

@if ($producto)
  @foreach ($producto as $item)

  <tr>
    <th scope="row">{{$item->codigo}}</th>
    <td>
      {{$item->detalle}}
      @if ($codigoFactura == 1 OR $codigoFactura == 51)
        ({{$item->iva}}%)
      @endif
    </td>
    <td align="right">{{$item->cantidad}}</td>
    <td align="right">{{number_format($item->precioLista,2)}}</td>
    <td align="right">
      {{$item->porcentaje < 0 ? $item->porcentaje : ''}}
    </td>
    <td align="right">{{number_format($item->precio,2)}}</td>

    <td align="right">{{ number_format($item->precio * $item->cantidad,2)}}</td>
  </tr>
      
  @endforeach
    
@else

<tr>
  <th scope="row">00123</th>
  <td>VARIOS</td>
  <td align="right">1</td>
  <td align="right">{{$totalVenta}}</td>
  <td align="right">{{$totalVenta}}</td>
</tr>
    
@endif


  
    </tbody>
    <tr>
      <td colspan="2"></td>
      <td colspan="5"><hr></td>
    </tr>
    
  <tfoot>
    {{-- <tr>
        <td colspan="4"></td>
        <td align="right">Sub.Total</td>
        <td align="right">$ {{$subTotalPrecioLista}}</td>
    </tr>

    <tr>
        <td colspan="4"></td>
        <td align="right">Bon.</td>
        <td align="right">$ {{$totalDescuento}}</td>
    </tr> --}}

    <tr>
        <td colspan="5" align="right" >Importe sin Descuento: </td>


        <td colspan="2" align="right">${{$subTotalPrecioLista}}</td>

    </tr>


    <tr>
        <td colspan="5" align="right" >Importe Descuento: </td>
        <td colspan="2" align="right">${{$totalDescuento}}</td>
       
    </tr>


    <tr>
        <td colspan="5" align="right" >Importe con Descuento: </td>

        <td colspan="2" align="right">
          @if ($codigoFactura == 1 OR $codigoFactura == 51)
                ${{$subtotal}} {{-- Subtotal por que le agrega el iva despues en fac a y m   --}}
          @else
                ${{$totalVenta}} {{-- ak tenes todo los impuesto incluidos --}}
          @endif

        </td>

    </tr>

    <tr>
      <td colspan="7"><hr></td>
    </tr>

      @if ($codigoFactura == 1 OR $codigoFactura == 3 OR $codigoFactura == 51)
        <tr>
            <td colspan="2"></td>
            <td align="right" colspan="2">Subtotal:</td>
            <td align="right" colspan="3">${{$subtotal}}</td>
        </tr>
        @if ($iva105 > 0)
          <tr>
              <td colspan="2"></td>
              <td align="right" colspan="2">iva 10.5 </td>
              <td align="right" colspan="3">${{$iva105}}</td>
          </tr>
            
        @endif
        @if ($iva21 > 0)
          <tr>
            <td colspan="2"></td>
            <td align="right" colspan="2">iva 21 </td>
            <td align="right" colspan="3">$ {{$iva21}}</td>
          </tr>
            
        @endif
      @endif
        <tr>
            <td colspan="2" ></td>
            <td colspan="2" align="right">Total :</td>
            <td colspan="3" align="right" class="gray">$ {{$totalVenta}}</td>
        </tr>


{{-- TRANSPARENCIA FISCAL --}}
        @if ($codigoFactura == 6 )
          <tr>
            <td colspan="4"></td>
          </tr>
          <tr>
              <td align="right" colspan="2">Régimen de Transparencia Fiscal al Consumidor (Ley 27.743)</td>
          </tr>
          <tr>
            <td colspan="4"><hr></td>
          </tr>
          @if ($iva105 > 0)
            <tr>
                <td align="right" colspan="1">Iva 10.5 </td>
                <td align="right" colspan="1">${{$iva105}}</td>
            </tr>
              
          @endif
          @if ($iva21 > 0)
            <tr>
              <td align="right" colspan="1">Iva 21 </td>
              <td align="right" colspan="1">$ {{$iva21}}</td>
            </tr>
              
          @endif
        @endif




        <tr class="bill-row row-details">
          <td>
            <div>
              <div class="row">
                            <img src="{{$qr}}" alt="" width="100%">
              </div>
            </div>
          </td>
          <td>
            <div>
              <div class="row text-right margin-b-10">
                <strong>CAE Nº:&nbsp;</strong> {{$cae}}
              </div>
              <div class="row text-right">
                <strong>Fecha de Vto. de CAE:&nbsp;</strong> {{$vtocae}}
              </div>
            </div>
          </td>
        </tr>


    </tfoot>
  </table>

  {{-- <img src="{{$qr}}" alt="" width="20%"> --}}

  <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(270, 820, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }
  </script>
  
</body>
</html>