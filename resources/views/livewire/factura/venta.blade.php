<div>
    {{-- The whole world belongs to you. --}}

<div class="container">

  @if ($carrito)
    <article style="text-align: center; justify-content: center;">

      <h1 style="font-size: 50px;">$ {{$carrito['total']}}</h1>
      <small>Articulos: {{$carrito['articulos']}}</small>
      
  </article>      
  @endif



  @if ($carrito)
  <nav>
    <ul>
      <li>      
          <fieldset role="group">
            <input type="number" step=".5" wire:model="porcentaje" style="text-align: right;">
            <button wire:click="aplicarPorcentaje">%</button>
          </fieldset>        
          @if (session('mensaje'))
              <div class="alert alert-success">
                  {{ session('mensaje') }}
              </div>
          @endif
      </li>
      <li>
        <button wire:click="borrarCarrito"  class="outline">Borrar</button>
      </li>

      
      <a wire:navigate href="{{route('nuevoComprobante')}}" role="button" class="outline contrast">Finalizar</a>
      
    </ul>
    <ul>
      <li>
        <label>
          <input name="terms" type="checkbox" role="switch" wire:click="cambiar" />
          VerCarrito
        </label>
        
      </li>
    </ul>
  </nav>
  @endif



</div>


    <div class="grid">
      
      @if ($carrito)
      <article  class="{{$esconderCelular}}" style="background-color: rgb(48, 53, 50);">

        <div>
            <h3>Articulos Cargados</h3>
            <div class="overflow-auto">
                <table>
                    <thead>
                      <tr>
                        
                        <th scope="col"  style="text-align: center;">Borrar-Detalle</th>
                        <th scope="col">Precio</th>
                        
                        <th scope="col">Cant</th>
                        <th scope="col">Sub Total</th>

                      </tr>
                    </thead>
                    <tbody>

                        @foreach ($carrito['carrito'] as $key => $articulo)
                          <tr>                            
                            
                              <th scope="row">

                                <div role="group">
                                  <button 
                                    class="outline secondary"
                                    wire:click="borrarArticulo({{$key}})" 
                                    data-tooltip="Eliminar"
                                  >
                                    <i class="fa-solid fa-trash"></i>
                                    {{$articulo['codigo']}}
                                  </button>
                                  <button class="outline contrast" wire:click="abrirModal({{$key}})" data-tooltip="Editar">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    {{$articulo['detalle']}}
                                  </button>
                                </div>

                              </th>  
                              <th scope="row">${{$articulo['precio']}}</th>  
                              <th scope="row">{{$articulo['cantidad']}}</th>                            

                              {{-- <td><input class="seleccionarTodo" style="text-align: right;" type="text" value="{{$articulo['precio']}}" name="precio" style="min-width: 300px;" ></td>
                              
                              <td><input class="seleccionarTodo" style="text-align: right;" type="text" value="{{$articulo['cantidad']}}" name="cantidad" style="min-width: 300px;" ></td> --}}
                                                       
                              <td style="text-align: right;">${{$articulo['subtotal']}}</td>
                              
                          </tr>                          
                        @endforeach
                          
                    </tbody>
                    <tfoot>
                      <tr>

                      </tr>
                    </tfoot>
                </table>
              </div>

        </div>

      </article>


          
      @endif

        

        <div class="{{$mostrarCelular}}">
            
 
          
          <form role="search"  wire:submit="buscarCargar">        
            <input style="text-align: center; width: 30%;" class="seleccionarTodo" 
              wire:model.live="cantidad"
              wire:keydown.down="restarCantidad"
              wire:keydown.up="sumarCantidad"
              type="text"
            
            >
            <input wire:model.live="datoBuscado" name="search" type="search" placeholder="Buscar en Inventario   ⬆️ + ⬇️ - Cantidad " class="seleccionarTodo"
                wire:keydown.down="restarCantidad"
                wire:keydown.up="sumarCantidad"
                autocomplete="off"
             />
            <input type="submit" value="Buscar" />
          </form>
          @error('cantidad') 
            <small id="invalid-helper">
              {{ $message }} 
            </small>
          @enderror
              

              <div class="overflow-auto">
                <table>
                    <thead>
                      <tr>
                        <th scope="col">Codigo</th>
                        <th scope="col">Detalle</th>
                        <th scope="col" style="text-align: right;">
                                  <!-- Select -->
                          <select name="select" aria-label="Select" required wire:model.live="seleccionPrecio">
                            <option selected value="precio1">Precio 1</option>
                            <option value="precio2">Precio 2</option>
                            <option value="precio3">Precio 3</option>

                          </select>
                        </th>
                       
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($inventario as $i)
                        <tr>
                          <th scope="row"><button wire:click="cargar({{$i->id}})">{{$i->codigo}}</button> </th>
                          <td>{{$i->detalle}}</td>
                          <td style="text-align: right;">${{$i->precio}}</td>
        
                        </tr>
                      @endforeach

                    </tbody>
                    <tfoot>
                      <tr>

                      </tr>
                    </tfoot>
                </table>
              </div>

              {{ $inventario->links('vendor.livewire.bootstrap') }}

        </div>

    </div>


    {{-- // ESTE ES EL MODAL  --}}
    <dialog {{$estadoModal}} >
      <article >
        <h3>Articulos Cargados</h3>

          <fieldset>
            <label>
              Detalle
              <input type="text" wire:model="modificarDetalle" wire:keydown.enter="modificarCarrito">
            </label>
            <label>
              Precio
              <input type="text" wire:model="modificarPrecio" wire:keydown.enter="modificarCarrito">
            </label>
            <label>
              Cantidad
              <input type="text" wire:model="modificarCantidad" wire:keydown.enter="modificarCarrito">
            </label>
          </fieldset>

        <footer>
          <button className="secondary"
            wire:click="cerrarModal">
            Cancelar
          </button>
          <button
            wire:click="modificarCarrito">Modificar</button>
        </footer>
      </article>
    </dialog>

</div>




