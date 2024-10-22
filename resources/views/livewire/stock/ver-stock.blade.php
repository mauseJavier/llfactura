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
                  <button @click=" modalOpen = !modalOpen" >Nuevo Deposito</button>
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
                          @if ($usuario->role_id == 2 || $usuario->role_id == 3 )  
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
                              @if ($usuario->role_id == 2 || $usuario->role_id == 3 )  
                                {{-- <td>
                                  <div role="group">
                                    <button wire:click="asignarCodigoDetalle('{{$item->codigo}}','{{$item->detalle}}','{{$item->depositoId}}')" @click=" modalStock = !modalStock" ><i class="fa fa-plus" aria-hidden="true"></i></button>
                                  
                                    <button style="background-color: red;" wire:click="eliminarSockDeposito('{{$item->codigo}}','{{$item->depositoId}}')"  > <i class="fa-solid fa-trash"></i></button>
                                  </div>
                                </td> --}}

                                <td>
                                  <div role="group" title="Modificar / Eliminar">
                                                                     
                                    <i class="fa fa-plus" style="color:#4EB31B;" aria-hidden="true" wire:click="asignarCodigoDetalle('{{$item->codigo}}','{{$item->detalle}}','{{$item->depositoId}}')" @click=" modalStock = !modalStock"></i>
                                    {{-- //AL ELIMINAR EL STOCK SERGIO NO TIENE CONTROL POR QUE SUS EMPLEADOS SON TODOS ADMIN --}}
                                    {{-- <i class="fa-solid fa-trash" style="color: red;" wire:click="eliminarSockDeposito('{{$item->codigo}}','{{$item->depositoId}}')" wire:confirm="Seguro de Eliminar TODO el Stock de {{$item->detalle}} de: {{$item->nombreDeposito}}??"></i> --}}
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
                    <strong>Nuevo Deposito</strong>
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
                  placeholder="Nombre Deposito"
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
