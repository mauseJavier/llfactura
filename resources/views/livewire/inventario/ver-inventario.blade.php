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
                                    <li>
                                        <a role="button" class="outline" wire:navigate href="{{route('codigoBarra')}}">Codigo Barra</a>
                                    </li>
                                    <li>
                                        <a role="button" class="outline" wire:click="exportarInventarioCsv" >ExportarCSV (Filtro)</a>
                                    </li>
                                    <li>
                                        <a role="button" class="outline" wire:click="exportarPLU">Exportar PLU</a>
                                    </li>
                                </ul>
                            </details>
                        </div>
                        <div class="col">
                            <button wire:click="cambiarModal" data-tooltip="Nuevo Articulo">+</button>
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



            <hr />

            {{-- @dump($listaRubros) --}}
            
            <details>
                <summary>Filtro: {{$nombreRubro == '' ? '' : '(Rubro: '.$nombreRubro.')'}}
                                     {{$nombreProveedor == '' ? '' : '(Proveedor: '.$nombreProveedor.')'}}
                                    {{$nombreMarca == '' ? '' : '(Marca: '.$nombreMarca.')'}} 
                                     {{$filtroModificado == '' ? '' : '(Fecha Modi.: '.$filtroModificado.')'}} 

                </summary>
                <div class="grid">
                    <div class="col">
                            <select name="favorite-cuisine" aria-label="Selecciones Rubro" wire:model.live="nombreRubro">
                                <option selected disabled value="">
                                    Selecione Rubro
                                </option>
                                <option value="">Todo</option>
                                @foreach ($listaRubros as $r)                                    
                                    <option value="{{$r->nombre}}">{{$r->nombre}}</option>
                                @endforeach

                            </select>

                            <select name="favorite-cuisine" aria-label="Selecciones Proveedor" wire:model.live="nombreProveedor">
                                <option selected disabled value="">
                                    Selecione Proveedor
                                </option>
                                <option value="">Todo</option>
                                @foreach ($listaProveedores as $p)                                    
                                    <option value="{{$p->nombre}}">{{$p->nombre}}</option>
                                @endforeach
                            </select>

                            <select name="favorite-cuisine" aria-label="Selecciones Marca" wire:model.live="nombreMarca">
                                <option selected disabled value="">
                                    Selecione Marca
                                </option>
                                <option value="">Todo</option>
                                @foreach ($listaMarcas as $m)                                    
                                    <option value="{{$m->nombre}}">{{$m->nombre}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Fecha de Modificacion:</legend>
                            <label>
                                <input type="radio" name="language" wire:click="modFechaModificacion('')" {{$filtroModificado == '' ? 'checked' : ''}}   />
                                Todo
                            </label>
                            <label>
                              <input type="radio" name="language" wire:click="modFechaModificacion('Hoy')" />
                              Hoy
                            </label>
                            <label>
                              <input type="radio" name="language" wire:click="modFechaModificacion('Esta Semana')" />
                              Esta Semana
                            </label>
                            <label>
                              <input type="radio" name="language" wire:click="modFechaModificacion('Este Mes')" />
                              Este Mes
                            </label>
                            <label>
                              <input type="radio" name="language" wire:click="modFechaModificacion('Mes Pasado')" />
                              Mes Pasado
                            </label>

                          </fieldset>
            
                    </div>
                </div>
            </details>



                
        </article>
     
        <div class="grid">
            <div class="col">
                <fieldset>
            
                    <label>
                      <input wire:model.live="masDatos" name="detalles" type="checkbox" role="switch" />
                      Ver mas Datos
                    </label>
        
                </fieldset>

            </div>
            <div class="col">
                <fieldset>

                    <p>Orden:  
                        @switch($ordenarPor)
                            @case('detalle')
                                Detalle
                                @break
                            @case('precio1')
                                Precio
                                @break
                            @case('costo')
                                Costo
                                @break
                            @case('rubro')
                                Rubro
                                @break
                            @case('proveedor')
                                Proveedor
                                @break
                            @case('marca')
                                Marca
                                @break
                            @case('created_at')
                                Creado
                                @break
                            @case('updatad_at')
                                Actualizado
                                @break
                        
                        @default
                            
                    @endswitch
                    
                    {{ $acendenteDecendente == 'DESC' ? 'Descendente' : 'Ascendente'}}</p>
        
                    
                </fieldset>

            </div>
        </div>


        @if (session('mensaje'))
            <div style="color: red;">
                {{ session('mensaje') }}
            </div>
        @endif


        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    {{-- <th scope="col">id</th> --}}
                    <th scope="col">Codigo</th>
                    <th scope="col"><a  data-tooltip="Ordenar Ascendente/Descendente" data-placement="bottom" wire:click="ordenarGrilla('detalle')">Detalle</a></th>
                    <th scope="col"><a  data-tooltip="Ordenar Precio 1 Ascendente/Descendente" data-placement="bottom" wire:click="ordenarGrilla('precio1')">Precio</a></th>
                    @if ($masDatos)                        
                        <th scope="col"><a  data-tooltip="Ordenar Ascendente/Descendente" data-placement="bottom" wire:click="ordenarGrilla('costo')">Costo</a></th>
                        <th scope="col">Iva</th>
                        <th scope="col"><a  data-tooltip="Ordenar Ascendente/Descendente" data-placement="bottom" wire:click="ordenarGrilla('rubro')">Rubro</a></th>
                        <th scope="col"><a  data-tooltip="Ordenar Ascendente/Descendente" data-placement="bottom" wire:click="ordenarGrilla('proveedor')">Proveedor</a></th>
                        <th scope="col"><a  data-tooltip="Ordenar Ascendente/Descendente" data-placement="bottom" wire:click="ordenarGrilla('marca')">Marca</a></th>
                        <th scope="col">Control Stock</th>
                        <th scope="col">Pesable</th>
                        <th scope="col">Imagen</th>
                        <th scope="col"><a  data-tooltip="Ordenar Ascendente/Descendente" data-placement="bottom" wire:click="ordenarGrilla('created_at')">Creado</a></th>
                        <th scope="col"><a  data-tooltip="Ordenar Ascendente/Descendente" data-placement="bottom" wire:click="ordenarGrilla('updated_at')">Actualizado</a></th>
                        
                        <th scope="col">Favorito</th>
                    @endif
                    
                    
                    <th scope="col">Eliminar</th>

                  </tr>
                </thead>
                <tbody>

                    @foreach ($inventario as $i)
                        
                        <tr>
                        {{-- <th scope="row">{{$i->id}}</th> --}}
                        <td> <button wire:click="editarId({{$i->id}})"> 

                            {{$i->codigo}}</button> 
                        </td>
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
                                <td><button wire:click="cambiarModalStock('{{$i->codigo}}','{{$i->detalle}}')" >
                                    <!-- plus icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                                        <path
                                        d="M 295.38461538461536 39.38461538461539 Q 295.38461538461536 22.153846153846153 284.3076923076923 11.076923076923077 L 284.3076923076923 11.076923076923077 L 284.3076923076923 11.076923076923077 Q 273.2307692307692 0 256 0 Q 238.76923076923077 0 227.69230769230768 11.076923076923077 Q 216.6153846153846 22.153846153846153 216.6153846153846 39.38461538461539 L 216.6153846153846 216.6153846153846 L 216.6153846153846 216.6153846153846 L 39.38461538461539 216.6153846153846 L 39.38461538461539 216.6153846153846 Q 22.153846153846153 216.6153846153846 11.076923076923077 227.69230769230768 Q 0 238.76923076923077 0 256 Q 0 273.2307692307692 11.076923076923077 284.3076923076923 Q 22.153846153846153 295.38461538461536 39.38461538461539 295.38461538461536 L 216.6153846153846 295.38461538461536 L 216.6153846153846 295.38461538461536 L 216.6153846153846 472.61538461538464 L 216.6153846153846 472.61538461538464 Q 216.6153846153846 489.84615384615387 227.69230769230768 500.9230769230769 Q 238.76923076923077 512 256 512 Q 273.2307692307692 512 284.3076923076923 500.9230769230769 Q 295.38461538461536 489.84615384615387 295.38461538461536 472.61538461538464 L 295.38461538461536 295.38461538461536 L 295.38461538461536 295.38461538461536 L 472.61538461538464 295.38461538461536 L 472.61538461538464 295.38461538461536 Q 489.84615384615387 295.38461538461536 500.9230769230769 284.3076923076923 Q 512 273.2307692307692 512 256 Q 512 238.76923076923077 500.9230769230769 227.69230769230768 Q 489.84615384615387 216.6153846153846 472.61538461538464 216.6153846153846 L 295.38461538461536 216.6153846153846 L 295.38461538461536 216.6153846153846 L 295.38461538461536 39.38461538461539 L 295.38461538461536 39.38461538461539 Z"
                                        />
                                    </svg>
                                </button>                               

                                </td>
                            @else                            
                                <td>{{$i->controlStock}}</td>
                            @endif
                            <td>{{$i->pesable}}</td>
                            <td>{{$i->imagen}}</td>
                            <td>{{$i->created_at}}</td>
                            <td>{{$i->updated_at}}</td>

                            <td>    
                                <input name="opt-in" type="checkbox" role="switch" {{ $i->favorito ? 'checked':''}} 
                                        wire:click="cambioFavorito({{$i->id}},{{$i->favorito}})"
                                />
                                
                            </td>

                        @endif
                            <td style="text-align: center;color: red; "  title="Eliminar">
                                <!-- trash icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="2em" fill="currentColor" viewBox="0 0 512 512"
                                
                                    wire:click="eliminarInventario({{$i->id}})" 
                                    wire:confirm="Esta seguro de ELIMINAR este articulo?" 
                                    class="fa-solid fa-trash fa-xl" 
                                    style="cursor: pointer;" 
                                
                                >
                                    <path
                                    d="M 210 16 L 302 16 L 210 16 L 302 16 Q 315 16 323 27 L 346 64 L 346 64 L 166 64 L 166 64 L 189 27 L 189 27 Q 197 16 210 16 L 210 16 Z M 176 19 L 148 64 L 176 19 L 148 64 L 40 64 L 40 64 Q 33 65 32 72 Q 33 79 40 80 L 472 80 L 472 80 Q 479 79 480 72 Q 479 65 472 64 L 364 64 L 364 64 L 336 19 L 336 19 Q 324 1 302 0 L 210 0 L 210 0 Q 188 1 176 19 L 176 19 Z M 80 119 Q 79 112 71 112 Q 64 113 64 121 L 92 461 L 92 461 Q 95 483 110 497 Q 126 512 148 512 L 364 512 L 364 512 Q 386 512 402 497 Q 417 483 420 461 L 448 121 L 448 121 Q 448 113 441 112 Q 433 112 432 119 L 404 459 L 404 459 Q 402 475 391 485 Q 380 496 364 496 L 148 496 L 148 496 Q 132 496 121 485 Q 110 475 108 459 L 80 119 L 80 119 Z"
                                    />
                                </svg>

                            </td>
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

                            <select name="iva" id="iva" wire:model.blur="iva" wire:change="calcularPrecios"                                
                                 @error('iva') aria-invalid="true" @enderror
                            >
                                <option value="21">21</option>
                                <option value="10.5">10.5</option>
                                <option value="27">27</option>


                            </select>
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
                        <label for="">P.2 ({{$empresa->precio2}}% de P1)
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
                        <label for="">P.3 ({{$empresa->precio3}}% de P1)
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

            <hr>

                {{-- NUEVO STOCK --}}
                <div class="grid">                   
    
                        <div class="col">
                            Depositos
                            <select wire:model="idDeposito" name="idDeposito" aria-label="">
                                @foreach ($depositos as $item)
                                    <option value="{{$item->id}}">{{$item->nombre}}</option>                        
                                @endforeach
                            </select>
                        </div>
    
                        <div class="col">
    
                            <label>
                            Stock Inicial (0=Sin Control)
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
                        </div>
    
    
    
                   
                </div>

            <hr />

            <details >
                <summary>Mas:</summary>


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

    <dialog {{$modalEditar}}>
        <article>
          <header>
            <button aria-label="Close" rel="prev" wire:click="cambiarModalEditar"></button>
            <p>
              <strong>Editar Articulo</strong>
            </p>
          </header>

          <form wire:submit="editarArticulo">
            <fieldset>
              <label>
                Codigo (ID {{$idArticulo}})
                    <input
                    name="codigo"
                    placeholder="Codigo"
                    autocomplete="codigo"
                    wire:model.blur="codigo"
                    wire:blur="buscarCodigoDuplicado()"
                    @error('codigo') aria-invalid="true" @enderror
                    />
                    @error('codigo') 
                        <small id="invalid-helper">
                            {{ $message }} 
                        </small>                               
                    @enderror

                    @if (session('codigoDuplicado'))
                        <p style="color: red;">
                            {{ session('codigoDuplicado') }}
                        </p>    
                    @endif
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
                        <label for="">P.2 ({{$empresa->precio2}}% de P1)
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
                        <label for="">P.3 ({{$empresa->precio3}}% de P1)
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

            <hr>

            <label for="">
                Control de Stock?
                <select name="controlStock" id="controlStock" 
                    wire:model.live="controlStock">
                    <option value="si">Activado</option>
                    <option value="no">Desactivado</option>
                </select>
            </label>

            <details >
                <summary>Mas:</summary>


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
          <button wire:click="cambiarModalEditar" class="outline">Cancelar</button>

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


              <div x-data="{
                        
                    nuevoRubro: @entangle('nuevoRubro'),
                    rubros: @js($listaRubros->pluck('nombre')),
                    filteredTipos: [],
                    showSuggestions: false,
                    get filteredTipos() {
                        return this.rubros.filter(nuevoRubro => nuevoRubro.toLowerCase().includes(this.nuevoRubro.toLowerCase()));
                    }
                }">
                    <label for="">
                        Rubros
                
                        <input type="text" name="nuevoRubro" x-model="nuevoRubro" @input="showSuggestions = true" @blur="setTimeout(() => showSuggestions = false, 100)" 
                            placeholder="Nombre Rubro"
                            autocomplete="rubro"
                            @error('nuevoRubro') aria-invalid="true" @enderror
                        />
                            @error('nuevoRubro') 
                            <small id="invalid-helper">
                                {{ $message }} 
                                </small>
                            @enderror
                
                        <ul x-show="showSuggestions && nuevoRubro.length > 0">
                            <template x-for="suggestion in filteredTipos" :key="suggestion">
                                <li @click="nuevoRubro = suggestion; showSuggestions = false">
                                    <span style="color: red;" x-text="suggestion"></span>
                                </li>
                            </template>
                        </ul>
                    </label>
                </div>

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


                <div x-data="{
                            
                    nuevoProveedor: @entangle('nuevoProveedor'),
                    proveedores: @js($listaProveedores->pluck('nombre')),
                    filteredTipos: [],
                    showSuggestions: false,
                    get filteredTipos() {
                        return this.proveedores.filter(nuevoProveedor => nuevoProveedor.toLowerCase().includes(this.nuevoProveedor.toLowerCase()));
                    }
                }">
                    <label for="">
                        Proveedor
                
                        <input type="text" name="nuevoProveedor" x-model="nuevoProveedor" @input="showSuggestions = true" @blur="setTimeout(() => showSuggestions = false, 100)" 
                            placeholder="Nombre Proveedor"
                            autocomplete="proveedor"
                            @error('nuevoProveedor') aria-invalid="true" @enderror
                        />
                            @error('nuevoProveedor') 
                            <small id="invalid-helper">
                                {{ $message }} 
                                </small>
                            @enderror
                
                        <ul x-show="showSuggestions && nuevoProveedor.length > 0">
                            <template x-for="suggestion in filteredTipos" :key="suggestion">
                                <li @click="nuevoProveedor = suggestion; showSuggestions = false">
                                    <span style="color: red;" x-text="suggestion"></span>
                                </li>
                            </template>
                        </ul>
                    </label>
                </div>
                

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


                <div x-data="{
                            
                    nuevaMarca: @entangle('nuevaMarca'),
                    marcas: @js($listaMarcas->pluck('nombre')),
                    filteredTipos: [],
                    showSuggestions: false,
                    get filteredTipos() {
                        return this.marcas.filter(nuevaMarca => nuevaMarca.toLowerCase().includes(this.nuevaMarca.toLowerCase()));
                    }
                }">
                    <label for="">
                        Marca
                
                        <input type="text" name="nuevaMarca" x-model="nuevaMarca" @input="showSuggestions = true" @blur="setTimeout(() => showSuggestions = false, 100)" 
                            placeholder="Nombre Marca"
                            autocomplete="marca"
                            @error('nuevaMarca') aria-invalid="true" @enderror
                        />
                            @error('nuevaMarca') 
                            <small id="invalid-helper">
                                {{ $message }} 
                                </small>
                            @enderror
                
                        <ul x-show="showSuggestions && nuevaMarca.length > 0">
                            <template x-for="suggestion in filteredTipos" :key="suggestion">
                                <li @click="nuevaMarca = suggestion; showSuggestions = false">
                                    <span style="color: red;" x-text="suggestion"></span>
                                </li>
                            </template>
                        </ul>
                    </label>
                </div>

                

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
                {{-- <label>
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
                
                </label> --}}

                <div x-data="{
                            
                    nuevaLista: @entangle('nuevaLista'),
                    listas: @js($listaPrecios->pluck('nombre')),
                    filteredTipos: [],
                    showSuggestions: false,
                    get filteredTipos() {
                        return this.listas.filter(nuevaLista => nuevaLista.toLowerCase().includes(this.nuevaLista.toLowerCase()));
                    }
                }">
                    <label for="">
                        Nombre Lista
                
                        <input type="text" name="nuevaLista" x-model="nuevaLista" @input="showSuggestions = true" @blur="setTimeout(() => showSuggestions = false, 100)" 
                            placeholder="Nombre Lista"
                            autocomplete="lista"
                            @error('nuevaLista') aria-invalid="true" @enderror
                        />
                            @error('nuevaLista') 
                            <small id="invalid-helper">
                                {{ $message }} 
                                </small>
                            @enderror
                
                        <ul x-show="showSuggestions && nuevaLista.length > 0">
                            <template x-for="suggestion in filteredTipos" :key="suggestion">
                                <li @click="nuevaLista = suggestion; showSuggestions = false">
                                    <span style="color: red;" x-text="suggestion"></span>
                                </li>
                            </template>
                        </ul>
                    </label>
                </div>
                

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
