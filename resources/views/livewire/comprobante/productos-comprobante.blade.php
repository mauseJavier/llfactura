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
                    @if ($comp->tipoComp == 'remito')
                        <a wire:navigate role="button" href="{{route('facturarRemito',['idComprobante'=>$comp->id])}}"> Facturar </a>
                        
                    @endif
                    <a wire:navigate role="button" href="{{route('formatoPDF',['tipo'=>'factura','comprobante_id'=>$comp->id])}}">Imprimir</a>
                </div>
            </div>
                
        </article>

        <article>

            <h4>Detalles:</h4>

            <div class="grid">
                <div class="col">

                    <label for="">
                        @switch($comp->tipoComp)
                            @case(1)
                                Tipo: Factura A
                            @break
                            @case(6)
                                Tipo: Factura B
                            @break
                            @case(11)
                                Tipo: Factura C
                            @break
                            @case(51)
                                Tipo: Factura M
                            @break
        
                            @case('remito')
                                Tipo: Remito
                            @break
        
                            @case('3')
                                Tipo: NC A
                            @break
                            @case('8')
                                Tipo: NC B
                            @break
                            @case('13')
                                Tipo: NC C
                            @break
                        @case('notaRemito')
                            NC R
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
                        Razon Social: {{$comp->razonSocial}}
                    </label>                    
                    <label for="">
                        Tipo Cntribuyente: {{$comp->tipoContribuyente}}
                    </label>



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

                    <label for="">
                        Domicilio: {{$depo->domicilio}}
                    </label>                    
                    <label for="">
                        Leyenda: {{$depo->leyenda}}
                    </label>                    


                    <label for="">
                        Usuario: {{$comp->usuario}}
                    </label>




                    

                </div>
            </div>

            <hr>

            <div class="grid">
                <div class="col">
                    <label for="">
                        FP Uno: {{$fpUno->nombre}}
                    </label>
                    <label for="">
                        FP Dos: {{$fpDos->nombre}}
                    </label>

                </div>
                <div class="col">
                    <label for="">
                        Pago Uno: ${{$comp->importeUno}}
                    </label>                    
                    <label for="">
                        Pago Dos: ${{$comp->importeDos}}
                    </label>

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
