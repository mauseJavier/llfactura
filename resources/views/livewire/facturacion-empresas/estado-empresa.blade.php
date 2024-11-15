<div>

    {{-- @dump($empresas) --}}
    <div class="container-fluid">
    
        <h3>Estado Empresas</h3>

          <article>
            <button wire:click="exportarCSV()">Exportar</button>
            <button wire:click="reiniciarPagos()" 
                wire:confirm="Esta seguro?"

                style="background-color: rgb(168, 122, 122)"
            
            >Reiniciar</button>

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
                    <th scope="col" style="color: red;">CUIT</th>
                    <th scope="col" style="color: red;">TEL</th>
                    <th scope="col" style="color: red;">TOTAL MES</th>

                    <th scope="col" style="color: red;">Ven de Pago</th>
                    <th scope="col" style="color: red;">Pago Servicio</th>

                    
                </tr>
                </thead>
                <tbody>
                    @foreach ($empresas as $key => $c)                        
                        <tr x-data="{ vencimiento: '{{$c->vencimientoPago}}' }">
                            <th scope="row">{{$c->id}}</th>
                            <th scope="row">{{$c->razonSocial}}</th>
                            <th scope="row">{{$c->cuit}}</th>

                            <th scope="row">
                                <a href="https://wa.me/54{{$c->telefono}}" target="_blank" rel="noopener noreferrer">{{$c->telefono}}</a>
                            </th>

                            <th scope="row">${{ number_format( $c->totalFacturado ,2 )}}</th>

 
                            <th scope="row" > 
                                <fieldset role="group">

                                    <input type="date" 
                                           value="{{$c->vencimientoPago}}"
                                           
                                           x-ref="vencimientoInput"
                                           @input="vencimiento = $event.target.value">
                            
                                    <button type="button" 
                                            wire:click="modificarFechaVencimientoPago({{$c->id}}, $refs.vencimientoInput.value)">
                                        Actualizar
                                    </button>

                                </fieldset>


                            </th>
                            <th scope="row">
                                <fieldset>
                                    <label for="">
                                        @if ($c->pagoServicio == 1)
                                            SI
                                        @else
                                            NO  
                                        @endif
                                        <input name="terms" type="checkbox" role="switch"
                                            wire:click="pagarEmpresa({{$c->id}}, $refs.vencimientoInput.value)"
                                            @if ($c->pagoServicio == 1)
                                                checked                                             
                                            @endif
                                        />
                                    </label>                              
                                    
                                  </fieldset>
                                </th>



                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
        {{-- {{ $empresas->links('vendor.livewire.bootstrap') }} --}}

    </div>
</div>
