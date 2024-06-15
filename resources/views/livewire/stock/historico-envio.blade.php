<div>
    {{-- @dump($registros) --}}
    <div class="container">
        <hgroup>
            <h1>Historico</h1>
          </hgroup>
        <article>
            <div class="grid">
                <div class="col">
                    <a role="button" wire:navigate href="{{route('stock')}}">Stock</a>
                </div>
                <div class="col">
                    <input type="date" name="date" wire:model.live="fechaFiltro" aria-label="Date" />
                </div>
            </div>
        </article>

        @if (session('mensaje'))        
            <article style="color: green;">
            {{ session('mensaje') }}
            </article>
        @endif

        {{-- [▼
        "id" => 1
        "codigo" => "3934623019965"
        "detalle" => "Vodka"
        "depositoOrigen_id" => 1
        "depositoDestino_id" => 3
        "stock" => 1.0
        "estado" => "recibido"
        "comentario" => "Comentario"
        "usuarioEnvio" => "MAUSE LLFACTURA"
        "usuarioRecibo" => "MAUSE LLFACTURA"
        "empresa_id" => 1
        "created_at" => "2024-06-09 19:07:35"
        "updated_at" => "2024-06-09 19:36:10"
        "depositoOrigen" => "General"
        "depositoDestino" => "TERCER DEPOSITO"
      ]
       --}}

        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    <th scope="col">F. Envio.</th>
                    <th scope="col">F. Recibo</th>
                    <th scope="col">Codigo</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Origen</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Estado</th>
                    <th scope="col">U. Envio</th>
                    <th scope="col">U. Recibo</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Eliminar</th>


                  </tr>
                </thead>
                <tbody>
                    @foreach ($registros as $r)
                        
                        <tr>
                        <th scope="row">{{$r->created_at}}</th>
                        <td>{{$r->updated_at}}</td>
                        <td>{{$r->codigo}}</td>
                        <td>{{$r->detalle}}</td>
                        <td>{{$r->stock}}</td>
                        <td>{{$r->depositoOrigen}}</td>
                        <td>{{$r->depositoDestino}}</td>
                        <td>{{$r->estado}}</td>
                        <td>{{$r->usuarioEnvio}}</td>
                        <td>{{$r->usuarioRecibo}}</td>
                        <td>{{$r->comentario}}</td>

                        <td>
                            @if ($r->estado != 'recibido')                                
                                <button wire:click="eliminarRegistro({{$r->id}})" wire:confirm="Eliminar?" >Eliminar</button>
                            @endif
                        </td>
                        </tr>
                    @endforeach

                </tbody>

              </table>
          </div>

          {{ $registros->links('vendor.livewire.bootstrap') }}
    </div>
</div>
