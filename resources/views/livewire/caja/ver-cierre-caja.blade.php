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
            
            
            <div class="grid">

                <div class="col">

                    <fieldset role="group">

                        <input type="text" wire:model.live="importeInicio" placeholder="Importe de Inicio de Caja"
                        @error('importeInicio') 
                            aria-invalid="true"
                            aria-describedby="invalid-helper"
                        @enderror
                        >
                        <input type="button" value="Iniciar Caja" wire:click="inicioCaja" wire:confirm="Esta seguro de Iniciar?">
                      </fieldset>
                        @error('importeInicio') 
                         <small id="invalid-helper">
                            {{ $message }} 
                          </small>
                        @enderror

                </div>  

                <div class="col">

                    <fieldset role="group">

                        <input type="text" wire:model.live="importeCierre" placeholder="Importe de Cierre de Caja"
                        @error('importeCierre') 
                            aria-invalid="true"
                            aria-describedby="invalid-helper"
                        @enderror
                        >
                        <input type="button" value="Cerrar Caja" wire:click="cerrarCaja" wire:confirm="Esta seguro de Cerrar?" style="background-color: red">
                      </fieldset>
                        @error('importeCierre') 
                         <small id="invalid-helper">
                            {{ $message }} 
                          </small>
                        @enderror

                </div>                    

            </div>


            
                    
                    
            <form target="_blank"
                    {{-- PARA EL TOMI QUE ES 44 EL REPORTE  --}}
                @if (Auth::user()->empresa_id != 44)
                    action="{{ route('reporteVentaUsuarioCompleto') }}" method="POST"
                @else
                    action="{{ route('reporteVentaUsuario') }}" method="POST"

                @endif 
            >
                @csrf
                <div class="grid">


                        <div class="col">
                            <label for="">
                                Inicio Turno
                                <input type="datetime-local" name="inicioTurno" aria-label="Datetime local" wire:model.live="inicioTurno"

                                    {{-- VOLVEMOS A ACTIVAR LA MODIFICACION DEL CAMPO PARA QUE NO SEA SOLO DE LECTURA COMO ANTES
                                    @if (Auth()->user()->role_id == 1)
                                        disabled
                                    @endif --}}
                                >
                                
                            </label>
                
                            
                        </div>
                        
                        <div class="col">
                                        
                            <label for="">
                                Fin Turno
                                <input type="datetime-local" name="finTurno" aria-label="Datetime local" wire:model.live="finTurno"
                                                                    
                                    {{-- VOLVEMOS A ACTIVAR LA MODIFICACION DEL CAMPO PARA QUE NO SEA SOLO DE LECTURA COMO ANTES
                                    @if (Auth()->user()->role_id == 1)
                                        disabled
                                    @endif --}}
                                >
                                
                            </label>
                            
                        </div>
                                       

                    
                </div>
                <div class="grid">
                    <div class="col">
    
                        <button type="submit">Generar Reporte</button>
    
                    </div>

                </div>
            </form>


            


        </article>

        <hr>

        <h4>Cierres Usuario {{$usuario->name}}</h4>

        <article>
            <table>
                <tbody>

                    @forelse ($cierres as $c)
                        <tr>
                            <td>
                                {{$c->created_at}}
                            </td>
                            <td>
                                {{$c->descripcion}}
                            </td>
                            <td style="text-align: right;">
                                ${{number_format($c->importe, 2, ',', '.')}}
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
                        <td style="text-align: right;" colspan="2">
                            ${{number_format($sumaCierre, 2, ',', '.')}}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </article>

            {{-- ADMIN USUARIO --}}
            @if ( Auth::User()->role_id == 3 || Auth::User()->role_id == 4)


                <hr>
                <div class="grid">
                    <h3>Cierre de Usuarios</h3>
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
                                            -${{$item['totalSoloEfectivo']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Cobro CC en Efectivo:
                                        </td>
                                        <td style="text-align:right;">
                                            -${{$item['cobroCuentasCorrientes']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Gastos Pagados en Efe.:
                                        </td>
                                        <td style="text-align:right;">
                                            ${{$item['sumaGastos']}}
                                        </td>
                                    </tr>
                                    @foreach ($item['cierres'] as $c)

                                        <tr>
                                            <td>
                                                {{$c->descripcion}} - ({{$c->created_at}})
                                            </td>
                                            <td style="text-align:right;">
                                                ${{$c->importe}}
                                            </td>
                                        </tr>                                        
                                    @endforeach
                                    {{-- <tr>
                                        <td>
                                            Total Cierre Efectivo:
                                        </td>
                                        <td style="text-align:right;">
                                            ${{$item['sumaCierre']}}
                                        </td>
                                    </tr> --}}
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
