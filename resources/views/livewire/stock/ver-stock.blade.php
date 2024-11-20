<div x-data="{ modalOpen: false, modalStock: false }">
    {{-- @dump(        $stock    ) --}}

    <div class="container">
      <hgroup>
        <h2>Stock</h2>
        <p>Deposito Usuario {{$nombreDepositoUsuario}}</p>
      </hgroup>

      <article>
          <div class="grid">
                <div class="col">
                  <button @click=" modalOpen = !modalOpen" >Nuevo Local</button>
                  <a wire:navigate role="button" href="{{route('importarstock')}}">Importar Stock</a>
                </div>


                <div class="col">
                
                  <fieldset role="group">
                    <input
                        type="search"
                        name="buscar"
                        placeholder="Buscar"
                        aria-label="Search"
                        wire:model.live="datoBuscado"
                    />
                    <select wire:model.live="idDepositoUsuario">
                      <option value="">Todo</option>

                      @foreach ($todosDepositos as $item)
                         
                          <option value="{{$item->id}}">({{$item->id}}) {{$item->nombre}}</option>

                      @endforeach

                    </select>

                  </fieldset>

                    
            


                </div>
          </div>

          <div class="grid">

                {{-- SI ES NULL NO SE PUEDEN HACER ENVIOS  --}}
                @if ($depositos)                 
                  <div class="col">

                      <select name="" wire:model="depositoDestino_id" >
                        @foreach ($depositos as $item)                                
                              <option value="{{$item->id}}" >Enviar a Deposito: {{$item->nombre}}</option>               
                        @endforeach
                      </select>
   
                  </div>
                  <div class="col">
                    <a role="button" wire:navigate href="{{route('recibirstock')}}">Recibir Stock</a>
                    <a role="button" wire:navigate href="{{route('historicoenvio')}}">Historico</a>
                  </div>
                @endif


          </div>
            
      </article>

        @if (session('mensaje'))        
          <article style="color: green;">
            {{ session('mensaje') }}
          </article>
        @endif


        @if ($stock)
            
           <div class="overflow-auto">
                  <table class="striped">
                      <thead>
                        <tr>
      
                          <th scope="col">Codigo</th>
                          <th scope="col">Detalle</th>
                          <th scope="col">Deposito</th>
                          <th scope="col">stock</th>
                          @if ($usuario->role_id == 2 || $usuario->role_id == 3 || $usuario->role_id == 4 )  
                            <th scope="col">Modificar</th>
                          @endif
                          {{-- si no existen depositos no se puede enviar --}}
                          @if ($depositos)  
                            <th scope="col">Enviar</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                          @foreach ($stock as $item)
      
                          <tr>
                              <td>
                                <a wire:navigate href="{{route('movimientostock',['stock_id'=>$item->id])}}">{{$item->codigo}}</a>
      
                              </td>
                              <td>{{$item->detalle}}</td>
                              <td>{{$item->nombreDeposito}}</td>
                              <td>{{$item->sumStock}}</td>

                              {{-- PARA EL AUMENTO DE STOCK --}}
                              @if ($usuario->role_id == 2 || $usuario->role_id == 3 || $usuario->role_id == 4 )  


                                <td>
                                  <div role="group" title="Modificar / Eliminar">
                                           <!-- plus icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                      <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512"
                                      
                                        style="color:#4EB31B;" aria-hidden="true" 
                                        wire:click="asignarCodigoDetalle('{{$item->codigo}}','{{$item->detalle}}','{{$item->depositoId}}')" 
                                        @click=" modalStock = !modalStock"
                                      >
                                        <path
                                          d="M 295.38461538461536 39.38461538461539 Q 295.38461538461536 22.153846153846153 284.3076923076923 11.076923076923077 L 284.3076923076923 11.076923076923077 L 284.3076923076923 11.076923076923077 Q 273.2307692307692 0 256 0 Q 238.76923076923077 0 227.69230769230768 11.076923076923077 Q 216.6153846153846 22.153846153846153 216.6153846153846 39.38461538461539 L 216.6153846153846 216.6153846153846 L 216.6153846153846 216.6153846153846 L 39.38461538461539 216.6153846153846 L 39.38461538461539 216.6153846153846 Q 22.153846153846153 216.6153846153846 11.076923076923077 227.69230769230768 Q 0 238.76923076923077 0 256 Q 0 273.2307692307692 11.076923076923077 284.3076923076923 Q 22.153846153846153 295.38461538461536 39.38461538461539 295.38461538461536 L 216.6153846153846 295.38461538461536 L 216.6153846153846 295.38461538461536 L 216.6153846153846 472.61538461538464 L 216.6153846153846 472.61538461538464 Q 216.6153846153846 489.84615384615387 227.69230769230768 500.9230769230769 Q 238.76923076923077 512 256 512 Q 273.2307692307692 512 284.3076923076923 500.9230769230769 Q 295.38461538461536 489.84615384615387 295.38461538461536 472.61538461538464 L 295.38461538461536 295.38461538461536 L 295.38461538461536 295.38461538461536 L 472.61538461538464 295.38461538461536 L 472.61538461538464 295.38461538461536 Q 489.84615384615387 295.38461538461536 500.9230769230769 284.3076923076923 Q 512 273.2307692307692 512 256 Q 512 238.76923076923077 500.9230769230769 227.69230769230768 Q 489.84615384615387 216.6153846153846 472.61538461538464 216.6153846153846 L 295.38461538461536 216.6153846153846 L 295.38461538461536 216.6153846153846 L 295.38461538461536 39.38461538461539 L 295.38461538461536 39.38461538461539 Z"
                                        />
                                      </svg>

                                      <!-- trash icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                      <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512"
                                      
                                        style="color: red;" wire:click="eliminarSockDeposito('{{$item->codigo}}','{{$item->depositoId}}')" 
                                        wire:confirm="Seguro de Eliminar TODO! el Stock de {{$item->detalle}} de: {{$item->nombreDeposito}}??"
                                      >
                                        <path
                                          d="M 210 16 L 302 16 L 210 16 L 302 16 Q 315 16 323 27 L 346 64 L 346 64 L 166 64 L 166 64 L 189 27 L 189 27 Q 197 16 210 16 L 210 16 Z M 176 19 L 148 64 L 176 19 L 148 64 L 40 64 L 40 64 Q 33 65 32 72 Q 33 79 40 80 L 472 80 L 472 80 Q 479 79 480 72 Q 479 65 472 64 L 364 64 L 364 64 L 336 19 L 336 19 Q 324 1 302 0 L 210 0 L 210 0 Q 188 1 176 19 L 176 19 Z M 80 119 Q 79 112 71 112 Q 64 113 64 121 L 92 461 L 92 461 Q 95 483 110 497 Q 126 512 148 512 L 364 512 L 364 512 Q 386 512 402 497 Q 417 483 420 461 L 448 121 L 448 121 Q 448 113 441 112 Q 433 112 432 119 L 404 459 L 404 459 Q 402 475 391 485 Q 380 496 364 496 L 148 496 L 148 496 Q 132 496 121 485 Q 110 475 108 459 L 80 119 L 80 119 Z"
                                        />
                                      </svg>

                                  </div>
                                </td>

                              @endif

                                {{-- si no existen depositos no se puede enviar --}}
                              @if ($depositos) 
                                  <td>
                                    <form wire:submit="enviarArticulo({{$item->depositoId}},'{{$item->codigo}}','{{$item->detalle}}')" wire:confirm="Seguro de Enviar?">
                                      <div class="grid">
                                        <div>
                                          <input type="text" wire:model="cantidadEnviar" placeholder="Cantidad Enviar" style="min-width: 80px">
                                            @error('cantidadEnviar')
                                              <small id="invalid-helper" style="color: red;">
                                                {{ $message }}
                                              </small>  
                                            @enderror
                                        </div>
                                        <div>
                                          <input type="submit" value="Enviar">
                                        </div>
                                      </div>
                                    </form>
                                  </td>                            
                                @endif
                            </tr>
                              
                          @endforeach
      
                      </tfoot>
                    </table>
                </div>
      
                {{ $stock->links('vendor.livewire.bootstrap') }}
          </div>
            
        
        @endif

  <dialog x-bind:open="modalOpen">
        <article>
            <header>
                <button @click=" modalOpen = !modalOpen" aria-label="Close" rel="prev"></button>
                <p>
                    <strong>Nuevo Local</strong>
                </p>
            </header>
            @if (session()->has('mensaje'))          
                <p style="color: rgb(0, 137, 90);">
                    {{ session('mensaje') }}
                </p>          
            @endif
          <form wire:submit="guardarDeposito">
            <fieldset>
              <label>
                Nombre
                <input
                    tipe="text"
                  name="nombreDeposito"
                  placeholder="Nombre Local"
                  autocomplete="nombreDeposito"
                  wire:model="nombreDeposito"
                  @error('nombreDeposito')  aria-invalid="true" @enderror
                />
                @error('nombreDeposito') <small id="valid-helper">{{ $message }}</small>@enderror
              </label>
              <label>
                Comentario
                <input
                  type="text"
                  name="comentarioDeposito"
                  placeholder="Comentario"
                  autocomplete="comentarioDeposito"
                  wire:model="comentarioDeposito"
                />
              </label>
            </fieldset>
          
            <input
              type="submit"
              value="Guardar Deposito"
            />
          </form>

          <button @click=" modalOpen = !modalOpen">Cerrar</button>
        </article>
  </dialog>

  <dialog x-bind:open="modalStock">
    <article>
        <header>
            <button @keydown.escape.window="if(modalStock) modalStock = false" @click=" modalStock = !modalStock" aria-label="Close" rel="prev"></button>
            <p>
                <strong>Modificar Stock</strong>
            </p>
        </header>
        @if (session()->has('mensaje'))          
            <p style="color: rgb(0, 137, 90);">
                {{ session('mensaje') }}
            </p>          
        @endif

        
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

        <select wire:model="idDepositoGuardar" name="idDepositoGuardar" aria-label="">
            @foreach ($todosDepositos as $item)
                <option value="{{$item->id}}">({{$item->id}}) {{$item->nombre}}</option>                        
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

        <label>
          Comentario
          <input
              wire:model.live="comentario"
              name="comentario"
              placeholder="Comentario"
              autocomplete="comentario"
              @error('comentario') aria-invalid="true" @enderror
          />
              @error('comentario') 
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


      <button @click=" modalStock = !modalStock">Cerrar</button>
    </article>
</dialog>




</div>
