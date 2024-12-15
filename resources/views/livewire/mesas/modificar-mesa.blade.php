<div>

    <div class="container">

        <article>
            <div class="grid">
                <div class="col">
                    <h1>
                        ({{$mesa->numero}})  {{$mesa->nombre}}        
                        (${{number_format($total, 2, ',', '.')}} - {{$razonSocial}})
                    </h1>

                </div>
                
                
                <div class="col">
                    @if ($data)

                        <button wire:click="finalizarMesa" wire:confirm="Finalizar Mesa??">Finalizar Mesa</button>
                        <button wire:click="cancelarMesa" wire:confirm="Cancelar Mesa??" style="background-color: rgb(145, 62, 62);">Cancelar Mesa</button>
                    @endif
                </div>
            </div>

        </article>
        
        
        <article x-data="{ abierto: false }">
              
            <label>
                <input name="terms" type="checkbox" role="switch" @click="abierto = !abierto" x-bind:checked="abierto" />

                    <!-- id-card-clip icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                        <path
                            d="M 227.55555555555554 28.444444444444443 L 284.44444444444446 28.444444444444443 L 227.55555555555554 28.444444444444443 L 284.44444444444446 28.444444444444443 Q 296.8888888888889 28.444444444444443 304.8888888888889 36.44444444444444 Q 312.8888888888889 44.44444444444444 312.8888888888889 56.888888888888886 L 312.8888888888889 113.77777777777777 L 312.8888888888889 113.77777777777777 Q 312.8888888888889 126.22222222222223 304.8888888888889 134.22222222222223 Q 296.8888888888889 142.22222222222223 284.44444444444446 142.22222222222223 L 227.55555555555554 142.22222222222223 L 227.55555555555554 142.22222222222223 Q 215.11111111111111 142.22222222222223 207.11111111111111 134.22222222222223 Q 199.11111111111111 126.22222222222223 199.11111111111111 113.77777777777777 L 199.11111111111111 56.888888888888886 L 199.11111111111111 56.888888888888886 Q 199.11111111111111 44.44444444444444 207.11111111111111 36.44444444444444 Q 215.11111111111111 28.444444444444443 227.55555555555554 28.444444444444443 L 227.55555555555554 28.444444444444443 Z M 56.888888888888886 85.33333333333333 L 170.66666666666666 85.33333333333333 L 56.888888888888886 85.33333333333333 L 170.66666666666666 85.33333333333333 L 170.66666666666666 128 L 170.66666666666666 128 Q 171.55555555555554 145.77777777777777 183.11111111111111 158.22222222222223 Q 195.55555555555554 169.77777777777777 213.33333333333334 170.66666666666666 L 298.6666666666667 170.66666666666666 L 298.6666666666667 170.66666666666666 Q 316.44444444444446 169.77777777777777 328.8888888888889 158.22222222222223 Q 340.44444444444446 145.77777777777777 341.3333333333333 128 L 341.3333333333333 85.33333333333333 L 341.3333333333333 85.33333333333333 L 455.1111111111111 85.33333333333333 L 455.1111111111111 85.33333333333333 Q 479.1111111111111 86.22222222222223 495.1111111111111 102.22222222222223 Q 511.1111111111111 118.22222222222223 512 142.22222222222223 L 512 426.6666666666667 L 512 426.6666666666667 Q 511.1111111111111 450.6666666666667 495.1111111111111 466.6666666666667 Q 479.1111111111111 482.6666666666667 455.1111111111111 483.55555555555554 L 56.888888888888886 483.55555555555554 L 56.888888888888886 483.55555555555554 Q 32.888888888888886 482.6666666666667 16.88888888888889 466.6666666666667 Q 0.8888888888888888 450.6666666666667 0 426.6666666666667 L 0 142.22222222222223 L 0 142.22222222222223 Q 0.8888888888888888 118.22222222222223 16.88888888888889 102.22222222222223 Q 32.888888888888886 86.22222222222223 56.888888888888886 85.33333333333333 L 56.888888888888886 85.33333333333333 Z M 156.44444444444446 416.8888888888889 Q 157.33333333333334 425.77777777777777 166.22222222222223 426.6666666666667 L 345.77777777777777 426.6666666666667 L 345.77777777777777 426.6666666666667 Q 354.6666666666667 425.77777777777777 355.55555555555554 416.8888888888889 Q 354.6666666666667 397.3333333333333 341.3333333333333 384 Q 328 370.6666666666667 308.44444444444446 369.77777777777777 L 203.55555555555554 369.77777777777777 L 203.55555555555554 369.77777777777777 Q 184 370.6666666666667 170.66666666666666 384 Q 157.33333333333334 397.3333333333333 156.44444444444446 416.8888888888889 L 156.44444444444446 416.8888888888889 Z M 256 341.3333333333333 Q 288 340.44444444444446 304.8888888888889 312.8888888888889 Q 320.8888888888889 284.44444444444446 304.8888888888889 256 Q 288 228.44444444444446 256 227.55555555555554 Q 224 228.44444444444446 207.11111111111111 256 Q 191.11111111111111 284.44444444444446 207.11111111111111 312.8888888888889 Q 224 340.44444444444446 256 341.3333333333333 L 256 341.3333333333333 Z"
                        />
                        </svg>
                    Datos Mesa
            </label>
              <details x-bind:open="abierto">

                
                <summary style="color:red;">
                </summary>

                    <fieldset class="grid">
                        <label for="">
                            Cliente
                            <input 
                              name="razonSocial"
                              placeholder="Cliente"
                              wire:model.blur="razonSocial"
                              @error('razonSocial') aria-invalid="true" @enderror
                              />
                              @error('razonSocial') 
                                  <small id="invalid-helper">
                                      {{ $message }}  
                                  </small>
                              @enderror
                        </label>
                        <label for="">
                            Comentario
                            <textarea
                                name="comentario"
                                placeholder="Ingrese Comentario..."
                                rows="3"
                                wire:model="comentario"
                            ></textarea>
                        </label>
                    </fieldset>


                    <fieldset class="grid">
                        <label>
                            Tipo de documento
                            <select name="" wire:model="tipoDocumento">
                                <option selected value="99">Consumidor Final</option>
                                <option  value="80">CUIT</option>
                                <option  value="86">CUIL</option>
                                <option  value="96">DNI</option>
                            </select>
                        </label>
                        <label for="">
                            Numero Documento
                            <input 
                              name="numeroDocumento"
                              placeholder="Numero Documento"
                              wire:model.blur="numeroDocumento"
                              @error('numeroDocumento') aria-invalid="true" @enderror
                              />
                              @error('numeroDocumento') 
                                  <small id="invalid-helper">
                                      {{ $message }}  
                                  </small>
                              @enderror
                        </label>
                    </fieldset>

                    <fieldset class="grid">
                        <label for="">
                            Tipo Contribuyente
                            <select name="" aria-label=""  required wire:model="tipoContribuyente">       
    
                                    <option value="5">Consumidor Final</option>
                                    <option value="13">Monotributista</option>
                                    <option value="6">Responsable Inscripto</option>
                                    <option value="4">Exento</option>
                            
                            </select>
                            
                        </label>
                        <label for="">
                            Domicilio
                            <input 
                              name="domicilio"
                              placeholder="Domicilio"
                              wire:model="domicilio"
                            />
                        </label>

                        <label for="">
                            Correo
                            <input 
                              name="correo"
                              placeholder="Correo"
                              wire:model="correo"
                            />
                        </label>
                    </fieldset>

                    <button wire:click="guardarrazonSocial">Guardar Cliente</button>

              </details>

                @if ($data)

                    <hr>

                    <details>
                        <summary style="color:yellowgreen;">
      
                          <!-- bell-concierge icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                          <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                              <path
                              d="M 216 64 Q 194 66 192 88 Q 194 110 216 112 L 232 112 L 232 112 L 232 145 L 232 145 Q 146 156 90 218 Q 34 279 32 368 L 480 368 L 480 368 Q 478 279 422 218 Q 366 156 280 145 L 280 112 L 280 112 L 296 112 L 296 112 Q 318 110 320 88 Q 318 66 296 64 L 256 64 L 216 64 Z M 24 400 Q 2 402 0 424 Q 2 446 24 448 L 488 448 L 488 448 Q 510 446 512 424 Q 510 402 488 400 L 24 400 L 24 400 Z"
                              />
                          </svg>
      
                            Pedido      
                              @if ($data)
                                 - ${{$data['total']}}    
                              @endif
                        </summary>
      
      
                        <table class="striped">
                          <thead>
                            <tr>
                              <th scope="col">Cantidad</th>
                              <th scope="col">Detalle</th>
                              <th scope="col" style="text-align: right;">SubTotal</th>
      
      
                            </tr>
                          </thead>
                          <tbody>
      
                              @foreach ($data['mesaCarrito'] as $key => $articulo)
          
                              {{-- @dump($articulo) --}}
          
                                      <tr>               
                                                      
                                          <th scope="row" >{{$articulo['cantidad']}}</th>   
                                          
          
                                          <th >
                                  
                                                  {{$articulo['detalle']}} {{$articulo['porcentaje'] < 0 ? '('.$articulo['porcentaje'].'%)' : ''}}
                                              
                                          </th>
                  
                                          <td style="text-align: right;"><strong>${{$articulo['subtotal']}}</strong></td>
                                          
                                      </tr>  
                              @endforeach
      
      
                          </tbody>
      
                        </table>
      
                        
                        
                        
                      </details>


                    
                @endif

                

                @if ($mesaCarrito)

                <hr>

                <details open>
                    <summary>
                        <!-- square-plus icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                            <path
                            d="M 73.14285714285714 54.857142857142854 Q 56 56 54.857142857142854 73.14285714285714 L 54.857142857142854 438.85714285714283 L 54.857142857142854 438.85714285714283 Q 56 456 73.14285714285714 457.14285714285717 L 438.85714285714283 457.14285714285717 L 438.85714285714283 457.14285714285717 Q 456 456 457.14285714285717 438.85714285714283 L 457.14285714285717 73.14285714285714 L 457.14285714285717 73.14285714285714 Q 456 56 438.85714285714283 54.857142857142854 L 73.14285714285714 54.857142857142854 L 73.14285714285714 54.857142857142854 Z M 0 73.14285714285714 Q 1.1428571428571428 42.285714285714285 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 Q 42.285714285714285 1.1428571428571428 73.14285714285714 0 L 438.85714285714283 0 L 438.85714285714283 0 Q 469.7142857142857 1.1428571428571428 490.2857142857143 21.714285714285715 Q 510.85714285714283 42.285714285714285 512 73.14285714285714 L 512 438.85714285714283 L 512 438.85714285714283 Q 510.85714285714283 469.7142857142857 490.2857142857143 490.2857142857143 Q 469.7142857142857 510.85714285714283 438.85714285714283 512 L 73.14285714285714 512 L 73.14285714285714 512 Q 42.285714285714285 510.85714285714283 21.714285714285715 490.2857142857143 Q 1.1428571428571428 469.7142857142857 0 438.85714285714283 L 0 73.14285714285714 L 0 73.14285714285714 Z M 228.57142857142858 356.57142857142856 L 228.57142857142858 283.42857142857144 L 228.57142857142858 356.57142857142856 L 228.57142857142858 283.42857142857144 L 155.42857142857142 283.42857142857144 L 155.42857142857142 283.42857142857144 Q 130.28571428571428 281.14285714285717 128 256 Q 130.28571428571428 230.85714285714286 155.42857142857142 228.57142857142858 L 228.57142857142858 228.57142857142858 L 228.57142857142858 228.57142857142858 L 228.57142857142858 155.42857142857142 L 228.57142857142858 155.42857142857142 Q 230.85714285714286 130.28571428571428 256 128 Q 281.14285714285717 130.28571428571428 283.42857142857144 155.42857142857142 L 283.42857142857144 228.57142857142858 L 283.42857142857144 228.57142857142858 L 356.57142857142856 228.57142857142858 L 356.57142857142856 228.57142857142858 Q 381.7142857142857 230.85714285714286 384 256 Q 381.7142857142857 281.14285714285717 356.57142857142856 283.42857142857144 L 283.42857142857144 283.42857142857144 L 283.42857142857144 283.42857142857144 L 283.42857142857144 356.57142857142856 L 283.42857142857144 356.57142857142856 Q 281.14285714285717 381.7142857142857 256 384 Q 230.85714285714286 381.7142857142857 228.57142857142858 356.57142857142856 L 228.57142857142858 356.57142857142856 Z"
                            />
                        </svg>
                        Nuevo Pedido
                    </summary>
                    
                        <article  style="background-color: rgb(48, 53, 50);">
                
                                <div class="overflow-auto" >
                                    <table style="font-size: 20px; height: 10px;"  id="tablamesaCarrito">
    
                                        <tbody>
                
                
                                            @foreach ($mesaCarrito['mesaCarrito'] as $key => $articulo)
                                            <tr>               
                                                
                                                <th scope="row" >
                                                    {{$articulo['cantidad']}}
                                                </th>   
                                                
                
                                                <th>
                                                            
                                                    <p  wire:click="borrarArticulo({{$key}})" 
                                                        style="display: inline-block;cursor: pointer; font-size: 20px;"
                                                    >
                
                                                        <!-- trash icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                                                            <path
                                                            d="M 210 16 L 302 16 L 210 16 L 302 16 Q 315 16 323 27 L 346 64 L 346 64 L 166 64 L 166 64 L 189 27 L 189 27 Q 197 16 210 16 L 210 16 Z M 176 19 L 148 64 L 176 19 L 148 64 L 40 64 L 40 64 Q 33 65 32 72 Q 33 79 40 80 L 472 80 L 472 80 Q 479 79 480 72 Q 479 65 472 64 L 364 64 L 364 64 L 336 19 L 336 19 Q 324 1 302 0 L 210 0 L 210 0 Q 188 1 176 19 L 176 19 Z M 80 119 Q 79 112 71 112 Q 64 113 64 121 L 92 461 L 92 461 Q 95 483 110 497 Q 126 512 148 512 L 364 512 L 364 512 Q 386 512 402 497 Q 417 483 420 461 L 448 121 L 448 121 Q 448 113 441 112 Q 433 112 432 119 L 404 459 L 404 459 Q 402 475 391 485 Q 380 496 364 496 L 148 496 L 148 496 Q 132 496 121 485 Q 110 475 108 459 L 80 119 L 80 119 Z"
                                                            />
                                                        </svg>
                    
                                                        {{$articulo['detalle']}} {{$articulo['porcentaje'] < 0 ? '('.$articulo['porcentaje'].'%)' : ''}}
                                                    </p>                                        
                                                    
                                                </th>
                        
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
    
                
                        </article>
                
                        <button wire:click="guardarPedidoMesa">Guardar Pedido</button>
                        <button wire:click="borrarCarrito" style="background-color: rgb(147, 54, 54);">Cancelar Pedido</button>

                        <hr>
                        <label for="">
                            Total: ${{ number_format( $mesaCarrito['total'], 2, ',', '.')}}
                        </label>
                        <label for="">
                            Articulos: {{$mesaCarrito['articulos']}}
                        </label>
                  </details>


                @endif




        </article>


    </div>


    <div class="container">

        <div style="justify-content: center; align-items: center; ">
            @if (session()->has('mensaje'))
                <div style="color: red; text-align: center;">
                {{ session('mensaje') }}
                </div>
            @endif
        </div>

        
        <article>

            <fieldset role="group">

                <input
                type="search"
                name="Buscar"
                placeholder="Buscar"
                aria-label="Buscar"
                wire:model.live="datoBuscado"
                />
    
                <select name="select" aria-label="Select" required wire:model.live="seleccionPrecio" style="font-size: 15px; ">
    
                    <option value="precio1">Precio 1</option>
                    <option value="precio2">Precio 2</option>
                    <option value="precio3">Precio 3</option>
    
                </select>
            </fieldset>
            



            <table>
                <tbody>

                    @foreach ($inventario as $i)

                    <tr>
                        <td style="width: 25%;">

                            <div role="group">
                                <button style="width: 15%; background-color: red;" wire:click="restarCantidad">-</button>
                            <label for="" style="            display: flex;
                                justify-content: center; /* Alinea horizontalmente en el centro */
                                align-items: center; /* Alinea verticalmente en el centro */
                                width: 50%; /* Ajusta el ancho según tus necesidades */
                                ">{{$cantidad}}
                            </label>     
                            <button style="width: 15%;  background-color: rgb(65, 112, 142);" wire:click="sumarCantidad">+</button>
                            </div>

                        </td>

                        <td style="width: 65%;">

                            <div role="group">
                                <button style="width: 100%;"
                                        wire:click="cargar('{{$i->id}}')"        
                                >
        
                                    {{$i->detalle}}
                                    (${{$i->precio}})
                
                                </button>

                            </div>

                        </td>
                    </tr>
                        
                    @endforeach

                </tbody>
            </table>
        

        </article>
            
    </div>



    <div class="container">

        {{-- {{ $comprobantes->links('paginacion.paginacion') }} --}}
        {{ $inventario->links('vendor.livewire.bootstrap') }}
    </div>


</div>
