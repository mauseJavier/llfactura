<!DOCTYPE html>
<html>
<head>
	<title>{{$titulo}}</title>
	<style type="text/css">
		*{
			box-sizing: border-box;
			-webkit-user-select: none; /* Chrome, Opera, Safari */
			-moz-user-select: none; /* Firefox 2+ */
			-ms-user-select: none; /* IE 10+ */
			user-select: none; /* Standard syntax */
		}

		.bill-container{
			border-collapse: collapse;
			max-width: 8cm;
			position: absolute;
			left:0;
			right: 0;
			margin: auto;
			border-collapse: collapse;
			font-family: monospace;
			font-size: 12px;
		}

		.text-lg{
			font-size: 20px;
		}

		.text-center{
			text-align: center;
		}
	

		#qrcode {
			width: 75%
		}

		p {
			margin: 2px 0;
		}

		table table {
			width: 100%;
		}

		
		table table tr td:last-child{
			text-align: right;
		}

		.border-top {
			border-top: 1px dashed;
		}

		.padding-b-3 {
			padding-bottom: 3px;
		}

		.padding-t-3 {
			padding-top: 3px;
		}

	</style>
</head>
<body>
	<table class="bill-container">
		<tr>
			<td class="padding-b-3">
				<p>{{$empresaNombre}}</p>
				<p>{{$direccionEmpresa}}</p>
                <P>DE: {{$titularEmpresa}}</P>
				<p>C.U.I.T.: {{$cuitEmpresa}}</p>
			
				<p>IIBB: {{$cuitEmpresa}}</p>
				<p>Inicio de actividad: {{$inicioActividades}}</p>
			</td>
		</tr>
		<tr>
			<td class="border-top padding-t-3 padding-b-3">
				<p class="text-center text-lg">{{$tipoFactura}}</p>
				<p class="text-center">Codigo {{$codigoFactura}}</p>
				<p>NRO: {{$numeroFactura}}</p>
	
				<p>Fecha: {{$fechaFactura}}</p>
				<p>Concepto: Productos</p>
			</td>
		</tr>
		<tr>
			
				<p>A {{$tipoContribuyente}}</p>
				<p>R.SOCIAL {{$nombreCliente}}</p>
				<p>CUIT {{$cuitCliente}}</p>
			
		</tr>
		<tr>

			
			
		</tr>
		<tr>

			
			
		</tr>
        <tr>
			<td class="border-top padding-t-3 padding-b-3">
				<p>{{$leyenda}}</p>
			</td>
		</tr>
		<tr>
			<td class="border-top padding-t-3 padding-b-3">
				<div>
					<table>
					
                    @if ($producto)
                        @foreach ($producto as $item)

						<tr>
							<th scope="row">{{$item->codigo}}</th>
							<td colspan="2">{{$item->detalle}}</td>
						</tr>
                        <tr>                        
							<td align="right">{{$item->cantidad}}</td>
							<td align="right">${{$item->precio}}</td>
							<td align="right">${{$item->precio * $item->cantidad}}</td>
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


					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="border-top padding-t-3 padding-b-3">
				<div>
					<table>
						@if ($codigoFactura == 1 OR $codigoFactura == 3)
							<tr>
								<td colspan="3"></td>
								<td align="right">Subtotal $</td>
								<td align="right">{{$subtotal}}</td>
							</tr>							
							@if ($iva105 > 0)
								<tr>
									<td colspan="3"></td>
									<td align="right">iva 10.5 $</td>
									<td align="right">{{$iva105}}</td>
								</tr>							  
							@endif
							@if ($iva21 > 0)
								<tr>
								<td colspan="3"></td>
								<td align="right">iva 21 $</td>
								<td align="right">{{$iva21}}</td>
								</tr>							  
							@endif


						@endif
                        <tr>
                            <td colspan="3"></td>
                            <td align="right">Total $</td>
                            <td align="right" class="gray">$ {{$totalVenta}}</td>
                        </tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="border-top padding-t-3">
				<p>CAE: {{$cae}}</p>
				<p>Vto: {{$vtocae}}</p>
			</td>
		</tr>
		<tr class="text-center">
			<td>
                <img src="{{$qr}}" alt="" width="100%">
			</td>
		</tr>
	</table>
</body>
</html>