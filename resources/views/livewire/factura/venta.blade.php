<div>
    {{-- The whole world belongs to you. --}}
    <style>
      .bg-default {
          /* background-color: lightgray; */
      }
      .bg-focus {
          background-color: red;
      }
  </style>


      <div class="container" style="margin-top: -2%;">

        <div style="justify-content: center; align-items: center; ">

        
        </div>
        
          <br>



      </div>


    <div class="grid">
      
      @if ($carrito)
        <article  style="background-color: rgb(48, 53, 50);">

          <div style=" 
                        height: {{$tamañoGrillaVenta}}px; /* Fija la altura deseada */
                        overflow: auto; /* Permite el desplazamiento */">


              <div class="overflow-auto" >
                  <table style="font-size: 15px;" style="height: 10px;"  id="tablaCarrito">
                      <thead>
                        <tr>
                          
                          <th scope="col"  style="text-align: center;">Codigo-Borrar</th>
                          <th scope="col"  style="text-align: center;">Detalle-Editar</th>

                          <th scope="col">Precio</th>
                          
                          <th scope="col">Cant</th>
                          <th scope="col">Sub Total</th>

                        </tr>
                      </thead>
                      <tbody>


                          @foreach ($carrito['carrito'] as $key => $articulo)
                            <tr>                            
                              
                                <th scope="row">

                                  <p 
                                    wire:click="borrarArticulo({{$key}})" 
                                    style="cursor: pointer; font-size: 15px;"
                                  >
                                  
                                    <i class="fa-solid fa-trash"></i>
                                    {{$articulo['codigo']}}
                                  </p>


                                </th>  

                                <th >

                                  <p
                                    wire:click="abrirModal({{$key}})" 
                                   
                                    style="cursor: pointer; font-size: 15px;"
                                    >
                                      <i class="fa-regular fa-pen-to-square"></i>
                                      {{$articulo['detalle']}}
                                  </p>
                                </th>
                                <th scope="row" >${{$articulo['precio']}}</th>  
                                <th scope="row" >{{$articulo['cantidad']}}</th>                            

                                {{-- <td><input class="seleccionarTodo" style="text-align: right;" type="text" value="{{$articulo['precio']}}" name="precio" style="min-width: 300px;" ></td>
                                
                                <td><input class="seleccionarTodo" style="text-align: right;" type="text" value="{{$articulo['cantidad']}}" name="cantidad" style="min-width: 300px;" ></td>  --}}
                                                        
                                <td style="text-align: right;"><strong>${{$articulo['subtotal']}}</strong></td>
                                
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


    </div>

    <div class="grid">

      <div >
            
 
        <div class="grid">

          @if ($carrito)



            <div class="div">

              <fieldset role="group">

                <a wire:navigate href="{{route('nuevoComprobante')}}" role="button" data-tooltip="Finalizar Venta"  >Finalizar</a>
                <a  wire:click="borrarCarrito" role="button" data-tooltip="Borrar todo"  class="secondary">Cancelar</a>
                {{-- <button wire:click="borrarCarrito"  class="contrast" data-tooltip="Borrar todo" style="font-size: 15px;">Cancelar</button> --}}

                <input type="number" step=".5" wire:model="porcentaje" style="text-align: right; font-size: 15px; text-align: center;">
                <button wire:click="aplicarPorcentaje" data-tooltip="Aplicar % a Toda la Venta" >%</button>
              </fieldset>        
              @if (session('mensaje'))
                <div class="alert alert-success">
              {{ session('mensaje') }}
              </div>
              @endif

            </div>



              
          @endif




          <div class="div">
            <form role="search"  wire:submit="buscarCargar" >        
              <input style="text-align: center; width: 20%;" class="seleccionarTodo" 
                wire:model.live="cantidad"
                wire:keydown.down="restarCantidad"
                wire:keydown.up="sumarCantidad"
                type="text"
                style="font-size: 15px;"
              
              >
              <input wire:model.live="datoBuscado" name="search" type="search" placeholder="Buscar en Inventario" class="seleccionarTodo"
                  wire:keydown.down="restarCantidad"
                  wire:keydown.up="sumarCantidad"
                  autocomplete="off"
                  style="font-size: 15px;"
                  autofocus
               />
              
              <button type="submit" ><i class="fa-solid fa-magnifying-glass" style="font-size: 15px;"></i></button>
            </form>

          </div>

        </div>




        @error('porcentaje') 
          <small id="invalid-helper">
            {{ $message }} 
          </small>
        @enderror

        

        @error('cantidad') 
          <small id="invalid-helper">
            {{ $message }} 
          </small>
        @enderror
            

            <div class="overflow-auto"
            style=" 
                        height: 800px; /* Fija la altura deseada */
                        overflow: auto; /* Permite el desplazamiento */"
            >
              <table style="font-size: 15px;" >
                  <thead>
                    <tr>
                      <th scope="col">Codigo</th>
                      <th scope="col">Detalle</th>
                      <th scope="col" style="text-align: right;">
                                <!-- Select -->
                        <select name="select" aria-label="Select" required wire:model.live="seleccionPrecio" style="font-size: 15px; ">
                          <option selected value="precio1">Precio 1</option>
                          <option value="precio2">Precio 2</option>
                          <option value="precio3">Precio 3</option>

                        </select>
                      </th>
                     
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($inventario as $i)
                      <tr x-data="{ isFocused: false }">
                        <th scope="row">
                            <button 
                              :class="{ 'bg-default': !isFocused, 'bg-focus': isFocused }" 
                              @focus="isFocused = true" 
                              @blur="isFocused = false"
                              class="bg-default"
                              
                              wire:click="cargar({{$i->id}})" style="font-size: 15px;">{{$i->codigo}}
                            </button> 
                        </th>
                        <td >{{$i->detalle}}</td>
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

            {{-- {{ $inventario->links('vendor.livewire.bootstrap') }} --}}

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

          <hr>

          <fieldset>
            <legend>Modificar Precios Inventario:</legend>
            <label>
              <input type="checkbox" wire:model="checkPrecio1" />
              Precio 1
            </label>
            <label>
              <input type="checkbox" wire:model="checkPrecio2"  />
              Precio 2
            </label>
            <label>
              <input type="checkbox" wire:model="checkPrecio3" />
              Precio 3
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




