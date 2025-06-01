<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        .header, .footer {
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .company-details, .supplier-details {
            width: 100%;
            margin-bottom: 20px;
        }
        .company-details td, .supplier-details td {
            vertical-align: top;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }
        .summary {
            margin-top: 20px;
            width: 100%;
        }
        .summary td {
            padding: 6px;
        }
        .right {
            text-align: right;
        }
        .signature {
            margin-top: 40px;
            width: 100%;
        }
        .signature td {
            padding-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Orden de Compra</h1>
            <p>N.º OP-001 | Fecha: {{$data['fecha']}}</p>
        </div>

        <table class="company-details">
            <tr>
                <td>
                    <strong>Empresa Compradora:</strong><br>
                    {{$data['empresa']->razonSocial}}<br>
                    CUIT: {{$data['empresa']->cuit}}<br>
                    Dirección: {{$data['empresa']->domicilio}}<br>
                    Teléfono: {{$data['empresa']->telefono}}<br>
                    Email: {{$data['empresa']->correo}}
                </td>
                <td>
                    <strong>Proveedor:</strong><br>
                    {{$proveedor['nombre']}}<br>
                    CUIT: {{$proveedor['cuit']}}<br>
                    Dirección: {{$proveedor['direccion']}}<br>
                    Teléfono: {{$proveedor['telefono']}}<br>
                    Email: {{$proveedor['email']}}
                </td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Costo Unitario</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse  ($ordenDeCompra['articulos'] as $a)
                <tr>
                    <td>
                        {{ $a['codigo'] }}
                    </td>
                    <td>
                        {{ $a['detalle'] }}
                    </td>
                    <td>
                        <p style="text-align: right;">

                            {{ $a['cantidad'] }}
                        </p>
                    </td>

                    <td>
                        <p style="text-align: right;">

                            @if (isset($a['costo']))
                            
                                ${{ number_format($a['costo'], 2, ',', '.') }}
                                
                            @else
                                
                                ${{ number_format($a['costoUnitario'], 2, ',', '.') }}
                            @endif

                        </p>
                    </td>

                    <td>
                        <p style="text-align: right;">
                            @if (isset($a['costo']))
                            
                                ${{ number_format($a['costo'] * $a['cantidad'], 2, ',', '.') }}
                                
                            @else
                                
                                ${{ number_format($a['costoUnitario'] * $a['cantidad'], 2, ',', '.') }}
                            @endif

                        </p>

                    </td>
                </tr>
                    
                @empty
                <tr>
                    <td colspan="5" class="right">No hay artículos en la orden de compra.</td>
                </tr>
                    
                @endforelse


            </tbody>
        </table>

        <table class="summary">
            <tr>
                <td class="right"><strong>Subtotal:</strong></td>
                <td class="right">${{ number_format($data['total'], 2, ',', '.') }}</td>
            </tr>
            {{-- <tr>
                <td class="right"><strong>IVA (21%):</strong></td>
                <td class="right">$4.200,00</td>
            </tr> --}}
            <tr>
                <td class="right"><strong>Total:</strong></td>
                <td class="right">
                    <strong>
                        ${{ number_format($data['total'], 2, ',', '.') }}
                    </strong>
                </td>
            </tr>
        </table>

        <table class="signature">
            <tr>
                <td>
                    ___________________________<br>
                    Firma Autorizada<br>
                    Empresa Compradora
                </td>
                <td class="right">
                    ___________________________<br>
                    Firma Proveedor<br>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Gracias por su atención. Ante cualquier duda, comuníquese con nosotros.</p>
        </div>
    </div>
</body>
</html>
