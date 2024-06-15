<div x-data="{ modalOpen: false }">
    {{-- @dump(        $stock    ) --}}

    <div class="container">
        <h1>Stock</h1>
        <article>
            <div class="grid">
                <div class="col">
                  <button @click=" modalOpen = !modalOpen" >Nuevo Deposito</button>
                </div>
                <div class="col">
                  <a wire:navigate role="button" href="{{route('importarstock')}}">Importar Stock</a>
                </div>

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

                <div class="col">
                    <input
                        type="search"
                        name="buscar"
                        placeholder="Buscar"
                        aria-label="Search"
                        wire:model.live="datoBuscado"
                    />

                </div>
            </div>
            
        </article>

        @if (session('mensaje'))        
          <article style="color: green;">
            {{ session('mensaje') }}
          </article>
        @endif


        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>

                    <th scope="col">Codigo</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Deposito</th>
                    <th scope="col">stock</th>
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
                          <a wire:navigate href="{{route('movimientostock',['codigo'=>$item->codigo])}}">{{$item->codigo}}</a>
 
                        </td>
                        <td>{{$item->detalle}}</td>
                        <td>{{$item->nombreDeposito}}</td>
                        <td>{{$item->sumStock}}</td>
                          {{-- si no existen depositos no se puede enviar --}}
                          @if ($depositos)
                            <td>
                              <form wire:submit="enviarArticulo({{$item->depositoId}},'{{$item->codigo}}','{{$item->detalle}}')" wire:confirm="Seguro de Enviar?">
                                <div class="grid">
                                  <div>
                                    <input type="text" wire:model="cantidadEnviar" placeholder="Cantidad Enviar">
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




</div>
