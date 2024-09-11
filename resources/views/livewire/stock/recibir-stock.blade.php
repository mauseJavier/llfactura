<div>
    {{-- @dump($registros) --}}
    <div class="container">
        <hgroup>
            <h1>Recibir Stock</h1>
            <h4>Deposito Usuario {{$nombreDepositoUsuario[0]->nombre}}</h4>

          </hgroup>
        <article>
            <div class="grid">
                <div class="col">
                    <a role="button" wire:navigate href="{{route('stock')}}">Stock</a>
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
                    <th scope="col">Fecha H.</th>
                    <th scope="col">Codigo</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Origen</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Recibir</th>

                  </tr>
                </thead>
                <tbody>
                    @foreach ($registros as $r)
                        
                        <tr>
                        <th scope="row">{{$r->fechaHora}}</th>
                        <td>{{$r->codigo}}</td>
                        <td>{{$r->detalle}}</td>
                        <td>{{$r->stock}}</td>
                        <td>{{$r->depositoOrigen}}</td>
                        <td>{{$r->depositoDestino}}</td>
                        <td>{{$r->comentario}}</td>
                        <td>{{$r->usuarioEnvio}}</td>
                        <td>
                            <button wire:click="recibirStock({{$r->id}})" wire:confirm="Recibir?" >Recibir</button>
                        </td>
                        </tr>
                    @endforeach

                </tbody>

              </table>
          </div>

          {{ $registros->links('vendor.livewire.bootstrap') }}
    </div>
</div>
