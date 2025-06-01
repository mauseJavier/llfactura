<div>

    <div class="container">
        <h3>Orden de Compra</h3>


        @if (session()->has('mensaje'))
            <div class="alert alert-success">
                <h4>
                    {{ session('mensaje') }}
                </h4>
            </div>
        @endif


        <article>
                {{-- <details>
                    <summary>Datos Proveedor</summary>

                    <div class="grid">
                        <div class="col">
                            <label for="">
                                <input type="text" name="proveedor" placeholder="Nombre Proveedor" aria-label="Text"
                                    wire:model="nombreProveedor"
                                    @error('nombreProveedor')                                     
                                        aria-invalid="true"
                                        aria-describedby="invalid-helper"
                                    @enderror
                                    >
                                    <div>@error('nombreProveedor') 
                                        <small id="invalid-helper">
                                            {{ $message }} 
                                        </small>
                                        @enderror
                                    </div>

                            </label>
                            <label for="">
                                <input type="text" name="direccion" placeholder="Direccion Proveedor" aria-label="Text"
                                    wire:model="direccionProveedor"
                                    @error('direccionProveedor')                                     
                                        aria-invalid="true"
                                        aria-describedby="invalid-helper"
                                    @enderror
                                    >
                                    <div>@error('direccionProveedor') 
                                        <small id="invalid-helper">
                                            {{ $message }} 
                                        </small>
                                        @enderror
                                    </div>

                            </label>    
                            <label for="">
                                <input type="text" name="email" placeholder="Email Proveedor" aria-label="Text"
                                    wire:model="emailProveedor"
                                    @error('emailProveedor')                                     
                                        aria-invalid="true"
                                        aria-describedby="invalid-helper"
                                    @enderror
                                    >
                                    <div>@error('emailProveedor') 
                                        <small id="invalid-helper">
                                            {{ $message }} 
                                        </small>
                                        @enderror
                                    </div>
                            </label>

                        </div>
                        <div class="col">
                            <label for="">
                                <input type="text" name="cuit" placeholder="CUIT" aria-label="Text"
                                    wire:model="cuitProveedor">
                            </label>
                            <label for="">
                                <input type="text" name="telefono" placeholder="Telefono Proveedor" aria-label="Text"
                                    wire:model="telefonoProveedor">
                            </label>

                            <button wire:click="guardarDatosProveedor">Guardar</button>


                        </div>
                    </div>


                </details>       --}}
                <div class="col">
        
                    <!-- Select -->
                    <select name="select" aria-label="Select" wire:model="idProveedor" wire:change="guardarDatosProveedor">  >
                        <option selected disabled value="0">Seleccionar Proveedor</option>
                        @foreach ($listaProveedores as $p)
                            <option value="{{$p->id}}">{{$p->nombre}}</option>  
                        @endforeach
                        
                    </select>
                </div>
        </article>



        @if (count($ordenDeCompra['articulos']) > 0)

            <article style="position: sticky; top: 0; z-index: 100; ">

                <div style="max-height: 500px; overflow-y: auto;">
                    <table class="table striped" style="width: 100%;">
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
                            @forelse ($ordenDeCompra['articulos'] as $a => $item)
                                <tr>
                                    <td>
                                        <button wire:click="eliminarOrdenCompra({{ $a }})">
                                                <!-- trash icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                                                    <path
                                                    d="M 210 32 L 302 32 L 210 32 L 302 32 Q 311 32 316 40 L 331 64 L 331 64 L 181 64 L 181 64 L 196 40 L 196 40 Q 201 32 210 32 L 210 32 Z M 369 64 L 343 23 L 369 64 L 343 23 Q 328 1 302 0 L 210 0 L 210 0 Q 184 1 169 23 L 143 64 L 143 64 L 96 64 L 48 64 Q 33 65 32 80 Q 33 95 48 96 L 66 96 L 66 96 L 92 453 L 92 453 Q 94 478 112 495 Q 130 511 156 512 L 356 512 L 356 512 Q 382 511 400 495 Q 418 478 420 453 L 446 96 L 446 96 L 464 96 L 464 96 Q 479 95 480 80 Q 479 65 464 64 L 448 64 L 369 64 Z M 414 96 L 388 450 L 414 96 L 388 450 Q 387 463 378 471 Q 369 480 356 480 L 156 480 L 156 480 Q 143 480 134 471 Q 125 463 124 450 L 98 96 L 98 96 L 414 96 L 414 96 Z"
                                                    />
                                                </svg>
                                            {{ $item['codigo'] }}
                                        </button>
                                    </td>
                                    <td>{{ $item['detalle'] }}</td>
                                    <td>{{ $item['cantidad'] }}</td>
                                    <td>${{ number_format($item['costo'], 2, ',', '.') }}</td>
                                    <td>${{ number_format($item['costo'] * $item['cantidad'], 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-right">No hay artículos en la orden de compra.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


            </article>

            
        @endif

        
        <article>
            <div class="grid">
                <div class="col" style="text-align: right;">
                    <label for="">
                        <h4>Total: ${{number_format($total,2,',','.')}}</h4>
                    </label>

                    <button wire:click="borrarOrdenCompra" style="background-color: red">Cancelar</button>
                    <button wire:click="guardarOrdenCompra">Guardar</button>
                    {{-- <a role="button" href="{{Route('imprimirOrdenCompra')}}" target="_blank" rel="noopener noreferrer">Imprimir</a> --}}
        
                </div>
                
            </div>

        </article>

        {{-- <article style="display: flex; gap: 10px; align-items: center;">
            <div class="col" style="width: 20%;">
                <input type="number" name="cantidad" placeholder="Cantidad" aria-label="Number"
                    wire:model.live="cantidad"
                    step="0.1"
                >
            </div>
        
            <div class="col" style="width: 80%;">
                <input type="search" name="search" placeholder="Buscar" aria-label="Search"
                    wire:model.live="codigoDetalle"
                />
            </div>
        </article> --}}

        <article>

            <div class="div" >
                <form role="search"  wire:submit="buscarCargar" > 

                  <input style="text-align: center; width: 20%;" class="seleccionarTodo" 
                    wire:model.live="cantidad"
                    {{-- wire:keydown.down="restarCantidad"
                    wire:keydown.up="sumarCantidad" --}}
                    type="text"
                    style="font-size: 15px;"
                  
                  >
                  <input name="search" type="search" placeholder="Buscar en Inventario" class="seleccionarTodo"
                      wire:model.live="codigoDetalle" 
                      wire:keydown.down="restarCantidad"
                      wire:keydown.up="sumarCantidad"
                      autocomplete="off"
                      style="font-size: 15px;"
                      autofocus
                      x-ref="inputField"
                   />
                  
                  <button type="submit" >
                    <!-- magnifying-glass icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                      <path
                        d="M 398.44357976653697 207.19066147859922 Q 398.44357976653697 155.3929961089494 372.54474708171205 111.56420233463035 L 372.54474708171205 111.56420233463035 L 372.54474708171205 111.56420233463035 Q 347.6420233463035 67.73540856031128 302.8171206225681 41.83657587548638 Q 257.9922178988327 15.937743190661479 207.19066147859922 15.937743190661479 Q 156.38910505836577 15.937743190661479 111.56420233463035 41.83657587548638 Q 66.73929961089495 67.73540856031128 41.83657587548638 111.56420233463035 Q 15.937743190661479 155.3929961089494 15.937743190661479 207.19066147859922 Q 15.937743190661479 258.988326848249 41.83657587548638 302.8171206225681 Q 66.73929961089495 346.6459143968872 111.56420233463035 372.54474708171205 Q 156.38910505836577 398.44357976653697 207.19066147859922 398.44357976653697 Q 257.9922178988327 398.44357976653697 302.8171206225681 372.54474708171205 Q 347.6420233463035 346.6459143968872 372.54474708171205 302.8171206225681 Q 398.44357976653697 258.988326848249 398.44357976653697 207.19066147859922 L 398.44357976653697 207.19066147859922 Z M 347.6420233463035 359.5953307392996 Q 290.863813229572 412.3891050583658 207.19066147859922 414.38132295719845 Q 119.53307392996109 412.3891050583658 60.762645914396884 353.61867704280155 Q 1.9922178988326849 294.84824902723733 0 207.19066147859922 Q 1.9922178988326849 119.53307392996109 60.762645914396884 60.762645914396884 Q 119.53307392996109 1.9922178988326849 207.19066147859922 0 Q 294.84824902723733 1.9922178988326849 353.61867704280155 60.762645914396884 Q 412.3891050583658 119.53307392996109 414.38132295719845 207.19066147859922 Q 412.3891050583658 290.863813229572 359.5953307392996 347.6420233463035 L 508.01556420233464 496.0622568093385 L 508.01556420233464 496.0622568093385 Q 512 502.03891050583655 508.01556420233464 508.01556420233464 Q 502.03891050583655 512 496.0622568093385 508.01556420233464 L 347.6420233463035 359.5953307392996 L 347.6420233463035 359.5953307392996 Z"
                      />
                    </svg>
                  </button>
                </form>
    
            </div>

        </article>
    </div>

    <div class="container">

        {{-- @dump($inventario) --}}
        {{-- "id" => 1
        "codigo" => "4977864619647"
        "detalle" => "Shampoo"
        "costo" => 437.42
        "precio1" => 96.39
        "precio2" => 85.69
        "precio3" => 228.37
        "porcentaje" => 0.0
        "iva" => 21.0
        "rubro" => "Electrónica"
        "proveedor" => "General"
        "marca" => "Nike"
        "pesable" => "si"
        "controlStock" => "no"
        "imagen" => null
        "empresa_id" => 1
        "favorito" => 0
        "created_at" => "2025-03-31 18:51:00"
        "updated_at" => "2025-03-31 18:51:00" --}}

        <div class="overflow-auto">
            <table class="striped">
                <thead>
                    <tr>
                    <th scope="col">Codigo</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Costo</th>
                    <th scope="col">Rubro</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">Marca</th>


                    </tr>
                </thead>
                <tbody>


                    @forelse ($inventario as $i)
                        <tr>
                            <td>
                                <button wire:click="agregarOrdenCompra({{{$i->id}}})">
                                    {{$i->codigo}}
                                </button>
                                
                            </td>
                      
                            <td>
                                {{$i->detalle}}
                            </td>
                    
                            <td>
                                ${{number_format($i->costo, 2, ',', '.')}}
                            </td>
                     
                            <td>
                                {{$i->rubro}}
                            </td>
                      
                            <td>
                                {{$i->proveedor}}
                            </td>
                  
                            <td>
                                {{$i->marca}}
                            </td>
                        </tr>
                        
                    @empty
                        <h3>Sin resultados</h3>
                    @endforelse
                </tbody>

            </table>
          </div>

          {{ $inventario->links('vendor.livewire.bootstrap') }}
    </div>


    

    

</div>
