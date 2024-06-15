<div >
  {{-- ESN ESTE COMPONENTE UTILIZO ALPINE JS PARA LOS MODALES ABRIR Y CERRAR  --}}
    <div class="container" x-data="{ modalFiltro: false }">

        <article>
            <h3>Ver Comprobantes</h3>
        </article>    

        <div class="grid">
          <div class="col">
            <article>
              @foreach ($totales as $item)                
                <p>{{$item->nombre}}: ${{$item->sumTotal}}</p>                
              @endforeach   

            </article>
             
          </div>
          <div class="col">
            <article>

              @foreach ($sumComprobantes as $item)

                @switch($item->tipoComp)
                  @case(1)
                    <label for="">Factura A: ${{$item->sumTotal}}</label>
                  @break
                  @case(6)
                    <label for="">Factura B: ${{$item->sumTotal}}</label>
                  @break
                  @case(11)
                    <label for="">Factura C: ${{$item->sumTotal}}</label>
                  @break
                  @case('remito')
                    <label for="">Remito: ${{$item->sumTotal}}</label>
                  @break
                @default

                @endswitch
              @endforeach

            </article>

          </div>
        </div>

        <article>

          <label for="">Total: {{$sumTotal}}</label>
        </article>
          
          <hr />
          <fieldset>
            <label>
              <input name="terms" type="checkbox" role="switch" @click="modalFiltro = !modalFiltro" x-bind:checked="modalFiltro" />
              Filtros
            </label>
          </fieldset>

          <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">
                      Comp.
                    </th>
                    <th scope="col">Numero</th>
                    <th scope="col">Importe</th>
                    <th scope="col">Cae</th>
                    <th scope="col">VenCae</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">CuitCliente</th>
                    <th scope="col">
                      Usuario
                    </th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($comprobantes as $item)
                        <tr>
                            <th scope="row"> 
                              <!-- Dropdown -->
                              <details class="dropdown">
                                <summary>Acciones</summary>
                                <ul>
                                  <li><a wire:navigate href="{{route('productosComprobante',['idComprobante'=>$item->id])}}">Ver</a></li>
                                  <li><a wire:navigate href="{{route('formatoPDF',['comprobante_id'=>$item->id])}}">Imprimir</a></li>
                                  <li><a wire:navigate href="{{route('remitoscomprobante')}}">Remitos</a></li>
                                </ul>
                              </details>                       
                            </th>
                            <td>{{$item->fecha}}</td>
                            <td>
                              @switch($item->tipoComp)
                                  @case(11)
                                      Factura C
                                      @break
                                  @case(6)
                                      Factura B
                                      @break
                                  @case(1)
                                      Factura A
                                      @break
                                  @case('remito')
                                      Remito
                                      @break
                                  @default
                                  {{$item->tipoComp}}
                              @endswitch
                              
                            </td>
                            <td>{{$item->numero}}</td>
                            <td>{{$item->total}}</td>
                            <td>{{$item->cae}}</td>
                            <td>{{$item->fechaVencimiento}}</td>
                            <td>{{$item->razonSocial}}</td>
                            <td>{{$item->cuitCliente}}</td>
                            <td>{{$item->usuario}}</td>
                          </tr>
                    @endforeach

                
                </tbody>
                <tfoot>
                  <tr>

                  </tr>
                </tfoot>
            </table>
          </div>

          {{-- {{ $comprobantes->links('paginacion.paginacion') }} --}}
          {{ $comprobantes->links('vendor.livewire.bootstrap') }}
  


          <dialog x-bind:open="modalFiltro">
            <article>
              <header>
                <button aria-label="Close" rel="prev" @click="modalFiltro = !modalFiltro"></button>
                <p>
                  <strong>Filtro Comprobantes</strong>
                </p>
              </header>

              <div class="grid">
                <div class="col">

                  <label for="">
                    Fecha Desde
                    <input type="datetime-local" wire:model.live="fechaFiltroDesde" />
                  </label>
                </div>
                <div class="col">

                  <label for="">
                    Fecha Hasta
                    <input type="datetime-local" wire:model.live="fechaFiltroHasta" />
                  </label>
                </div>

              </div>
              
              <div class="grid">
                <div class="col">
                  <label for="">
                    Comp.
                    <select name="tipoComp" wire:model.live="tipoComp">
                      <option value="">Todo</option>

                      @foreach ($tiposComprobantes as  $key => $value)
                        <option value="{{$key}}">{{$value}}</option>                          
                      @endforeach


                    </select>
                  </label>                  
                </div>
                <div class="col">
                  <label for="">
                    Usuario
                    <input type="text" wire:model.live="usuarioFiltro" name="usuario" placeholder="Usuario"  id="">
                  </label>
                </div>
              </div>

              <label for="">
                Cerrar
                <input name="terms" type="checkbox" role="switch" @click="modalFiltro = !modalFiltro" x-bind:checked="modalFiltro" />
              </label>

            </article>
          </dialog>
</div>
