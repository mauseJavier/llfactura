<div>
    {{-- Do your work, then step back. --}}

    {{-- <div class="container" wire:loading>
        <div style="text-align: center;">
            <h1><span aria-busy="true">Cargando Datos....</span></h1>
        </div>
    </div> --}}

    {{-- @dump($codigo)
    @dump($detalle)
    @dump($rubro)
    @dump($proveedor)
    @dump($marca)
    @dump($ivaDefecto) --}}






    <div class="container" x-data="{ valor1:{{$total}}, valor2: 0 }">

        @if (\Session::has('mensaje'))
        <article>
            <ul>
                <li>{!! \Session::get('mensaje') !!}</li>
            </ul>
        </article>
        @endif

        {{-- //////ESTE ES EL GRID DE LOS FAVORITOS  --}}
        {{-- @dump($carrito) --}}
        @if ($carrito == null)            
            @foreach ($favoritos as $item)
                @if ($loop->index % 3 == 0)
                    @if (!$loop->first)
                        </div> <!-- Cierra la grid anterior -->
                    @endif
                    <div class="grid"> <!-- Abre una nueva grid cada 6 elementos -->
                @endif
            
                <div class="col">
                    <article 
                        x-data="{ codigo: '{{$item->codigo}}' }"
                        :style="codigo == '{{$codigo}}' ? 'background-color: red; text-align: center;' : 'background-color: green; text-align: center;'" 
                        wire:click="cargarFavorito(
                                                    '{{$item->codigo}}',
                                                    '{{$item->detalle}}',
                                                    '{{$item->rubro}}',
                                                    '{{$item->proveedor}}',
                                                    '{{$item->marca}}',
                                                    '{{$item->iva}}')"
                        >
                        {{$item->detalle}}
                    </article>
                </div>
            
                @if ($loop->last)
                    </div> <!-- Cierra la última grid -->
                @endif
            @endforeach
        @endif
    
        {{-- //////ESTE ES EL GRID DE LOS FAVORITOS  --}}

        <article style="text-align: center;" >

            <div class="grid">

                <div x-data="{ 
                        formatCurrency(value) { 
                            let number = parseFloat(value.replace(/,/g, ''));
                            if (isNaN(number)) return '';
                            return new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' }).format(number); 
                        } 
                    }">

                    <input 
                        style="text-align: center; font-size:50px; width: 80%; height: 80%" 
                        type="text" 
                        x-ref="inputText" 
                        x-model.number="valor1"
                        x-on:focus="$refs.inputText.select()" 
                        x-on:blur="event.target.value = formatCurrency(event.target.value)"
                        placeholder="$0,00"
                        wire:model="total"
                        wire:keydown.enter="facturar"
                        {{$modificarImporte}}
    
                        @if ($errors->has('total'))
                            aria-invalid="true"
                        @else  
                            aria-invalid="" 
                        @endif
    
                    >
                        @error('total')
                            <small id="invalid-helper">
                                {{ $message }}                          
                            </small>
                         @enderror
                </div>



            </div>

            

            
            

        </article>

        @if ($idFormaPago == 1)
                    

                    <div class="grid" style="text-align: center; transform: scale(0.7); transform-origin: top center;">
                        <div>
                            <label for="input2">Efectivo <small>(Calcular Vuelto)</small></label>
                            <input   
                                style="text-align: center; font-size:45px;" 
                                type="text" id="input2" x-model.number="valor2" x-on:focus="$refs.inputText.select()"   x-ref="inputText"  
                                x-on:blur="event.target.value = formatCurrency(event.target.value)"
                            >
                        </div>
                        
                        <div style="text-align: center; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <p :style="(valor1 - valor2) > 0 ? 'color:red; font-size: 45px; margin: 0;' : 'color:green; font-size: 45px; margin: 0;'" >
                                <span x-text="(valor1 - valor2) > 0 ? 'Falta $'+(valor1 - valor2).toFixed(2) : 'Vuelto $'+(valor1 - valor2).toFixed(2)"></span>
                            </p>
                        </div>

                    </div>

                
            @endif

        <article wire:loading>
            <span aria-busy="true">Procesando la Informacion...</span>
        </article>



        <article wire:loading.remove x-data="{ buttonText: 'Finalizar' }" style="width: 100%">
            {{-- ////////// BOTONES DE LA FACTURACION --}}
            <div class="grid" style="text-align: center;" >
                <div>
                    <button class="outline" style="width: 100%" x-text="buttonText"
                        wire:click="facturar">
                    </button>
                </div>
                <div>
                        <select name="" aria-label=""  required wire:model.live="tipoComprobante" 
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
                            @isset($carrito['carrito'])
                            <option value="presupuesto">Presupuesto</option>               
                            @endisset
                            <option value="remito">Remito</option>
                        </select>
                        <small id="invalid-helper">@error('cuit') {{ $message }} @enderror </small>
                        

                </div>
                <div>

                        <select name="" aria-label="" wire:model.live="idFormaPago">         
                            @foreach ($formaPago as $item)
                                @if ($item->id !== 0) 
                                    {{-- PARA QUE NO MUESTRE EL CUENTA CORRIENTE DE LA BASE                               --}}
                                    <option value="{{$item->id}}">{{$item->nombre}}</option>
                                @endif
                            @endforeach

                            @if ($cuit !== 0) 
                            {{-- SI ESTA CARGADO UN CLIENTE SE PUEDE ASIGNAR EL SALDO A LA CUENTA CORRIENTE  --}}
                                <option value="0">Cuenta Corriente</option>                                
                            @endif

                        </select>
                        

                </div>
            </div>
            {{-- ////////// BOTONES DE LA FACTURACION --}}
        </article>



        <article style="text-align: center; width: 100%;" wire:loading.remove>
            <div class="grid">

                <div class="col">
                    <fieldset style="width: 100%;   display: flex; justify-content: center; align-items: center;">
                        <label>                      
                            <h4>Imprimir?</h4>
                            <input name="" type="checkbox" @checked($imprimir) role="switch" wire:model="imprimir" />
                        </label>
                    </fieldset>

                </div>         

                <div>
                    <fieldset>
                        <label for="">
                            Fecha de Comprobante
                            <input style="text-align: center;" type="date" name="" id="" wire:model="fechaHoy" min="{{$fechaMin}}" max="{{$fechaMax}}">
                        </label>
                    </fieldset>
                </div>
                <div>
                    <label for="">
                        Tipo Contribuyente
                        <select name="" aria-label=""  required wire:model="tipoContribuyente">       

                                <option value="5">Consumidor Final</option>
                                <option value="13">Monotributista</option>
                                <option value="6">Responsable Inscripto</option>
                                <option value="4">Exento</option>
                        
                        </select>
                        
                    </label>
                </div>


            </div>            
        </article>

        <article>

            {{-- //////////////// --}}
            <div class="grid">

                <div>
                    <livewire:Factura.Clientes :empresa_id="$empresa->id" wire:model="razonSocial"/>
                </div>


                <div x-data>
                    <label for="">Numero de Documento <small style="color: red">(Buscar para Cuenta Corriente)</small></label>
                    <fieldset role="group">                       

                        <input  
                            wire:model="cuit" 
                            {{-- wire:focusout="buscarCuit" --}}
                            wire:keydown.enter="buscarCliente"
                            {{-- wire:focusout="buscarCliente" por si no queda bien que rederise a cada rato--}}
                            {{-- wire:keyup ="buscarCliente" --}}
                            maxlength="11" 
                            style="width:100%"    
                            name="cuit"    
                            placeholder="Numero de Documento"
                            autocomplete="cuit"
                            x-on:click="event.target.select()"

                        
                            @if ($errors->has('cuit'))
                                aria-invalid="true"
                            @else  
                                aria-invalid="" 
                            @endif                   

                        />      
                        
                        <button wire:click="buscarCliente"><i class="fa-solid fa-magnifying-glass"></i></button>
                        
                        </fieldset>
                        <small id="invalid-helper">@error('cuit') {{ $message }} @enderror </small>                  
                </div>
                                
            </div>

            <div class="grid">

                <label for="">
                    Tipo de Docuemento
                    <select name="" aria-label=""  required wire:model="tipoDocumento">         
                        {{-- *
                        * 80 = CUIT 
                        * 86 = CUIL 
                        * 96 = DNI
                        * 99 = Consumidor Final                    
                            --}}
                        <option value="99">Consumidor Final</option>
                        <option value="80">CUIT</option>
                        <option value="96">DNI</option>
                        <option value="86">CUIL</option>
                    </select>
                    
                </label>
                    
                

                <label for="">
                    Domicilio
                    <input 
                    type="text"
                    name="domicilio"
                    placeholder="Domicilio"
                    aria-label="Domicilio"
                    autocomplete="domicilio"
                    wire:model="domicilio"                     
                    />
                </label>

            </div>

        </article>

        
        <article>
            <details>
               
                <summary>Mas Datos</summary>
                    <fieldset class="grid">

                        <label for="">
                            Correo
                            <input
                            type="text"
                            name="email"
                            placeholder="Correo"
                            aria-label="Correo"
                            autocomplete="email"
                            wire:model="correoCliente"
                            />
                        </label>
                    </fieldset>

                    <fieldset class="grid">
                        <label for="">
                            Leyenda
                            <input
                            type="text"
                            name="leyenda"
                            placeholder="Leyenda"
                            aria-label="Leyenda"
                            autocomplete="leyenda"
                            wire:model="leyenda"
                            />
                        </label>
    
    
    
                        <label for="">
                            Remito?
                            <select name="" aria-label=""  required wire:model="remitoEntrega">         
                                
                                <option value="no">NO (ENTREGA EN EL MOMENTO)</option>
                                <option value="si">SI (ENTREGA POSTERIOR)</option>
                                
                            </select>
                            
                        </label>
    
                    </fieldset>

              </details>
              
        </article>
    </div>

 
</div>




