<div x-data="{ modalPago: false }">

    {{-- @dd($saldo) --}}

    <div class="container">
        <h3>Cuenta Corriente</h3>
        <h4>{{$cliente->razonSocial}}</h4>

        @if (session('mensaje'))
            <article>
                <p>
                    {{ session('mensaje') }}    
                </p>     
            </article>
        @endif
        @if (session('mensajePago'))
            <article>
                <p style="color: green;">
                    {{-- <strong>Pago Realizado</strong> --}}
                    {{ session('mensajePago') }}    
                </p>     
            </article>
        @endif


        <article>
            <div class="grid">
                
                <div class="col">                      
                    <label for="">
                        
                        <a role="button" href="{{route('cliente')}}">Clientes</a>
                        <button @click="modalPago = !modalPago">Realizar Pago</button>
                        
                    </label>
                </div>
                <div class="col" style="text-align: right;">
                    <h3>Saldo</h3>
                    <h1 style="color: {{ $saldo >= 0 ? 'green' : 'red' }};">${{ number_format($saldo, 2, ',', '.') }}</h1>
                </div>
            </div>

            <hr>


            <details>
                <summary style="color: green;">Filtro</summary>

                <label for="">
                    Desde
                    <input type="date" wire:model.live="fechaDesde" name="date" aria-label="Date">
                </label>

            </details>


        </article>

        {{-- @dump($movimientos) --}}


        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>

                    <th scope="col">Acciones</th>
                    <th scope="col">Fecha</th>

                    <th scope="col">Comentario</th>
                    <th scope="col">Debe(-)</th>
                    <th scope="col">Haber(+)</th>
   
    
                  </tr>
                </thead>
                <tbody>
                    @foreach ($movimientos as $c)
                        <tr>
                            <td>
                                <!-- Dropdown -->
                              <details class="dropdown">
                                <summary>Acciones</summary>
                                <ul>
                                    @if ($c->tipo == 'venta')
                                        <li>

                                            <a rel="noopener noreferrer" href="{{route('productosComprobante',['idComprobante'=>$c->comprobante_id])}}">Ver</a>
                                        </li>
                                    @else
                                        <li>
                                            <a wire:click="imprimirPagoCC({{$c->id}})" >Ver Pago</a>
                                            {{-- <button wire:click="imprimirPagoCC({{$c->id}})">Ver Pago {{$c->id}}</button> --}}

                                        </li>                                     
                                        
                                    @endif
                                    <li><a wire:navigate href="{{route('formatoPDF',['tipo'=>'factura','comprobante_id'=>$c->comprobante_id])}}">Imprimir</a></li>


                                </ul>
                              </details>    
                            </td>
                            <td>{{$c->created_at}}</td>
                            <td>{{$c->comentario}}</td>

                            <td>{{number_format($c->debe, 2, ',', '.')}}</td>
                            <td>{{number_format($c->haber, 2, ',', '.')}}</td>
   
    
    
                        </tr>
                    @endforeach
    
                </tbody>
                
              </table>
          </div>
          {{ $movimientos->links('vendor.livewire.bootstrap') }}




    </div>


    <dialog x-bind:open="modalPago">
        <article>
          <header>
            <button aria-label="Close" rel="prev" @click="modalPago = !modalPago"></button>
            <p>
              <strong>Realizar un Pago Saldo:</strong>(${{$saldo}})
            </p>
          </header>

            <form wire:submit="pagar">
                <fieldset>

                    <label>
                        Forma de Pago 

                        <select    wire:model="formaPago"   name="formaPago"    placeholder="Ingrese una Forma de Pago">

                            @foreach ($formaPagoLista as $item)

                                @if ($item->nombre != 'Cuenta Corriente')
                                    <option value="{{$item->nombre}}">{{$item->nombre}}</option>                                
                                @endif
                                
                            @endforeach

                        </select>
                    @error('formaPago') 
                        <small id="invalid-helper">
                            {{ $message }}
                        </small>
                    @enderror
                    </label>



                    <label>
                        Importe Pagado
                        <input
                        wire:model="importePagado"
                        type="text"
                        name="pago"
                        placeholder="Ingrese un Importe"
                        
                        @error('importePagado') 
                            aria-invalid="true"
                            aria-describedby="invalid-helper"
                        @enderror

                        >
                    @error('importePagado') 
                        <small id="invalid-helper">
                            {{ $message }}
                        </small>
                    @enderror
                    
                    </label>

                    <label>
                        Comentario
                        <input
                        wire:model="comentario"
                        name="comentario"
                        placeholder="Ingrese un Comentario"
                        
                        @error('comentario') 
                            aria-invalid="true"
                            aria-describedby="invalid-helper"
                        @enderror

                        >
                    @error('comentario') 
                        <small id="invalid-helper">
                            {{ $message }}
                        </small>
                    @enderror
                    </label>

                    <label>
                        Telefono
                        <input
                            wire:model="telefono"
                            name="telefono"
                            placeholder="Ingrese un Telefono para el Recibo"
                            
                            @error('telefono') 
                                aria-invalid="true"
                                aria-describedby="invalid-helper"
                            @enderror

                        >
                    @error('telefono') 
                        <small id="invalid-helper">
                            {{ $message }}
                        </small>
                    @enderror
                    </label>

                </fieldset>
                

                <input type="button" value="Cancelar"
                    @click="modalPago = !modalPago"
                    style="background-color: red;">
                <input
                type="submit"
                value="Realizar Pago"
                />
            </form>


        </article>

    </dialog>



</div>
