<div>
    <div class="container">
        <h3>Facturar Remito N° {{$comp->numero}} </h3>

        <article wire:loading>
            <span aria-busy="true">Procesando la Informacion...</span>
        </article>


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

                    <label for="">
                        Pago Uno: {{$comp->importeUno}}
                    </label>                    
                    <label for="">
                        Pago Dos: {{$comp->importeDos}}
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
                        FP Uno: {{$fpUno->nombre}}
                    </label>
                    <label for="">
                        FP Dos: {{$fpDos->nombre}}
                    </label>

                    <label for="">
                        Usuario: {{$comp->usuario}}
                    </label>




                    

                </div>
            </div>

        </article>

        <article wire:loading.remove x-data="{ buttonText: 'Finalizar' }" style="width: 100%">
            {{-- ////////// BOTONES DE LA FACTURACION --}}
            <div class="grid" style="text-align: center;" >
                <div>
                    <button id="btnFacturar" class="outline" style="width: 100%" x-text="buttonText"
                        wire:click="facturar">
                    </button>
                </div>
                <div>
                        <select id="selectFormaPago" name="" aria-label=""  required wire:model.live="tipoComprobante" 
                            {{-- ESTA FUNCION DE ALPINE ES PARA CAMBIAR EL NOMBRE DEL BOTON FINALIZAR --}}
                            @change="buttonText = 'Finalizar ' + $event.target.options[$event.target.selectedIndex].text"

                        @if ($errors->has('cuit'))
                            aria-invalid="true"
                        @else  
                            aria-invalid="" 
                        @endif   
                        
                        >                                  
                            @if ($empresa->iva == 'ME' AND $empresa->fe == 'si')
                                <option value="11">Factura C</option>
                            @endif                            
                            @if ($empresa->iva == 'RI' AND $empresa->fe == 'si')
                                <option value="6">Factura B</option>
                                <option value="1">Factura A</option>   
                                <option value="51">Factura M</option>                        
                     
                            @endif      
                            @if ($empresa->razonSocial == 'Empresa Prueba' )
                                <option value="11">Factura C</option>
                                <option value="6">Factura B</option>
                                <option value="1">Factura A</option>    
                                <option value="51">Factura M</option>                                
                            @endif   

                        </select>
                        <small id="invalid-helper">@error('cuit') {{ $message }} @enderror </small>
                        

                </div>

            </div>
            {{-- ////////// BOTONES DE LA FACTURACION --}}
        </article>


    </div>


</div>
