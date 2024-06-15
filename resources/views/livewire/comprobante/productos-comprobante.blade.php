<div>
    <div class="container">

        {{-- @dump($productos) --}}

        <h1>Productos del comprobante N° {{$idComprobante}} </h1>
        <article>
            
            <div class="grid">

                <div class="col">
                    <div role="group">
                        {{-- <button class="outline">Rubro</button>
                        <button class="outline">Proveedor</button> --}}
                        <a wire:navigate href="{{route('comprobante')}}" role="button">Comprobantes</a>
                    </div>
                </div>

                <div class="col">
                    <form role="search"  wire:submit="">        
                        
                        <input disabled wire:model.live="datoBuscado" name="search" type="search" placeholder="Buscar" class="seleccionarTodo" />
                        {{-- <input type="submit" value="Buscar" /> --}}
                    </form>
                </div>
            </div>
                
        </article>

        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    <th scope="col">codigo</th>
                    <th scope="col">detalle</th>
                    <th scope="col">precio</th>
                    <th scope="col">iva</th>
                    <th scope="col">cantidad</th>
                    <th scope="col">rubro</th>
                    <th scope="col">proveedor</th>
                    <th scope="col">fecha</th>
                    <th scope="col">tipoComp</th>
                    <th scope="col">idFormaPago</th>
                    <th scope="col">usuario</th>
                    <th scope="col">ptoVta</th>
                    <th scope="col">empresa_id</th>

                  </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $e)
                        <tr>
                        <td>{{$e->codigo}}</td>
                        <td>{{$e->detalle}}</td>
                        <td>{{$e->precio}}</td>
                        <td>{{$e->iva}}</td>
                        <td>{{$e->cantidad}}</td>
                        <td>{{$e->rubro}}</td>
                        <td>{{$e->proveedor}}</td>
                        <td>{{$e->fecha}}</td>
                        <td>{{$e->tipoComp}}</td>
                        <td>{{$e->idFormaPago}}</td>
                        <td>{{$e->usuario}}</td>
                        <td>{{$e->ptoVta}}</td>
                        <td>{{$e->empresa_id}}</td>
                        
                        </tr>
                        
                    @endforeach
                </tfoot>
              </table>
        </div>

        {{ $productos->links('vendor.livewire.bootstrap') }}

    </div>
</div>
