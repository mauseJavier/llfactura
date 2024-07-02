<div x-data="{ isOpenRubro: false, isOpenProveedor: false, isOpenMarca: false, isOpenLista: false}">
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}

    {{-- @dump($inventario) --}}
    {{-- @dump($listaRubros) --}}
    {{-- @dump($listaProveedores) --}}
    {{-- @dump($empresa) --}}
    {{-- @dump($listaMarcas) --}}
    <div class="container"   >
        <h1>Inventario</h1>
        <article>
            
            <div class="grid">

                <div class="col">
                    <div class="grid">
                        <div class="col">

                            <!-- Dropdown -->
                            <details class="dropdown">
                                <summary>OPCIONES</summary>
                                <ul>
                                    <li>
                                        <a role="button" class="outline"  wire:click="cambiarModal">Nuevo Articulo</a>
                                    </li>
                                    <li><a role="button" class="outline"  @click="isOpenRubro = !isOpenRubro">Rubro</a></li>
                                    <li>
                                        <a role="button" class="outline"  @click="isOpenProveedor = !isOpenProveedor">Proveedor</a>
                                    </li>
                                    <li>
                                        <a role="button" class="outline"  @click="isOpenMarca = !isOpenMarca">Marca</a>
                                    </li>
                                    <li>
                                        <a role="button" class="outline"  @click="isOpenLista = !isOpenLista">Lista Precio</a>
                                    </li>
                                    <li>
                                        <a role="button" class="outline" wire:navigate href="{{route('edicionMultiple')}}">Edicion Multiple</a>
                                    </li>
                                    <li>
                                        <a role="button" class="outline" wire:navigate href="{{route('importarInventario')}}">Importar</a>
                                    </li>
                                </ul>
                            </details>
                        </div>
                        <div class="col">
                            <button wire:click="cambiarModal">Nuevo Articulo</button>
                            <a wire:navigate role="button" href="{{route('stock')}}">Stock</a>
        
                        </div>

                    </div>
                    

                </div>

                <div class="col">
                    <form role="search"  wire:submit="">      
                        
                        <input wire:model.live="datoBuscado" name="search" type="search" placeholder="Buscar en Inventario" class="seleccionarTodo" />
                        {{-- <input type="submit" value="Buscar" /> --}}
                    </form>
                </div>
            </div>
                
        </article>
     
        <fieldset>
            <label>
              <input wire:model.live="masDatos" name="detalles" type="checkbox" role="switch" />
              Ver mas Datos
            </label>
        </fieldset>

        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    {{-- <th scope="col">id</th> --}}
                    <th scope="col">Codigo</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Precio</th>
                    @if ($masDatos)                        
                        <th scope="col">Costo</th>
                        <th scope="col">Iva</th>
                        <th scope="col">Rubro</th>
                        <th scope="col">Proveedor</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Control Stock</th>
                        <th scope="col">Pesable</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Creado</th>
                        <th scope="col">Actualizado</th>
                    @endif
                  </tr>
                </thead>
                <tbody>

                    @foreach ($inventario as $i)
                        
                        <tr>
                        {{-- <th scope="row">{{$i->id}}</th> --}}
                        <td> <button wire:click="editarId({{$i->id}})"> <i class="fa-regular fa-pen-to-square"></i> {{$i->codigo}}</button> </td>
                        <td>{{$i->detalle}}</td>
                        <td>
                            <!-- Dropdown -->
                            <details class="dropdown">
                            <summary>${{$i->precio1}}</summary>
                                <ul>
                                    <li>${{$i->precio2}}</li>
                                    <li>${{$i->precio3}}</li>
                                    
                                </ul>
                            </details>
                        </td>
                        @if ($masDatos)                            
                            <td>{{$i->costo}}</td>
                            <td>{{$i->iva}}</td>
                            <td>{{$i->rubro}}</td>
                            <td>{{$i->proveedor}}</td>
                            <td>{{$i->marca}}</td>
                            @if ($i->controlStock == 'si')
                                <td><button wire:click="cambiarModalStock('{{$i->codigo}}','{{$i->detalle}}')" ><i class="fa fa-plus" aria-hidden="true"></i></button></td>
                            @else                            
                                <td>{{$i->controlStock}}</td>
                            @endif
                            <td>{{$i->pesable}}</td>
                            <td>{{$i->imagen}}</td>
                            <td>{{$i->created_at}}</td>
                            <td>{{$i->updated_at}}</td>
                        @endif
                        </tr>
                    @endforeach

              </table>
          </div>
    </div>

    <div class="container">
        {{ $inventario->links('vendor.livewire.bootstrap') }}

    </div>


    <dialog {{$modal}}>
        <article>
          <header>
            <button aria-label="Close" rel="prev" wire:click="cambiarModal"></button>
            <p>
              <strong>Nuevo Articulo</strong>
            </p>
          </header>

          <form wire:submit="guardarArticulo">
            <fieldset>
              <label>
                Codigo
                    <input
                    name="codigo"
                    placeholder="Codigo"
                    autocomplete="codigo"
                    wire:model.blur="codigo"
                    @error('codigo') aria-invalid="true" @enderror
                    />
                    @error('codigo') 
                        <small id="invalid-helper">
                            {{ $message }} 
                        </small>                               
                    @enderror
              </label>
              <label>
                Detalle
                <input
                  type="text"
                  name="detalle"
                  placeholder="Detalle"
                  autocomplete="detalle"
                  wire:model.blur="detalle"
                  @error('detalle') aria-invalid="true" @enderror
                  />
                  @error('detalle') 
                      <small id="invalid-helper">
                          {{ $message }} 
                      </small>                               
                  @enderror
              </label>
            </fieldset>



                <div class="grid">

                    <div class="col">                    
                            <fieldset>
                                <label>
                                    IVA Incluido?
                                </label>
                                <input name="ivaIncluido" wire:model.live="ivaIncluido" wire:click="calcularPrecios" type="checkbox" role="switch" />
                            </fieldset>                   
                    </div>
                    <div class="col">
                        <label for="algo">Costo
                            <input type="text" wire:model.live="costo" wire:keydown="calcularPrecios" name="" id=""
                            @error('costo') aria-invalid="true" @enderror
                            />
                            @error('costo') 
                                <small id="invalid-helper">
                                    {{ $message }} 
                                </small>                               
                            @enderror
                        </label>
                    </div>
                    <div class="col">
                        <label for="algo">Iva
                            <input type="text" wire:model.blur="iva" wire:keydown="calcularPrecios" name="" id=""
                                @error('iva') aria-invalid="true" @enderror
                            >
                            @error('iva') 
                                <small id="invalid-helper">
                                    {{ $message }} 
                                </small>                               
                            @enderror
                        </label>
                    </div>
                </div>
                <div class="grid">
                    <div class="col">
                        <label for="">Lista
                            <select name="" wire:model.blur="porcentaje1" wire:change="calcularPrecios">
                                <option value="0">% 0</option>
                                @foreach ($listaPrecios as $precio)
                                    <option value="{{$precio->porcentaje}}">{{$precio->nombre}} ({{$precio->porcentaje}}%)</option>
                                @endforeach
                               </select>
                        </label>
                    </div>
                    <div class="col">
                        <label for="algo">Porcentaje
                            <input type="text" wire:model.blur="porcentaje1" wire:keydown="calcularPrecios" name="" id=""
                            @error('porcentaje1') aria-invalid="true" @enderror
                            />
                            @error('porcentaje1') 
                                <small id="invalid-helper">
                                    {{ $message }} 
                                </small>                               
                            @enderror
                        </label>
                    </div>
                </div>
                <div class="grid">
                    <div class="col">
                        <label for="">Precio 1
                            <input type="text" wire:model.live="precio1"
                            @error('precio1') aria-invalid="true" @enderror
                            />
                            @error('precio1') 
                                <small id="invalid-helper">
                                    {{ $message }} 
                                </small>                               
                            @enderror
                        </label>
                    </div>
                    <div class="col">
                        <label for="">Precio 2 ({{$empresa->precio2}}%)
                            <input type="text" wire:model.blur="precio2"
                            @error('precio2') aria-invalid="true" @enderror
                            />
                            @error('precio2') 
                                <small id="invalid-helper">
                                    {{ $message }} 
                                </small>                               
                            @enderror
                        </label>
                    </div>
                    <div class="col">
                        <label for="">Precio 3 ({{$empresa->precio3}}%)
                            <input type="text" wire:model.blur="precio3"
                            @error('precio3') aria-invalid="true" @enderror
                            />
                            @error('precio3') 
                                <small id="invalid-helper">
                                    {{ $message }} 
                                </small>                               
                            @enderror
                        </label>
                    </div>
                </div>


            <hr />

            <details >
                <summary>Mas:</summary>

                <div class="grid">
                    <div class="col">
                        <label for="">Rubro
                            <select name="" id="" wire:model="rubro">
                                <option value="General">General</option>
                                @foreach ($listaRubros as $rubro)
                                    <option value="{{$rubro->nombre}}">{{$rubro->nombre}}</option>                                
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="col">
                        <label for="">Proveedor
                            <select name="" id="" wire:model="proveedor">
                                <option value="General">General</option>
                                @foreach ($listaProveedores as $pro)
                                    <option value="{{$pro->nombre}}">{{$pro->nombre}}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="col">
                        <label for="">Marca
                            <select name="" id="" wire:model="marca">
                                <option value="General">General</option>
                                @foreach ($listaMarcas as $mar)
                                    <option value="{{$mar->nombre}}">{{$mar->nombre}}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
                <div class="grid">
                    <div class="col">
                        <label for="">Pesable
                            <select name="" id="" wire:model="pesable">
                                <option value="no" selected>No</option>
                                <option value="si">Si</option>
                            </select>
                        </label>
                    </div>
                    <div class="col">
                        <label for="">Control Stock?
                            <select name="" id="" wire:model="controlStock">
                                <option value="no" selected>No</option>
                                <option value="si">Si</option>
                            </select>
                        </label>
                    </div>
                    <div class="col">
                        <label for="">Imagen
                            <input type="text" wire:model="imagen">
                        </label>
                    </div>
                </div>

            </details>


            <input
              type="submit"
              value="Guardar"
            />
          </form>
          <button wire:click="cambiarModal" class="outline">Cancelar</button>

        </article>
    </dialog>

    <dialog x-bind:open="isOpenRubro">
        <article>
          <header>
            <button aria-label="Close" @click="isOpenRubro = false" rel="prev"></button>
            <p>
              <strong>Nuevo Rubro</strong>
            </p>
          </header>
            @if (session()->has('rubroGuardado'))          
                <p style="color: rgb(0, 137, 90);">
                    {{ session('rubroGuardado') }}
                </p>          
            @endif
          <form wire:submit="guardarRubro">
            <fieldset>
              <label>
                Rubro
                <input
                  wire:model.live="nuevoRubro"
                  name="rubro"
                  placeholder="Nombre Rubro"
                  autocomplete="rubro"
                  @error('nuevoRubro') aria-invalid="true" @enderror
                />
                    @error('nuevoRubro') 
                    <small id="invalid-helper">
                        {{ $message }} 
                      </small>
                    @enderror
                
              </label>

            </fieldset>
          
            <input
              type="submit"
              value="Guardar Rubro"
            />
          </form>
           <button  @click="isOpenRubro = false">Cancelar</button>
        </article>
    </dialog>

    <dialog x-bind:open="isOpenProveedor">
        <article>
          <header>
            <button aria-label="Close" @click="isOpenProveedor = false" rel="prev"></button>
            <p>
              <strong>Nuevo Proveedor</strong>
            </p>
          </header>
            @if (session()->has('proveedorGuardado'))          
                <p style="color: rgb(0, 137, 90);">
                    {{ session('proveedorGuardado') }}
                </p>          
            @endif
            <form wire:submit="guardarProveedor">
            <fieldset>
                <label>
                Rubro
                <input
                    wire:model.live="nuevoProveedor"
                    name="proveedor"
                    placeholder="Nombre Proveedor"
                    autocomplete="proveedor"
                    @error('nuevoProveedor') aria-invalid="true" @enderror
                />
                    @error('nuevoProveedor') 
                    <small id="invalid-helper">
                        {{ $message }} 
                        </small>
                    @enderror
                
                </label>

            </fieldset>
            
            <input
                type="submit"
                value="Guardar Proveedor"
            />
            </form>
            <button  @click="isOpenProveedor = false">Cancelar</button>
        </article>
    </dialog>

    <dialog {{$modalStock}}>
        <article>
          <header>
            <button aria-label="Close" wire:click="cambiarModalStock" rel="prev"></button>
            <p>
              <strong>Stock</strong>
            </p>
          </header>
            @if (session()->has('modificarStock'))          
                <p style="color: rgb(0, 137, 90);">
                    {{ session('modificarStock') }}
                </p>          
            @endif
            @error('codigo') 
                <small id="invalid-helper">
                    Codigo {{ $message }} 
                </small>                               
            @enderror
            @error('detalle') 
                <small id="invalid-helper">
                    Detalle {{ $message }} 
                </small>                               
            @enderror

            <form wire:submit="modificarStockArticulo">
                <p>({{$codigo}}) {{$detalle}}</p>
                
            <fieldset>

                <select wire:model="idDeposito" name="idDeposito" aria-label="">
                    @foreach ($depositos as $item)
                        <option value="{{$item->id}}">{{$item->nombre}}</option>                        
                    @endforeach
                  </select>


                <label>
                Stock
                <input
                    wire:model.live="nuevoStock"
                    name="nuevoStock"
                    placeholder="Stock"
                    autocomplete="nuevoStock"
                    @error('nuevoStock') aria-invalid="true" @enderror
                />
                    @error('nuevoStock') 
                    <small id="invalid-helper">
                        {{ $message }} 
                        </small>
                    @enderror
                
                </label>

            </fieldset>
            
            <input
                type="submit"
                value="Guardar Stock"
            />
            </form>
            <button wire:click="cambiarModalStock" >Cancelar</button>
        </article>
    </dialog>

    <dialog x-bind:open="isOpenMarca">
        <article>
          <header>
            <button aria-label="Close" @click="isOpenMarca = false" rel="prev"></button>
            <p>
              <strong>Nueva Marca</strong>
            </p>
          </header>
            @if (session()->has('marcaGuardar'))          
                <p style="color: rgb(0, 137, 90);">
                    {{ session('marcaGuardar') }}
                </p>          
            @endif
            <form wire:submit="guardarMarca">
            <fieldset>
                <label>
                Marca
                <input
                    wire:model.live="nuevaMarca"
                    name="marca"
                    placeholder="Nombre Marca"
                    autocomplete="marca"
                    @error('nuevaMarca') aria-invalid="true" @enderror
                />
                    @error('nuevaMarca') 
                    <small id="invalid-helper">
                        {{ $message }} 
                        </small>
                    @enderror
                
                </label>

            </fieldset>
            
            <input
                type="submit"
                value="Guardar Marca"
            />
            </form>
            <button  @click="isOpenMarca = false">Cancelar</button>
        </article>
    </dialog>


    <dialog x-bind:open="isOpenLista">
        <article>
          <header>
            <button aria-label="Close" @click="isOpenLista = false" rel="prev"></button>
            <p>
              <strong>Nueva Lista</strong>
            </p>
          </header>
            @if (session()->has('mensajeLista'))          
                <p style="color: rgb(0, 137, 90);">
                    {{ session('mensajeLista') }}
                </p>          
            @endif
            <form wire:submit="guardarLista">
            <fieldset>
                <label>
                Nombre Lista
                <input
                    wire:model.live="nuevaLista"
                    name=""
                    placeholder="Nombre Lista"
                    
                    @error('nuevaLista') aria-invalid="true" @enderror
                />
                    @error('nuevaLista') 
                    <small id="invalid-helper">
                        {{ $message }} 
                        </small>
                    @enderror
                
                </label>

                <label>
                    Porcentaje Lista %
                    <input
                        wire:model.live="porcentajeLista"
                        name=""
                        placeholder="%"
                        
                        @error('porcentajeLista') aria-invalid="true" @enderror
                    />
                        @error('porcentajeLista') 
                        <small id="invalid-helper">
                            {{ $message }} 
                            </small>
                        @enderror
                    
                    </label>

            </fieldset>
            
            <input
                type="submit"
                value="Guardar Lista"
            />
            </form>
            <button  @click="isOpenLista = false">Cancelar</button>
        </article>
    </dialog>


</div>
