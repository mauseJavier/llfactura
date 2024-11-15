<div>
    <div class="container">

        {{-- @dump($productos) --}}

        <h1>Productos del comprobante N° {{$comp->numero}} </h1>
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
                        
                        <input disabled  name="search" type="search" placeholder="Buscar" class="seleccionarTodo" />
                        {{-- <input type="submit" value="Buscar" /> --}}
                    </form>
                </div>
            </div>
                
        </article>

        <article>

            <h4>Detalles:</h4>

            <div class="grid">
                <div class="col">

                    <label for="">
                        Tipo: 
                        @switch($comp->tipoComp)
                            @case(1)
                            <labelFactura A</label>
                            @break
                            @case(6)
                            <labelFactura B</label>
                            @break
                            @case(11)
                            <labelFactura C</label>
                            @break
                            @case(51)
                            <labelFactura M</label>
                            @break
        
                            @case('remito')
                            <labelRemito</label>
                            @break
        
                            @case('3')
                            <labelNC A</label>
                            @break
                            @case('8')
                            <labelNC B</label>
                            @break
                            @case('13')
                            <labelNC C</label>
                            @break
                        @case('notaRemito')
                            <labelNC R</label>
                            @break
                        @default
        
                        @endswitch
                    </label>
                    <label for="">
                        Cae: {{$comp->cae}}
                    </label>
                    <label for="">
                        Ven. Cae: {{$comp->fechaVencimiento}}
                    </label>

                    <label for="">
                        Tipo Doc: {{$comp->DocTipo}}
                    </label>                    
                    <label for="">
                        Cuit Cliente: {{$comp->cuitCliente}}
                    </label>                    
                    <label for="">
                        Ven. Cae: {{$comp->fechaVencimiento}}
                    </label>                    
                    <label for="">
                        Ven. Cae: {{$comp->fechaVencimiento}}
                    </label>

                    "DocTipo" => 99
                    "cuitCliente" => 0
                    "razonSocial" => "Consumidor Final"
                    "tipoContribuyente" => 5

                </div>
                <div class="col">
                    <label for="">
                        Total: ${{number_format($comp->total,2)}}
                    </label>
                    <label for="">
                        Fecha Hs. Comp: {{$comp->fecha}}
                    </label>
                    <label for="">
                        Pto Venta: {{$comp->ptoVta}}
                    </label>
                    <label for="">
                        Desposito: {{$depo->nombre}}
                    </label>
                    

                </div>
            </div>

            "DocTipo" => 99
            "cuitCliente" => 0
            "razonSocial" => "Consumidor Final"
            "tipoContribuyente" => 5
            "domicilio" => null
            "leyenda" => null
            "idFormaPago" => 5
            "importeUno" => 31885.93
            "idFormaPago2" => 5
            "importeDos" => 0.0
            "remito" => "no"
            "empresa_id" => 1
            "usuario" => "JAVIER LLFACTURA"


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
