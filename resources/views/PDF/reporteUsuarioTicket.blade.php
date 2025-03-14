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
			/* font-family: Arial, sans-serif,Verdana; */
			font-size: 14px;
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
			<td class="padding-b-3" colspan="3">
				<p>Fecha:{{$fechayhora}}</p>
				{{-- <P>{{$empresaIva}}</P>
				<p>{{$direccionEmpresa}}</p>
                <P>DE: {{$titularEmpresa}}</P>
				<p>C.U.I.T.: {{$cuitEmpresa}}</p>
			
				<p>IIBB: {{$cuitEmpresa}}</p>
				<p>Inicio de actividad: {{$inicioActividades}}</p> --}}
			</td>
		</tr>
		<tr>
			<td class="padding-b-3" colspan="3">
				<p>IT:{{$inicioTurno}}</p>

			</td>
		</tr>
		<tr>
			<td class="padding-b-3" colspan="3">
				<p>FT:{{$finTurno}}</p>

			</td>
		</tr>
		<tr>
			<td colspan="3">
				<hr>
			</td>
		</tr>
		<tr>
			<td class=" padding-t-3 padding-b-3" colspan="3">
				<p class="text-center text-lg">{{$usuario}}</p>
				{{-- <p class="text-center">Codigo {{$codigoFactura}}</p>
				<p>NRO: {{$numeroFactura}}</p>
	
				<p>Fecha: {{$fechaFactura}}</p>
				<p>Concepto: Productos</p> --}}
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<hr>
			</td>
		</tr>




                        @foreach ($cierreDia as $item)

							<tr>

								<th scope="row">{{$item['descripcion']}}</th>
								<td colspan="2">${{number_format($item['importe'], 2, ',', '.')}}</td>
							</tr>

                            
                        @endforeach
                        



			<tr>
				<td colspan="3">
					<hr>
				</td>
			</tr>

			<tr>
				<td colspan="3">Total: ${{$sumaCierre}}</td>
			</tr>

	</table>


</body>
</html>