<div>
    {{-- @dump($depositoId) --}}

    <div class="container">
        <h1>Movimiento por Codigo</h1>
        <article>
            <div class="grid">
                <div class="col">
                    <a wire:navigate role="button" href="{{route('stock')}}">Stock</a>
                </div>
                <div class="col">

                    <!-- Select -->
                    <select wire:model.live="depositoId">
                        <option value="todo">TODO</option>                            

                        @foreach ($depositos as $d)
                            <option value="{{$d->id}}">{{$d->nombre}}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input
                        type="search"
                        name="buscar"
                        placeholder="Buscar"
                        aria-label="Search"
                        wire:model.live="codigo"
                    />

                </div>
            </div>
        </article>

        {{-- 0 => {#1615 ▼
            +"id": 1
            +"codigo": "7816281284403"
            +"detalle": "Licor"
            +"deposito_id": 1
            +"stock": -1.0
            +"comentario": "comp n-1"
            +"usuario": "MAUSE LLFACTURA"
            +"empresa_id": 1
            +"created_at": "2024-06-04 21:58:07"
            +"updated_at": "2024-06-04 21:58:07"
          }
           --}}

        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    <th scope="col">Codigo</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Deposito</th>
                    <th scope="col">stock</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Fecha</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($registros as $item)

                    <tr>
                        <td>{{$item->codigo}}</td>
                        <td>{{$item->detalle}}</td>
                        <td>{{$item->nombreDeposito}}</td>
                        <td>{{$item->stock}}</td>
                        <td>{{$item->comentario}}</td>
                        <td>{{$item->usuario}}</td>
                        <td>{{$item->created_at}}</td>
                      </tr>
                        
                    @endforeach

                </tfoot>
              </table>
          </div>
          {{ $registros->links('vendor.livewire.bootstrap') }}
    </div>
</div>
