<div>

    <div class="container">
        <h3>Novedades</h3>

@if ($usuario->role_id == 3)
    
        <article>

                <fieldset>
                  <label>
                    {{$id}}- Titulo
                    <input
                    wire:model="titulo"
                      name="titulo"
                      placeholder="Titulo"
                      autocomplete="titulo"
                    />
                  </label>
                  <label for="">
                    Detalle
                    <textarea wire:model="detalle" name="detalle" id="" cols="30" rows="5"></textarea>
                  </label>
                  <label>
                    Nombre Ruta (Si la ruta no exite larga error!)
                    <input
                    wire:model="nombreRuta"
                      type="text"
                      name="nombreRuta"
                      placeholder="Nombre Ruta"
                      autocomplete="nombreRuta"
                    />
                  </label>
                  <label>
                    URL Externa
                    <input
                    wire:model="url"
                      type="text"
                      name="url"
                      placeholder="URL Externa"
                      autocomplete="url"
                    />
                  </label>
                  <label>
                    Pie de Tarjeta
                    <input
                    wire:model="pie"

                      type="text"
                      name="pie"
                      placeholder="Pie de Tarjeta"
                      autocomplete="pie"
                    />
                  </label>
                </fieldset>
              

                <button wire:click="guardarNovedad()">Guardar</button>
                
              </article>
@endif

          @if (session('mensaje'))
          <hr>
          <div class="alert alert-success">
              {{ session('mensaje') }}
          </div>
          @endif


        <hr>

        @foreach ($novedades as $item)

          <article>
              <header>
                <div class="grid" style="text-align: center;">
                  
                  <h5><small>({{$item->id}})</small>-{{$item->titulo}}</h5>

                  @if ($usuario->role_id == 3)      
                    <button style="background-color: rgb(62, 132, 180)" wire:click="editar({{$item->id}})">Editar</button>
                
                    <button style="background-color: rgb(161, 91, 75)" wire:click="eliminar({{$item->id}})">Eliminar</button>
                  @endif
                </div>
                
              </header>
              
              {{$item->detalle}}

              <hr>
              
              <ul>
                @if ($item->nombreRuta != '')                    
                  <li>
                    <a href="{{$item->nombreRuta}}">{{$item->titulo}}</a>
                    
                  </li>
                @endif
                @if ($item->url != '')                    
                  <li>
                    <a href="{{$item->url}}" target="_blank" rel="noopener noreferrer">{{$item->url}}</a>
                  </li>
                @endif
              </ul>
              <footer>{{$item->pie}}</footer>
          </article>
            
        @endforeach



    </div>
</div>
