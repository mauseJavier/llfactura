<div>

    {{-- @dump($empresas) --}}
    <div class="container-fluid">
    
        <h3>Estado Empresas</h3>

          <article>


            <table>
                <tbody>
                    @foreach ($totalPorUsuario as $item)
                    <tr>
                        <td>{{$item->cantidad}}</td>

                        <td>{{$item->usuarioPago}}</td>
                        <td>${{$item->totalPagos}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>{{$total[0]->totalCantidad}}</td>

                        <td style="text-align:right;">TOTAL:</td>
                        <td>${{$total[0]->total}}</td>
                    </tr>
                </tbody>
            </table>

            <div class="grid">

                <div role="group">

                    <button wire:click="exportarCSV()">Exportar</button>
                    <button wire:click="reiniciarPagos()" 
                        wire:confirm="Esta seguro?"
        
                        style="background-color: rgb(168, 122, 122)"
                    
                    >Reiniciar</button>
                </div>


    
                <form role="search">
                    <input wire:model.live="datoBuscado" name="search" type="search" placeholder="Buscar Razon Social, CUIT, Titular" />
                    <input type="submit" value="Buscar" />
                </form>
            </div>

          </article>


          @if (session('mensaje'))
          <article>

              <div style="color: red;">
                  {{ session('mensaje') }}
              </div>
          </article>
            @endif


        <div class="overflow-auto">
            <table>
                <thead>
                <tr>
                    <th scope="col" style="color: red;">id</th>
                    <th scope="col" style="color: red;">RazonSocial</th>
                    <th scope="col" style="color: red;">Titular</th>
                    <th scope="col" style="color: red;">TEL</th>
                    <th scope="col" style="color: red;">TOTAL MES</th>

                    <th scope="col" style="color: red;">Comentario</th>
                    <th scope="col" style="color: red;">Imp.Pago</th>
                    <th scope="col" style="color: red;">Usuario</th>


                    <th scope="col" style="color: red;">

                        <select 
                            name="pago servicio"
                            wire:model.live="filtroPago"
                        >

                            <option value="si">SI</option>
                            <option value="no">NO</option>
                            <option value="">TODO</option>

                        </select>

                    </th>


                    <th scope="col" style="color: red;">Ven de Pago</th>

                    <th><button wire:click="ordenarActualizado">Actualizado</button></th>

                    
                </tr>
                </thead>

                <tbody>
                    @foreach ($empresas as $key => $c)                        
                        <tr 
                            x-data="{ 
                                vencimiento: '{{$c->vencimientoPago}}',
                                pagoMes: '{{number_format($c->pagoMes, 2, ',', '.')}}',
                                comentario: '{{$c->comentario}}',
                                pagoServicio: '{{$c->pagoServicio}}',
                            }"
                        >
                            <th scope="row">{{$c->id}}</th>
                            <th scope="row" >{{$c->razonSocial}}</th>
                            <th scope="row">{{$c->titular}}</th>
                
                
                            <th scope="row">
                                <a href="https://wa.me/54{{$c->telefono}}" target="_blank" rel="noopener noreferrer">{{$c->telefono}}</a>
                            </th>
                
                            <th scope="row">${{ number_format( $c->totalFacturado ,2 )}}</th>
                
                            <th scope="row">
                                <textarea name="" id="" cols="20" rows="1"
                                    style="text-align: center; min-width: 200px;"

                                    x-ref="comentario"
                                    @input="comentario = $event.target.value">{{$c->comentario}}</textarea>
                            </th>
                            <th scope="row">
                                @if ($c->pagoMes > 0)
                                    <input type="number"
                                        style="text-align: center; min-width: 200px;"
                                        value="{{$c->pagoMes}}"
                                        x-ref="pagoMes"
                                        @input="pagoMes = $event.target.value"
                                        readonly>
                                @else
                                    <input type="number"
                                        style="text-align: center; min-width: 200px;"
                                        value="{{$c->pagoMes}}"
                                        x-ref="pagoMes"
                                        @input="pagoMes = $event.target.value">
                                @endif
                            </th>
                
                            <th scope="row">{{ $c->usuarioPago }}</th>
                
                            <th scope="row">
                                <select 
                                        name="pagoServicio"
                                        x-ref="pagoServicio"
                                        x-model="pagoServicio"

                                        @if ($c->pagoServicio === 'no')
                                            style="background-color: red; color: white;"
                                        @else
                                            style="background-color: rgb(60, 186, 34); color: white;"
                                        @endif

                                        @change="pagoServicio = $event.target.value">
                                    <option value="si" :selected="'{{$c->pagoServicio}}' === 'si'">SI</option>
                                    <option value="no" :selected="'{{$c->pagoServicio}}' === 'no'">NO</option>
                                </select>
                            </th>
                
                            <th scope="row"> 
                                <fieldset role="group">
                                    <input type="date" 
                                           value="{{$c->vencimientoPago}}"
                                           x-ref="vencimientoInput"
                                           @input="vencimiento = $event.target.value">
                                    <button type="button" 
                                            wire:click="modificarFechaVencimientoPago({{$c->id}}, $refs.vencimientoInput.value, $refs.pagoMes.value, $refs.comentario.value, $refs.pagoServicio.value)">
                                        Actualizar
                                    </button>
                                </fieldset>
                            </th>

                            <th>{{$c->updated_at}}</th>

                        </tr>
                    @endforeach
                </tbody>
                

            </table>
        </div>
        {{-- {{ $empresas->links('vendor.livewire.bootstrap') }} --}}

    </div>
</div>
