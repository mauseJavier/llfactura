<div>
    <div class="container">
        

      <div>

          <progress wire:loading />   
      </div>



        <article wire:loading.remove >
                <header>
                    <h3>Comprobante N° {{$comprobante->numero}}</h3>
                    
                </header>

                <div class="grid">
                    <div class="col">
                        @switch($comprobante->tipoComp)
                            @case(1)
                                <label for="">Tipo: Factura A</label>
                                @break
                                @case(6)
                                <label for="">Tipo: Factura B</label>
                                @break
                                @case(11)
                                <label for="">Tipo: Factura C</label>
                                @break
                                @case('remito')
                                <label for="">Tipo: Remito</label>
                                @break
                            @default

                        @endswitch

                        <label for="">Importe: ${{$comprobante->total}}</label>
                        <label for="">Fecha: {{$comprobante->fecha}}</label>
                        <label for="">Deposito: {{$deposito->nombre}}</label>
                        <label for="">Usuario: {{$comprobante->usuario}}</label>




                    </div>
                    <div class="col">
                        <label for="">CAE: {{$comprobante->cae}}</label>

                        <label for="">Ven. CAE: {{$comprobante->fechaVencimiento}}</label>
                        <label for="">Pto. Venta: {{$comprobante->ptoVta}}</label>
                        <label for="">Forma Pago: {{$formaPago->nombre}}</label>




                        

                    </div>
                </div>
                <br>

                <h4>Datos Cliente</h4>
                <hr>
                <div class="grid">
                    <div class="col">
                        <label for="">Cuit Cliente: {{$comprobante->cuitCliente}}</label>
                        <label for="">Razon Social: {{$comprobante->razonSocial}}</label>
                        <label for="">Domicilio: {{$comprobante->domicilio}}</label>
                        <label for="">Leyenda: {{$comprobante->leyenda}}</label>




                    </div>
                </div>



                <footer>
                    <div class="grid">
                        <div class="col">

                            <label for="">
                                Comentario
                                <input wire:model="comentario" placeholder="Comentario" type="text" name="" id="">
                            </label>
                            <div class="grid">
                                <div class="col">

                                    <label>
                                        <input wire:model="imprimir" type="checkbox" role="switch" />
                                        Imprimir?
                                      </label>
                                </div>
                                <div class="col">

                                    <button wire:click="notaCredito()" wire:confirm="Esta seguro?">Generar Nota de Credito</button>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <p style="color: red;">Esta Nota de credito ingresara los stocks de los articulos vendidos en el deposito {{$deposito->nombre}}.</p>
                        </div>
                    </div>
                </footer>
          </article>
        {{-- "id" => 16
        "tipoComp" => "remito"
        "numero" => 16
        "total" => 30.0
        "cae" => 0
        "fechaVencimiento" => "2024-06-22"
        "fecha" => "2024-06-22 11:12:35"
        "ptoVta" => 0
        "deposito_id" => 2
        "DocTipo" => 99
        "cuitCliente" => 0
        "razonSocial" => "Consumidor Final"
        "tipoContribuyente" => 5
        "domicilio" => null
        "leyenda" => null
        "idFormaPago" => 1
        "remito" => "no"
        "empresa_id" => 1
        "usuario" => "Javier Desmaret"
        "created_at" => "2024-06-22 11:12:35"
        "updated_at" => "2024-06-22 11:12:35"
      ] --}}
        
    </div>
</div>
