<div>
    {{-- Do your work, then step back. --}}

    {{-- <div class="container" wire:loading>
        <div style="text-align: center;">
            <h1><span aria-busy="true">Cargando Datos....</span></h1>
        </div>
    </div> --}}
    <div class="container" >

        @if (\Session::has('mensaje'))
        <article>
            <ul>
                <li>{!! \Session::get('mensaje') !!}</li>
            </ul>
        </article>
        @endif

        <article style="text-align: center;">

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
                    x-on:focus="$refs.inputText.select()" 
                    x-on:blur="event.target.value = formatCurrency(event.target.value)"
                    placeholder="$0,00"
                    wire:model="total"
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
            
        </article>

        <article wire:loading>
            <span aria-busy="true">Procesando la Informacion...</span>
        </article>



        <article wire:loading.remove x-data="{ buttonText: 'Finalizar Facturar' }">
            {{-- ////////// BOTONES DE LA FACTURACION --}}
            <div class="grid" style="text-align: center;" >
                <div>
                    <button class="outline" style="width: 100%" x-text="buttonText"
                        wire:click="facturar">
                    </button>
                </div>
                <div>
                        <select name="" aria-label=""  required wire:model.blur="tipoComprobante" @change="buttonText = 'Finalizar ' + $event.target.options[$event.target.selectedIndex].text"

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
                            @endif      
                            @if ($empresa->razonSocial == 'Empresa Prueba' )
                                <option value="11">Factura C</option>
                                <option value="6">Factura B</option>
                                <option value="1">Factura A</option>                                  
                            @endif   
                            @isset($carrito['carrito'])
                            <option value="presupuesto">Presupuesto</option>               
                            @endisset
                            <option value="remito">Remito</option>
                        </select>
                        <small id="invalid-helper">@error('cuit') {{ $message }} @enderror </small>
                        

                </div>
                <div>

                        <select name="" aria-label=""  required wire:model="idFormaPago">         
                            @foreach ($formaPago as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        

                </div>
            </div>
            {{-- ////////// BOTONES DE LA FACTURACION --}}
        </article>



        <article style="text-align: center;" wire:loading.remove>
            <div class="grid">

                <div class="col">
                    <fieldset style="width: 100%;   display: flex; justify-content: center; align-items: center;">
                        <label>                      
                            <h4>Imprimir?</h4>
                            <input name="" type="checkbox" role="switch" wire:model="imprimir" />
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
                    <label for="">Numero de Documento</label>
                    <fieldset role="group">                       

                        <input  
                            wire:model ="cuit" 
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




