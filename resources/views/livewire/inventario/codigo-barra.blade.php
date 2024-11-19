<div>


    <div class="container">
        <h3>Codigos de Barra</h3>

        <div wire:loading> 
            Saving post...
        </div>
        
        @if (count($arrayInventario)>0)
            <article>
                    
                <details close>
                    <summary>Ver Cargados {{ isset($arrayInventario) ? '|| Articulos: '.count($arrayInventario) : ''}}</summary>
                    
                    <div class="overflow-auto">

                        <table>
                            <thead>
                            <tr>
                                <th scope="col">Codigo</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Precio</th>
                                <th>Tipo</th>
                                <th>Borrar</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($arrayInventario as $key => $item)
                                    
                                    <tr>
                                        <th scope="row">{{$item['codigo']}}</th>
                                        <td>{{$item['detalle']}}</td>
                                        <td>${{$item['precio']}}</td>
                                        <td>{{$item['tipo']}}</td>
    
                                        <td wire:click="borrarArticulo({{$key}})">
                                            <!-- trash icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                                                <path
                                                d="M 210 16 L 302 16 L 210 16 L 302 16 Q 315 16 323 27 L 346 64 L 346 64 L 166 64 L 166 64 L 189 27 L 189 27 Q 197 16 210 16 L 210 16 Z M 176 19 L 148 64 L 176 19 L 148 64 L 40 64 L 40 64 Q 33 65 32 72 Q 33 79 40 80 L 472 80 L 472 80 Q 479 79 480 72 Q 479 65 472 64 L 364 64 L 364 64 L 336 19 L 336 19 Q 324 1 302 0 L 210 0 L 210 0 Q 188 1 176 19 L 176 19 Z M 80 119 Q 79 112 71 112 Q 64 113 64 121 L 92 461 L 92 461 Q 95 483 110 497 Q 126 512 148 512 L 364 512 L 364 512 Q 386 512 402 497 Q 417 483 420 461 L 448 121 L 448 121 Q 448 113 441 112 Q 433 112 432 119 L 404 459 L 404 459 Q 402 475 391 485 Q 380 496 364 496 L 148 496 L 148 496 Q 132 496 121 485 Q 110 475 108 459 L 80 119 L 80 119 Z"
                                                />
                                            </svg>

                                        </td>
                                    </tr>
                                @endforeach
    
                            </tbody>
    
                        </table>
                    </div>
        
                </details>
            </article>

        @endif

        
          

        <article>
            <p>Inventario</p>
            <div class="grid">
                <div class="col">
                    <button wire:click="borrar()" style="background-color: red;">Borrar Todos los Codigos</button>
                    <a href="{{route('codigoBarraPdf')}}" role="button">Generar PDF</a>

                </div>
                <div class="col">
                    <input
                        type="search"
                        name="search"
                        placeholder="Buscar"
                        aria-label="Search"
                        wire:model.live="datoBuscado"
                    />

                </div>
            </div>



            <hr>
{{-- 
            "id" => 1
            "codigo" => "4631658036149"
            "detalle" => "Vodka"
            "costo" => 422.33
            "precio1" => 35.7
            "precio2" => 194.4
            "precio3" => 191.11
            "porcentaje" => 0.0
            "iva" => 10.5
            "rubro" => "Electrónica"
            "proveedor" => "MAUSE"
            "marca" => null
            "pesable" => "no"
            "controlStock" => "si"
            "imagen" => null
            "empresa_id" => 1
            "created_at" => "2024-06-24 18:24:04"
            "updated_at" => "2024-06-24 18:24:04" --}}

            <div class="overflow-auto">
                <table class="striped">
                    <thead>
                        <tr>
                            <td>Codigo</td>
                            <td>Detalle</td>

                            <td>Precio 1</td>
                            <td>Precio 2</td>
                            <td>Precio 3</td>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventario as $item)
                        
                            <tr>
                                <td >{{$item->codigo}}</td>
                                <td>{{$item->detalle}}</td>
                                <td><button data-tooltip="Agregar Precio 1" wire:click="cargarArticulo({{$item->id}},'precio1')">${{$item->precio1}}</button></td>
                                <td><button data-tooltip="Agregar Precio 2" wire:click="cargarArticulo({{$item->id}},'precio2')">${{$item->precio2}}</button></td>
                                <td><button data-tooltip="Agregar Precio 3" wire:click="cargarArticulo({{$item->id}},'precio3')">${{$item->precio3}}</button></td>


                            </tr>
            
                        
                        @endforeach          
                    </tbody>
                </table>
            </div>
            
            
        </article>


    </div>

    <div class="container">
        {{ $inventario->links('vendor.livewire.bootstrap') }}

    </div>
</div>
