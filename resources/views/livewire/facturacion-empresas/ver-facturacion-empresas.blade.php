<div>

    <div class="container-fluid">
    
        <h3>Facturacion Empresas</h3>

        {{-- 0 => {#1613 ▼
            +"id": 1
            +"tipoComp": "11"
            +"numero": 132
            +"total": 295.58
            +"cae": 74262179865185
            +"fechaVencimiento": "2024-07-07"
            +"fecha": "2024-06-27 16:54:38"
            +"ptoVta": 4
            +"deposito_id": 1
            +"DocTipo": 99
            +"cuitCliente": 0
            +"razonSocial": "Consumidor Final"
            +"tipoContribuyente": 5
            +"domicilio": null
            +"leyenda": null
            +"idFormaPago": 1
            +"remito": "no"
            +"empresa_id": 1
            +"usuario": "MAUSE LLFACTURA"
            +"created_at": "2024-06-27 16:54:38"
            +"updated_at": "2024-06-27 16:54:38"
            +"empresa_razonSocial": "Empresa Prueba"
            +"deposito_nombre": "General"
            +"forma_pago_nombre": "Efectivo"
          } --}}

          <article>
            <div class="grid">
                <div class="col">

                    <fieldset class="grid">
                        <select name="favorite-cuisine"  wire:model.live="razonSocial">
                            <option value="">Todo</option>
                            @foreach ($empresas as $item)
                                <option value="{{$item->razonSocial}}">{{$item->razonSocial}}</option>
                            @endforeach
    
                          </select>
                          <input type="text" wire:model.live="razonSocial" data-tooltip="Filtro Empresa RazonSocial CUIT" placeholder="Filtro Empresa RazonSocial CUIT">
                      </fieldset>




                </div>
                <div class="col">
                    Fecha Desde:
                    <input type="date" name="date" aria-label="Date" wire:model.live="fechaDesde"  data-tooltip="Fecha Desde">

                </div>
            </div>
          </article>


        <div class="overflow-auto">
            <table wire:poll >
                <thead>
                <tr>
                    <th scope="col" style="color: red;">id</th>
                    <th scope="col" style="color: red;">createdAT</th>
                    <th scope="col" style="color: red;">empresa</th>
                    <th scope="col" style="color: red;">usuario</th>
                    <th scope="col" style="color: red;">tipoComp</th>
                    <th scope="col" style="color: red;">numero</th>
                    <th scope="col" style="color: red;">total</th>
                    <th scope="col" style="color: red;">cae</th>
                    <th scope="col" style="color: red;">vencimiento</th>
                    <th scope="col" style="color: red;">fecha</th>
                    <th scope="col" style="color: red;">ptoVta</th>
                    <th scope="col" style="color: red;">deposito</th>
                    <th scope="col" style="color: red;">cliente</th>
                    <th scope="col" style="color: red;">cuitCliente</th>
                    <th scope="col" style="color: red;">formaPago</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($comprobantes as $key => $c)                        
                        <tr>
                            <th scope="row">{{$c->id}}</th>
                            <th scope="row">{{$c->created_at}}</th>
                            <th scope="row">{{$c->empresa_razonSocial}}</th>
                            <th scope="row">{{$c->usuario}}</th>
                            <th scope="row">{{$c->tipoComp}}</th>
                            <th scope="row">{{$c->numero}}</th>
                            <th scope="row">{{$c->total}}</th>
                            <th scope="row">{{$c->cae}}</th>
                            <th scope="row">{{$c->fechaVencimiento}}</th>
                            <th scope="row">{{$c->fecha}}</th>
                            <th scope="row">{{$c->ptoVta}}</th>
                            <th scope="row">{{$c->deposito_nombre}}</th>
                            <th scope="row">{{$c->razonSocial}}</th>
                            <th scope="row">{{$c->cuitCliente}}</th>
                            <th scope="row">{{$c->forma_pago_nombre}}</th>
                        </tr>
                        @if (is_int($key / 5) AND $key/5>0)

                        <thead>
                            <tr>
                                <th scope="col" style="color: red;">id</th>
                                <th scope="col" style="color: red;">createdAT</th>
                                <th scope="col" style="color: red;">empresa</th>
                                <th scope="col" style="color: red;">usuario</th>
                                <th scope="col" style="color: red;">tipoComp</th>
                                <th scope="col" style="color: red;">numero</th>
                                <th scope="col" style="color: red;">total</th>
                                <th scope="col" style="color: red;">cae</th>
                                <th scope="col" style="color: red;">vencimiento</th>
                                <th scope="col" style="color: red;">fecha</th>
                                <th scope="col" style="color: red;">ptoVta</th>
                                <th scope="col" style="color: red;">deposito</th>
                                <th scope="col" style="color: red;">cliente</th>
                                <th scope="col" style="color: red;">cuitCliente</th>
                                <th scope="col" style="color: red;">formaPago</th>
                            </tr>
                            </thead>
                            
                        @endif
                    @endforeach

                </tbody>

            </table>
        </div>
        {{ $comprobantes->links('vendor.livewire.bootstrap') }}

    </div>
</div>
