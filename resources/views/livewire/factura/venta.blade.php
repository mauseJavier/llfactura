<div x-data="{ focusInput() { this.$nextTick(() => { this.$refs.inputField.focus(); }); } }">

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
        @if (session()->has('mensaje'))
            <div class="alert alert-success" role="alert">
                {{ session('mensaje') }}
            </div>
        @endif

        
        </div>
        
          <br>



      </div>

      {{-- @dump($carrito) --}}

    <div class="grid">
      
      @if ($carrito)
        <article  style="background-color: rgb(48, 53, 50);">

          <div style=" 
                        height: {{$tamañoGrillaVenta}}px; /* Fija la altura deseada */
                        overflow: auto; /* Permite el desplazamiento */"
               wire:key="grilla-{{$tamañoGrillaVenta}}">


              <div class="overflow-auto" >
                  <table style="font-size: 20px; height: 10px;"  id="tablaCarrito">
                      {{-- <thead>
                        <tr>
                          
                          <th scope="col"  style="text-align: center;">Codigo-Borrar</th>
                          <th scope="col"  style="text-align: center;">Detalle-Editar</th>

                          <th scope="col">Precio</th>
                          
                          <th scope="col">Cant</th>
                          <th scope="col">Sub Total</th>

                        </tr>
                      </thead> --}}
                      <tbody>


                          @foreach ($carrito['carrito'] as $key => $articulo)
                            <tr>               
                              
                              <th scope="row" >{{$articulo['cantidad']}}</th>   
                              
                                <th scope="row">

                                  <p  wire:click="borrarArticulo({{$key}})" 
                                      style="display: inline-block;cursor: pointer; font-size: 15px;"
                                  >

                                    <!-- trash icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                                      <path
                                        d="M 210 16 L 302 16 L 210 16 L 302 16 Q 315 16 323 27 L 346 64 L 346 64 L 166 64 L 166 64 L 189 27 L 189 27 Q 197 16 210 16 L 210 16 Z M 176 19 L 148 64 L 176 19 L 148 64 L 40 64 L 40 64 Q 33 65 32 72 Q 33 79 40 80 L 472 80 L 472 80 Q 479 79 480 72 Q 479 65 472 64 L 364 64 L 364 64 L 336 19 L 336 19 Q 324 1 302 0 L 210 0 L 210 0 Q 188 1 176 19 L 176 19 Z M 80 119 Q 79 112 71 112 Q 64 113 64 121 L 92 461 L 92 461 Q 95 483 110 497 Q 126 512 148 512 L 364 512 L 364 512 Q 386 512 402 497 Q 417 483 420 461 L 448 121 L 448 121 Q 448 113 441 112 Q 433 112 432 119 L 404 459 L 404 459 Q 402 475 391 485 Q 380 496 364 496 L 148 496 L 148 496 Q 132 496 121 485 Q 110 475 108 459 L 80 119 L 80 119 Z"
                                      />
                                    </svg>

                                  </p>
                                  <p 
                                    wire:click="borrarArticulo({{$key}})" 
                                    style="display: inline-block;cursor: pointer; font-size: 15px;"
                                  >
                                  
                                    {{$articulo['codigo']}}
                                  </p>


                                </th>  

                                <th >

                                  <p  wire:click="abrirModal({{$key}})"                                    
                                      style="display: inline-block;cursor: pointer; font-size: 20px;"
                                  >

                                  <!-- pen icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                  <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                                    <path
                                      d="M 396 25 Q 406 16 419 16 L 419 16 L 419 16 Q 431 16 441 25 L 487 71 L 487 71 Q 496 81 496 93 Q 496 106 487 116 L 420 183 L 420 183 L 329 92 L 329 92 L 396 25 L 396 25 Z M 318 103 L 409 194 L 318 103 L 409 194 L 166 437 L 166 437 Q 150 453 128 460 L 21 491 L 21 491 L 52 384 L 52 384 Q 59 362 75 346 L 318 103 L 318 103 Z M 453 14 Q 438 0 419 0 L 419 0 L 419 0 Q 399 0 385 14 L 63 335 L 63 335 Q 45 354 37 379 L 1 501 L 1 501 Q 0 505 3 509 Q 7 512 11 511 L 133 475 L 133 475 Q 158 467 177 449 L 498 127 L 498 127 Q 512 113 512 93 Q 512 74 498 59 L 453 14 L 453 14 Z"
                                    />
                                  </svg>
                                </p>
                                  <p
                                    wire:click="abrirModal({{$key}})"                                    
                                    style="display: inline-block;cursor: pointer; font-size: 20px;"
                                    >
                                      {{$articulo['detalle']}} {{$articulo['porcentaje'] < 0 ? '('.$articulo['porcentaje'].'%)' : ''}}
                                  </p>
                                </th>
                                <th scope="row" >${{$articulo['precio']}}</th>  
                                                          

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

    <div class="grid"  >

      <div >
            
 
        <div class="grid" >

          @if ($carrito)



            <div class="div" style="transform: scale(0.8, 0.8); margin-bottom: -10px;">

              <fieldset role="group">

                <a wire:navigate href="{{route('nuevoComprobante')}}" role="button" data-tooltip="Finalizar Venta"  >Finalizar</a>
                <a  wire:click="borrarCarrito" role="button" data-tooltip="Borrar todo"  class="bg-focus">Cancelar</a>
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




          <div class="div" style="transform: scale(0.9, 0.9);margin-bottom: -10px;">
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

                          <option value="precio1">Precio 1</option>
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
                            @click="focusInput()"
                              :class="{ 'bg-default': !isFocused, 'bg-focus': isFocused }" 
                              @focus="isFocused = true" 
                              @blur="isFocused = false"
                              class="bg-default"
                              wire:keydown.prevent.down="restarCantidad"
                              wire:keydown.prevent.up="sumarCantidad"
                              wire:click="cargar({{$i->id}})" style="font-size: 15px;"
                              >
                                {{$i->codigo}}                              
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
              <input type="text" wire:model.live="modificarPrecio" wire:keydown.enter="modificarCarrito">
            </label>
            <label>
              Cantidad
              <input type="text" wire:model="modificarCantidad" wire:keydown.enter="modificarCarrito">
            </label>
            <label>
              Porcentaje (-Descuento +Incremento)
              <fieldset role="group">
                <input type="text" wire:model="modificarPorcentaje" wire:keydown.enter="modificarCarrito">
                <input type="button" value="Quitar %" wire:click="quitarPorcentaje()">

              </fieldset>
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




