<div>


    <div class="container">
        <h3>Codigos de Barra</h3>
        
        @if (count($arrayInventario)>0)
            <article>
                    
                <details close>
                    <summary>Ver Cargados {{ isset($arrayInventario) ? '|| Articulos:'.count($arrayInventario) : ''}}</summary>
                    
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

                                    <td wire:click="borrarArticulo({{$key}})"><i class="fa-solid fa-trash"></i></td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
        
                </details>
            </article>

        @endif

        
          

        <article>
            <p>Inventario</p>
            <button wire:click="borrar()">Borrar Codigos</button>
            <a href="{{route('codigoBarraPdf')}}" role="button">Generar PDF</a>
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
                                <td><button wire:click="cargarArticulo({{$item->id}},'precio1')">${{$item->precio1}}</button></td>
                                <td><button wire:click="cargarArticulo({{$item->id}},'precio2')">${{$item->precio2}}</button></td>
                                <td><button wire:click="cargarArticulo({{$item->id}},'precio3')">${{$item->precio3}}</button></td>


                            </tr>
            
                        
                        @endforeach          
                    </tbody>
                </table>
            </div>
            
            
        </article>


    </div>
</div>
