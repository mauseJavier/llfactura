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




    <div class="container" x-data="{ valor1:{{$total}}, valor2: {{$importeUno}}, valor3: {{$importeDos}} , valor4: 0,
                                            formaPagoSeleccionado: 'NO',
                                            ajustarImporteDos() {
                                             if (this.formaPagoSeleccionado === 'NO') {
                                                 this.valor3 = 0;
                                             }
                                         } }">


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
                        id="total"
                        style="text-align: center; font-size:50px; width: 80%; height: 80%" 

                        type="text" 
                        pattern="^\d*\.?\d*$" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" 
                        title="Solo se permiten números y decimales con punto"

                        x-ref="inputText" 
                        x-model.number="valor1"
                        x-on:focus="$refs.inputText.select()" 
                        x-on:blur="event.target.value = formatCurrency(event.target.value)"
                        placeholder="$0,00"
                        wire:model="total"
                        wire:keydown.enter="facturar"
                        wire:keyup="igualarTotalImporteUno"

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


        <article styleee="transform: scale(0.7); transform-origin: top center;">

            <div
                x-data="{
                    handleKeyPress(event) {
                        const select = document.getElementById('idFormaPago');
                        const selectFactura = document.getElementById('selectFormaPago');


                        const button = document.getElementById('btnFacturar');
                        
                        switch(event.key.toUpperCase()) {
                            case 'E':
                                select.value = '1';
                                break;
                            case 'M':
                                select.value = '3';
                                break;
                            case 'T':
                                select.value = '2';
                                break;
                            case 'F':
                                select.value = '5';
                                break;

                            case 'A':
                                selectFactura.value = '1';
                                break;
                            case 'B':
                                selectFactura.value = '6';
                                break;
                            case 'C':
                                selectFactura.value = '11';
                                break;
                            case 'R':
                                selectFactura.value = 'remito';
                                break;
                            case 'P':
                                selectFactura.value = 'presupuesto';
                                break;


                            case 'ENTER':
                                button.click(); // Presiona el botón al presionar Enter
                                break;
                        }


                        
                        select.dispatchEvent(new Event('change')); // Para activar el evento de cambio en Livewire
                        selectFactura.dispatchEvent(new Event('change')); // Para activar el evento de cambio en Livewire

                    }
                }"
                x-on:keydown.window="handleKeyPress($event)"
            >
            </div>
            


            <div class="grid" style="text-align: center;">
                <div x-data="{ mostrarFormaPago: false }"> <!-- Declaramos una variable de estado -->
            
                    <label for="">
                        Forma de Pago 1
                        <select id="idFormaPago" aria-label="" wire:model.live="idFormaPago" wire:change="modificarFormaPago2()">         
                            @foreach ($formaPago as $item)
                                @if ($item->id !== 0) 
                                    {{-- PARA QUE NO MUESTRE EL CUENTA CORRIENTE DE LA BASE --}}
                                    <option value="{{$item->id}}">{{$item->nombre}}</option>
                                @endif
                            @endforeach
            
                            @if ($cuit !== 0) 
                                {{-- SI ESTA CARGADO UN CLIENTE SE PUEDE ASIGNAR EL SALDO A LA CUENTA CORRIENTE --}}
                                <option value="0">Cuenta Corriente</option>                                
                            @endif
                        </select>
                    </label>
            
                    <fieldset>
                        <label>
                            <input id="activarFormaDePago" type="checkbox" role="switch" x-model="mostrarFormaPago" />
                            Forma de Pago 2?
                        </label>
                    </fieldset>
            
                    <!-- Usamos x-show para mostrar u ocultar el contenido -->
                    <div id="contenidoFormaPago" x-show="mostrarFormaPago" style="display: none;"> 
                        <label for="">
                            Pago 1
                            <fieldset role="group">
                                <input 
                                    id="importeUno"
                                    wire:model="importeUno"
                                    style="text-align: center; font-size:45px;" 
                                    type="number" step="0.01"                                    
                                    x-model.number="valor2" 
                                    x-on:focus="$refs.inputText1.select()"   
                                    x-ref="inputText1"  
                                    x-on:blur="event.target.value = formatCurrency(event.target.value)"
                                />
                                <input type="button" value="Total" wire:click="igualarTotal()"  x-on:click="formaPagoSeleccionado = 'NO'"
                                />
                            </fieldset>
                            @error('importeUno')
                                <small style="color:red;" id="invalid-helper">
                                    {{ $message }}                          
                                </small>
                            @enderror
                        </label>
            
                        <label >
                            Forma de Pago 2
                            <select 
                                id="selectFormaPagoDos"
                                aria-label="" 
                                wire:model.live="idFormaPago2"
                                x-model="formaPagoSeleccionado"
                                @change="ajustarImporteDos"
                            >   
                                <option value="NO" selected>NO</option>
                                @foreach ($formaPago2 as $item)
                                    @if ($item->id !== 0)
                                        <option value="{{$item->id}}">{{$item->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </label>
            
                        <label for="">
                            Pago 2
                            <input   
                                wire:model="importeDos"
                                style="text-align: center; font-size:45px;" 
                                type="number" step="0.01"                                    
                                id="importeDos" 
                                x-model.number="valor3" 
                                x-ref="inputText2"
                                x-on:focus="$refs.inputText2.select()"   
                                x-on:blur="event.target.value = formatCurrency(event.target.value)"
                                :disabled="formaPagoSeleccionado === 'NO'"
                            >
                            @error('importeDos')
                                <small style="color:red;" id="invalid-helper">
                                    {{ $message }}                          
                                </small>
                            @enderror
                        </label>


                    </div>
                </div>
            </div>

            



    
            {{-- <div style="text-align: center; height: 100%; display: flex; align-items: center; justify-content: center; ">
                <p :style="(valor1 - (valor2 + valor3)) > 0 ? 'color:red; font-size: 45px; margin: 0;' : 'color:green; font-size: 45px; margin: 0;'" >
                    <span x-text="(valor1 -(valor2 + valor3)) > 0 ? 'Falta $'+(valor1 - (valor2 + valor3)).toFixed(2) : 'Vuelto $'+(valor1 - (valor2 + valor3)).toFixed(2)"></span>
                </p>
            </div> --}}

            <div style="text-align: center; height: 100%; display: flex; align-items: center; justify-content: center;">
                <p 
                    :style="(valor1 - (valor2 + valor3)) > 0 ? 'color:red; font-size: 45px; margin: 0;' : 'color:green; font-size: 45px; margin: 0;'" 
                >
                    <span x-text="(valor1 - (valor2 + valor3)) > 0 ? 'Falta $' + (valor1 - (valor2 + valor3)).toFixed(2) : 'Vuelto $' + (valor1 - (valor2 + valor3)).toFixed(2)"></span>
                </p>
            </div>

        </article>


        <article wire:loading>
            <span aria-busy="true">Procesando la Informacion...</span>
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
                            @isset($carrito['carrito'])
                            <option value="presupuesto">Presupuesto</option>               
                            @endisset
                            <option value="remito">Remito</option>
                        </select>
                        <small id="invalid-helper">@error('cuit') {{ $message }} @enderror </small>
                        

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




