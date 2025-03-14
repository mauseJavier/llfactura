<div>

    <style>
        .labelIcon {
          display: flex;
          justify-content: center;
          align-items: center;
          width: 10%;
          cursor: pointer;
          transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
    
        /* Efecto de iluminación */
        .highlight-green {
          box-shadow: inset 0 0 15px 5px green;
          transform: scale(1.1);
        }
    
        .highlight-red {
          box-shadow: inset 0 0 15px 5px red;
          transform: scale(1.1);
        }
      </style>



    <div class="container">

        <article>
            <div class="grid">
                <div class="col">
                    <h3>
                        ${{number_format($total, 2, ',', '.')}}
                    </h3>
                    <h6>
                        <label for="">
                            Nombre Mesa: {{$mesa->nombre}}   
                        </label>
                    </h6>
                    <h6>
                        <label for="">
                            Numero Mesa: {{$mesa->numero}}    
                        </label>
                    </h6>

                    <h6>
                        <label for="">
                            Cliente: {{$razonSocial}}
                        </label>
                    </h6>

                </div>
                
                
                <div class="col">
                    @if ($data)

                        <button wire:click="imprimirMesa">Imprimir</button>
                        <button wire:click="finalizarMesa" wire:confirm="Finalizar Mesa??">Finalizar Mesa</button>
                        <button wire:click="cancelarMesa" wire:confirm="Cancelar Mesa??" style="background-color: rgb(145, 62, 62);">Cancelar Mesa</button>
                    @endif
                </div>
            </div>

        </article>
        
        
        <article x-data="{ abierto: false }">
              
            <label>
                <input 
                    {{-- NO BORRAR ESTE IMPUT ES IMPORTATNE --}}
                    style="display: none;" 
                        name="terms" type="checkbox" role="switch" 
                        @click="abierto = !abierto" x-bind:checked="abierto" />

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

                        <label>
                            Comenzales
      
                            <select wire:model="cantidadComenzales" name="" id="" required
                                @error('cantidadComenzales') 
                                  required aria-invalid="true"
                                
                                @enderror
                            >
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
      
      
      
                              
                            </select>
                            
                            @error('cantidadComenzales') 
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


                    {{-- <fieldset class="grid">
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
                        </label> --}}
                    </fieldset>

                    <button wire:click="guardarrazonSocial" @click="abierto = !abierto">Guardar Cliente</button>

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
                                                    <p  wire:click="abrirModalEdicion({{$key}})" 
                                                        style="display: inline-block;cursor: pointer; font-size: 20px;"
                                                    >
                                                        <!-- pen-to-square icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                                                            <path
                                                            d="M 471.04 28.672 L 483.328 40.96 L 471.04 28.672 L 483.328 40.96 Q 495.616 53.248 495.616 69.632 Q 495.616 86.016 483.328 98.304 L 450.56 132.096 L 450.56 132.096 L 379.904 61.44 L 379.904 61.44 L 413.696 28.672 L 413.696 28.672 Q 425.984 16.384 442.368 16.384 Q 458.752 16.384 471.04 28.672 L 471.04 28.672 Z M 179.2 262.144 L 368.64 72.704 L 179.2 262.144 L 368.64 72.704 L 439.296 143.36 L 439.296 143.36 L 249.856 332.8 L 249.856 332.8 Q 241.664 340.992 230.4 343.04 L 150.528 361.472 L 150.528 361.472 L 168.96 281.6 L 168.96 281.6 Q 171.008 270.336 179.2 262.144 L 179.2 262.144 Z M 401.408 16.384 L 167.936 250.88 L 401.408 16.384 L 167.936 250.88 Q 156.672 262.144 152.576 278.528 L 131.072 370.688 L 131.072 370.688 Q 130.048 374.784 133.12 378.88 Q 136.192 381.952 141.312 380.928 L 233.472 359.424 L 233.472 359.424 Q 249.856 355.328 261.12 344.064 L 495.616 110.592 L 495.616 110.592 Q 512 92.16 512 69.632 Q 512 47.104 495.616 28.672 L 483.328 16.384 L 483.328 16.384 Q 464.896 0 442.368 0 Q 419.84 0 401.408 16.384 L 401.408 16.384 Z M 73.728 53.248 Q 41.984 54.272 21.504 74.752 L 21.504 74.752 L 21.504 74.752 Q 1.024 95.232 0 126.976 L 0 438.272 L 0 438.272 Q 1.024 470.016 21.504 490.496 Q 41.984 510.976 73.728 512 L 385.024 512 L 385.024 512 Q 416.768 510.976 437.248 490.496 Q 457.728 470.016 458.752 438.272 L 458.752 290.816 L 458.752 290.816 Q 457.728 283.648 450.56 282.624 Q 443.392 283.648 442.368 290.816 L 442.368 438.272 L 442.368 438.272 Q 441.344 462.848 425.984 479.232 Q 409.6 494.592 385.024 495.616 L 73.728 495.616 L 73.728 495.616 Q 49.152 494.592 32.768 479.232 Q 17.408 462.848 16.384 438.272 L 16.384 126.976 L 16.384 126.976 Q 17.408 102.4 32.768 86.016 Q 49.152 70.656 73.728 69.632 L 221.184 69.632 L 221.184 69.632 Q 228.352 68.608 229.376 61.44 Q 228.352 54.272 221.184 53.248 L 73.728 53.248 L 73.728 53.248 Z"
                                                            />
                                                        </svg>
                                                        Editar

                                                    </p>


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

                
                <label id="plus-icon" class="labelIcon"
                style="            
                        display: flex;
                        justify-content: center; /* Alinea horizontalmente en el centro */
                        align-items: center; /* Alinea verticalmente en el centro */
                        width: 12%; /* Ajusta el ancho según tus necesidades */
                        cursor:pointer;
                        "
                    wire:click="sumarCantidad"
                    >
                    
                    <!-- square-plus icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="2em" fill="currentColor" viewBox="0 0 512 512">
                        <path
                        d="M 73.14285714285714 18.285714285714285 Q 50.285714285714285 19.428571428571427 34.285714285714285 34.285714285714285 L 34.285714285714285 34.285714285714285 L 34.285714285714285 34.285714285714285 Q 19.428571428571427 50.285714285714285 18.285714285714285 73.14285714285714 L 18.285714285714285 438.85714285714283 L 18.285714285714285 438.85714285714283 Q 19.428571428571427 461.7142857142857 34.285714285714285 477.7142857142857 Q 50.285714285714285 492.57142857142856 73.14285714285714 493.7142857142857 L 438.85714285714283 493.7142857142857 L 438.85714285714283 493.7142857142857 Q 461.7142857142857 492.57142857142856 477.7142857142857 477.7142857142857 Q 492.57142857142856 461.7142857142857 493.7142857142857 438.85714285714283 L 493.7142857142857 73.14285714285714 L 493.7142857142857 73.14285714285714 Q 492.57142857142856 50.285714285714285 477.7142857142857 34.285714285714285 Q 461.7142857142857 19.428571428571427 438.85714285714283 18.285714285714285 L 73.14285714285714 18.285714285714285 L 73.14285714285714 18.285714285714285 Z M 0 73.14285714285714 Q 1.1428571428571428 42.285714285714285 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 Q 42.285714285714285 1.1428571428571428 73.14285714285714 0 L 438.85714285714283 0 L 438.85714285714283 0 Q 469.7142857142857 1.1428571428571428 490.2857142857143 21.714285714285715 Q 510.85714285714283 42.285714285714285 512 73.14285714285714 L 512 438.85714285714283 L 512 438.85714285714283 Q 510.85714285714283 469.7142857142857 490.2857142857143 490.2857142857143 Q 469.7142857142857 510.85714285714283 438.85714285714283 512 L 73.14285714285714 512 L 73.14285714285714 512 Q 42.285714285714285 510.85714285714283 21.714285714285715 490.2857142857143 Q 1.1428571428571428 469.7142857142857 0 438.85714285714283 L 0 73.14285714285714 L 0 73.14285714285714 Z M 246.85714285714286 365.7142857142857 L 246.85714285714286 265.14285714285717 L 246.85714285714286 365.7142857142857 L 246.85714285714286 265.14285714285717 L 146.28571428571428 265.14285714285717 L 146.28571428571428 265.14285714285717 Q 138.28571428571428 264 137.14285714285714 256 Q 138.28571428571428 248 146.28571428571428 246.85714285714286 L 246.85714285714286 246.85714285714286 L 246.85714285714286 246.85714285714286 L 246.85714285714286 146.28571428571428 L 246.85714285714286 146.28571428571428 Q 248 138.28571428571428 256 137.14285714285714 Q 264 138.28571428571428 265.14285714285717 146.28571428571428 L 265.14285714285717 246.85714285714286 L 265.14285714285717 246.85714285714286 L 365.7142857142857 246.85714285714286 L 365.7142857142857 246.85714285714286 Q 373.7142857142857 248 374.85714285714283 256 Q 373.7142857142857 264 365.7142857142857 265.14285714285717 L 265.14285714285717 265.14285714285717 L 265.14285714285717 265.14285714285717 L 265.14285714285717 365.7142857142857 L 265.14285714285717 365.7142857142857 Q 264 373.7142857142857 256 374.85714285714283 Q 248 373.7142857142857 246.85714285714286 365.7142857142857 L 246.85714285714286 365.7142857142857 Z"
                        />
                    </svg>
                </label>  
                
                <label for="" 
                    style="            
                        display: flex;
                        justify-content: center; /* Alinea horizontalmente en el centro */
                        align-items: center; /* Alinea verticalmente en el centro */
                        width: 12%; /* Ajusta el ancho según tus necesidades */
                        cursor:pointer;
                        font-size: 150%;">
                    {{$cantidad}}
                </label>   

                <label id="minus-icon" class="labelIcon"
                    style="            
                        display: flex;
                        justify-content: center; /* Alinea horizontalmente en el centro */
                        align-items: center; /* Alinea verticalmente en el centro */
                        width: 12%; /* Ajusta el ancho según tus necesidades */
                        cursor:pointer;
                        "
                    wire:click="restarCantidad"
                    >
                    
                        <!-- square-minus icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                        <svg xmlns="http://www.w3.org/2000/svg" height="2em" fill="currentColor" viewBox="0 0 512 512">
                            <path
                            d="M 73.14285714285714 18.285714285714285 Q 50.285714285714285 19.428571428571427 34.285714285714285 34.285714285714285 L 34.285714285714285 34.285714285714285 L 34.285714285714285 34.285714285714285 Q 19.428571428571427 50.285714285714285 18.285714285714285 73.14285714285714 L 18.285714285714285 438.85714285714283 L 18.285714285714285 438.85714285714283 Q 19.428571428571427 461.7142857142857 34.285714285714285 477.7142857142857 Q 50.285714285714285 492.57142857142856 73.14285714285714 493.7142857142857 L 438.85714285714283 493.7142857142857 L 438.85714285714283 493.7142857142857 Q 461.7142857142857 492.57142857142856 477.7142857142857 477.7142857142857 Q 492.57142857142856 461.7142857142857 493.7142857142857 438.85714285714283 L 493.7142857142857 73.14285714285714 L 493.7142857142857 73.14285714285714 Q 492.57142857142856 50.285714285714285 477.7142857142857 34.285714285714285 Q 461.7142857142857 19.428571428571427 438.85714285714283 18.285714285714285 L 73.14285714285714 18.285714285714285 L 73.14285714285714 18.285714285714285 Z M 0 73.14285714285714 Q 1.1428571428571428 42.285714285714285 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 Q 42.285714285714285 1.1428571428571428 73.14285714285714 0 L 438.85714285714283 0 L 438.85714285714283 0 Q 469.7142857142857 1.1428571428571428 490.2857142857143 21.714285714285715 Q 510.85714285714283 42.285714285714285 512 73.14285714285714 L 512 438.85714285714283 L 512 438.85714285714283 Q 510.85714285714283 469.7142857142857 490.2857142857143 490.2857142857143 Q 469.7142857142857 510.85714285714283 438.85714285714283 512 L 73.14285714285714 512 L 73.14285714285714 512 Q 42.285714285714285 510.85714285714283 21.714285714285715 490.2857142857143 Q 1.1428571428571428 469.7142857142857 0 438.85714285714283 L 0 73.14285714285714 L 0 73.14285714285714 Z M 146.28571428571428 246.85714285714286 L 365.7142857142857 246.85714285714286 L 146.28571428571428 246.85714285714286 L 365.7142857142857 246.85714285714286 Q 373.7142857142857 248 374.85714285714283 256 Q 373.7142857142857 264 365.7142857142857 265.14285714285717 L 146.28571428571428 265.14285714285717 L 146.28571428571428 265.14285714285717 Q 138.28571428571428 264 137.14285714285714 256 Q 138.28571428571428 248 146.28571428571428 246.85714285714286 L 146.28571428571428 246.85714285714286 Z"
                            />
                        </svg>

                </label>  

            </fieldset>
            



            <table>
                <tbody>

                    @foreach ($inventario as $i)

                    <tr>
                        {{-- <td style="width: 25%;">

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

                        </td> --}}

                        <td style="width: 65%;">

                            <div role="group">
                                <button style="width: 100%;"
                                        wire:click="cargar('{{$i->id}}')"        
                                >
        
                                    {{$i->detalle}}
                                    <strong>
                                        (${{$i->precio}})

                                    </strong>
                
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


    <div class="container">
        <details>
            <summary>Eliminar Mesa</summary>
            <article>
    
                <button wire:click="eliminarMesa({{$mesa->id}})"
                        style="background-color: red;"
                        wire:confirm="Seguro de Eliminar la Mesa de Forma Permanente?">Eliminar Mesa de forma Permanente??</button>
    
            </article>

          </details>



    </div>


    {{-- // ESTE ES EL MODAL  --}}
    <dialog {{$estadoModalEdicion}} >
        <article >
            <h3>Modifiar Pedido</h3>

            <fieldset>
                <label>
                Detalle
                <input type="text" wire:model="modificarDetalle" wire:keydown.enter="modificarMesaCarrito">
                </label>
                <label>
                Precio
                <input type="text" wire:model.live="modificarPrecio" wire:keydown.enter="modificarMesaCarrito">
                </label>
                <label>
                Cantidad
                <input type="text" wire:model="modificarCantidad" wire:keydown.enter="modificarMesaCarrito"
                    @error('modificarCantidad')
                    aria-invalid="true"
                    aria-describedby="invalid-helper"
                    @enderror
                >
                @error('modificarCantidad')
                <small id="invalid-helper">{{ $message }} </small>               
                @enderror
                </label>


            </fieldset>

            <hr>


            <footer>
            <button className="secondary"
                wire:click="cerrarModalEdicion">
                Cancelar
            </button>
            <button
                wire:click="modificarMesaCarrito">Modificar</button>
            </footer>
        </article>
    </dialog>

    {{-- MODAL PARA LA IMPRECION  --}}
    <dialog {{$modalImprimir}}>
        <article>
            <header>
            <button 
                aria-label="Close" rel="prev"     
                wire:click="imprimirMesa"                
                >
            </button>
            <p>
                <strong>Imprimir</strong>
            </p>
            </header>          
            <iframe width="100%" height="1000px" src="{{route('imprimirMesa',['mesa'=>$mesa->id])}}" frameborder="0"></iframe>
        </article>
    </dialog>

    <script>
        // Selección de elementos
        const plusIcon = document.getElementById('plus-icon');
        const minusIcon = document.getElementById('minus-icon');
    
        // Evento para el ícono de suma
        plusIcon.addEventListener('click', () => {
        //   plusIcon.classList.add('highlight-green');
        //   setTimeout(() => plusIcon.classList.remove('highlight-green'), 500);

        plusIcon.classList.add('highlight-green');
        setTimeout(() => plusIcon.classList.remove('highlight-green'), 500);

        });
    
        // Evento para el ícono de resta
        minusIcon.addEventListener('click', () => {
          minusIcon.classList.add('highlight-red');
          setTimeout(() => minusIcon.classList.remove('highlight-red'), 500);
        });
      </script>


</div>
