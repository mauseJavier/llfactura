<div>
    <div class="container">
        <h3>Cierre de Caja</h3>

        @if (session('mensaje'))
            <article>
                <h3 style="color:green;">
                    {{ session('mensaje') }}

                </h3>
            </article>
        @endif

        <article>
            <label for="">Importe de Cierre
                $<input type="text" wire:model.live="importeCierre" placeholder="Importe de Cierre de Caja"
                @error('importeCierre') 
                    aria-invalid="true"
                    aria-describedby="invalid-helper"
                @enderror
                >
                @error('importeCierre') 
                    <small id="invalid-helper">
                        {{ $message }} 
                    </small>
                @enderror
            </label>


            {{-- <input
            type="text"
            name="invalid"
            value="Invalid"
            aria-invalid="true"
            aria-describedby="invalid-helper"
            >
            <small id="invalid-helper">
            Please provide a valid value!
            </small> --}}



            <hr>
            <button wire:click="cerrarCaja" wire:confirm="Esta seguro?">Cerrar Caja</button>
            {{-- PARA ALICIA DE QUINTA empresa_id 48 --}}
            @if (Auth::User()->empresa_id == 48 OR Auth::User()->empresa_id == 91)                 
                <a href="{{route('reportes',['ruta'=>'reporteVentaUsuarioCompleto'])}}" style="cursor: pointer;" role="button">ReporteDiario C.</a>
            @else                
                <a href="{{route('reportes',['ruta'=>'reporteVentaUsuario'])}}" style="cursor: pointer;" role="button">Reporte Diario</a>
            @endif


        </article>

        <hr>

        <h4>Cierres Usuario {{$usuario->name}}</h4>

        <article>
            <table>
                <tbody>
                    @forelse ($cierreDia as $item)
                        <tr>
                            <td>
                                Fecha: {{$item->created_at}}
                            </td>
                            <td style="text-align: right;">
                                ${{number_format($item->importe, 2, ',', '.')}}
                            </td>
                        </tr>                
                    @empty
                        <h6>No existes Cierres del dia.</h6>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            Total:
                        </td>
                        <td style="text-align: right;">
                            ${{number_format($sumaCierre, 2, ',', '.')}}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </article>

            {{-- ADMIN USUARIO --}}
            @if ((Auth::user()->role_id == 2 || Auth::User()->role_id == 3 || Auth::User()->role_id == 4))


                <hr>
                <div class="grid">
                    <h3>Cierre de Usuarios</h3>
                    <input type="date" name="" id="" wire:model.live="fechaCierre">        

                </div>


                @forelse ($cierreTodosUsuarios as $item)
                {{-- 0 => array:5 [▼
                    "titulo" => "Reporte Diario:JAVIER LLFACTURA"
                    "usuario" => "JAVIER LLFACTURA"
                    "fechayhora" => "2025-01-18:21:17:14"
                    "totales" => array:1 [▶]
                    "sumaTotal" => "111,00"
                ] --}}
                    <article>
                        <h4>{{$item['usuario']}}</h4>
                        <table>
                            <tbody>
                                @foreach ($item['totales'] as $t)
                                <tr>
                                    <td>
                                        Tipo Venta: {{$t['nombre']}}
                                    </td>
                                    <td style="text-align:right;">
                                        ${{$t['total']}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td>
                                        Total ventas:
                                    </td>
                                    <td style="text-align:right;">
                                        ${{$item['sumaTotal']}}
                                    </td>
                                </tr>

                            </tfoot>


                        </table>
                            <h6>Cierre Efectivo</h6>
                        <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            Venta en Efectivo:
                                        </td>
                                        <td style="text-align:right;">
                                            ${{$item['totalSoloEfectivo']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Cobro CC en Efectivo:
                                        </td>
                                        <td style="text-align:right;">
                                            ${{$item['cobroCuentasCorrientes']}}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            Total Cierre Efectivo:
                                        </td>
                                        <td style="text-align:right;">
                                            ${{$item['sumaCierre']}}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
        
                                    <tr>
                                        <td>
                                            Diferencia:
                                        </td>
                                        <td style="text-align:right;">
                                            ${{$item['diferencia']}}
                                        </td>
                                    </tr>

                                </tfoot>

                        </table>
                    </article>
                    
                @empty
                    
                @endforelse
                
            @endif
    </div>
</div>
