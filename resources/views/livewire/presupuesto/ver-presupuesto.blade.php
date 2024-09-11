<div>
    <div class="container">
        <H3>Presupuestos</H3>

        @if (session('mensaje'))
          <article>
            {{ session('mensaje') }}
          </article>
        @endif

        <div class="grid">

            <div class="col">{{-- PRESUPUESTO --}}
                            
                <article>
                    <input type="search" wire:model.live="datoBuscado">
                </article>
                {{-- "id" => 1
                    "tipoComp" => "presupuesto"
                    "numero" => 1
                    "total" => 1161.6
                    "fechaVencimiento" => "2024-06-30"
                    "fecha" => "2024-06-23 13:41:54"
                    "deposito_id" => 2
                    "DocTipo" => 86
                    "cuitCliente" => 20358337164
                    "razonSocial" => "DESMARET JAVIER NICOLAS"
                    "tipoContribuyente" => 5
                    "domicilio" => ""
                    "leyenda" => null
                    "idFormaPago" => 3
                    "empresa_id" => 1
                    "usuario" => "Javier Desmaret"
                    "created_at" => "2024-06-23 13:41:54"
                    "updated_at" => "2024-06-23 13:41:54"
              ] --}}

              <div class="overflow-auto">
                <table class="striped">
                    <thead>
                      <tr>
                        <th scope="col">N°</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($presupuestos as $p)
                            <tr wire:click="traerProductos({{$p->id}})">
                                <td>{{$p->numero}}</td>
                                <td>{{$p->razonSocial}}</td>
                                <td>{{$p->usuario}}</td>
                                <td>${{$p->total}}</td>



                                
                            </tr>
                  
                        @endforeach

                    </tbody>

                  </table>
              </div>
              



            </div>{{-- PRESUPUESTO --}}


            <div class="col">{{-- LISTA DE PRODUCTOS --}}
                <article>
                    <h6>Productos</h6>
                    <button wire:click="cargarPresupuesto">Cargar</button>
                    <button wire:click="imprimirPresupuesto">Imprimir</button>
                </article>

                {{-- "id" => 1
                    "presupuesto_id" => 1
                    "presupuesto_numero" => 1
                    "codigo" => "9011861455640"
                    "detalle" => "Toallitas húmedas prueba porcentaje"
                    "precio" => 1161.6
                    "iva" => 21.0
                    "cantidad" => 1.0
                    "rubro" => "General"
                    "proveedor" => "moure deb"
                    "controlStock" => "no"
                    "fecha" => "2024-06-23"
                    "tipoComp" => "presupuesto"
                    "idFormaPago" => 3
                    "usuario" => "Javier Desmaret"
                    "empresa_id" => 1
              ] --}}

              <div class="overflow-auto">
                <table class="striped">
                    <thead>
                      <tr>
                        <th scope="col">Codigo</th>
                        <th scope="col">Detalle</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Precio</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $p)
                            <tr>
                                <td>{{$p->codigo}}</td>
                                <td>{{$p->detalle}}</td>
                                <td>{{$p->cantidad}}</td>
                                <td>${{$p->precio}}</td>



                                
                            </tr>
                  
                        @endforeach

                    </tbody>

                    <tfoot>
                        <tr>
                          <th scope="row"></th>
                          <td>Total:</td>
                          <td colspan="2" style="text-align: right;">${{$total}}</td>
 
                        </tr>
                      </tfoot>

                  </table>
              </div>

            </div>{{-- LISTA DE PRODUCTOS --}}



        </div>

    </div>
</div>
