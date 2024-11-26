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


    <div class="container" x-data="{ valor1:{{$total}}, valor2: {{$importeUno}}, valor3: {{$importeDos}} , valor4: 0,valor5: 0,
                                            formaPagoSeleccionado: 'NO',
                                            ajustarImporteDos() {
                                                    if (this.formaPagoSeleccionado === 'NO') {
                                                        this.valor3 = 0;
                                                    }
                                                },
                                                 handleKeyPress(event) {
                                                    const select = document.getElementById('idFormaPago');
                                                    const selectDuplicado = document.getElementById('idFormaPagoDuplicado');

                                                    const selectFactura = document.getElementById('selectFormaPago');


                                                    const button = document.getElementById('btnFacturar');
                                                    
                                                    switch(event.key.toUpperCase()) {
                                                        case 'E':
                                                            select.value = '1';
                                                            selectDuplicado.value = '1';
                                                            break;
                                                        case 'M':
                                                            select.value = '3';
                                                            selectDuplicado.value = '3';
                                                            break;
                                                        case 'T':
                                                            select.value = '2';
                                                            selectDuplicado.value = '2';
                                                            break;
                                                        case 'F':
                                                            select.value = '5';
                                                            selectDuplicado.value = '5';
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


        <article x-data="{ mostrarFormaPago: false }"> <!-- Declaramos una variable de estado -->
                     


            <div class="" style="text-align: center;">
                <div > 
            
                    <label for="" x-show="!mostrarFormaPago">
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
                            <input id="activarFormaDePago" type="checkbox" role="switch" x-model="mostrarFormaPago"
                            wire:click="igualarTotal()"  x-on:click="formaPagoSeleccionado = 'NO'"
                             />
                            Dos Formas de Pago
                        </label>
                    </fieldset>
            
                    <!-- Usamos x-show para mostrar u ocultar el contenido -->
                    <div id="contenidoFormaPago" x-show="mostrarFormaPago" style="display: none;"> 

                        <div class="grid">

                            <div class="col">


                                <label for="" >
                                    Forma de Pago 1
                                    <select style="background-color: rgb(95, 123, 98)" id="idFormaPagoDuplicado" aria-label="" wire:model.live="idFormaPago" wire:change="modificarFormaPago2()">         
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

                                <label for="">
                                    Pago 1
                                    <fieldset role="group">
                                        <input 
                                        
                                            id="importeUno"
                                            wire:model.live="importeUno"
                                            style="text-align: center; font-size:45px; background-color: rgb(95, 123, 98);" 
                                            type="number" step="0.01" min="0.01"                         
                                            x-model.number="valor2" 
                                            x-on:focus="$refs.inputText1.select()"   
                                            x-ref="inputText1"  
                                            {{-- x-on:blur="event.target.value = formatCurrency(event.target.value)" --}}
                                        />

                                        <input type="button" value="Total" wire:click="igualarTotal()"  x-on:click="formaPagoSeleccionado = 'NO'"/>

                                    </fieldset>
                                    @error('importeUno')
                                        <small style="color:red;" id="invalid-helper">
                                            {{ $message }}                          
                                        </small>
                                    @enderror
                                </label>

                            </div>
                            <div class="col">


                                <label >
                                    Forma de Pago 2
                                    <select 

                                        style="background-color: rgb(80, 102, 134)"
                                        id="selectFormaPagoDos"
                                        aria-label="" 
                                        wire:model.live="idFormaPago2"
                                        x-model="formaPagoSeleccionado"
                                        @change="ajustarImporteDos"
                                    >   
                                        {{-- <option value="NO" selected>NO</option> --}}
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
                                        {{-- wire:model="total - importeUno" --}}
                                        style="text-align: center; font-size:45px; background-color: rgb(80, 102, 134);" 

                                        type="texto"                                   
                                        id="importeDos" 
                                        x-model.number="{{round( $total - floatVal($importeUno),2)}}" 
                                        x-ref="inputText2"
                                        x-on:focus="$refs.inputText2.select()"   
                                        x-on:blur="event.target.value = formatCurrency(event.target.value)"
                                        {{-- :disabled="formaPagoSeleccionado === 'NO'" --}}
                                        disabled
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
                </div>
            </div>

            



    
            {{-- <div style="text-align: center; height: 100%; display: flex; align-items: center; justify-content: center; ">
                <p :style="(valor1 - (valor2 + valor3)) > 0 ? 'color:red; font-size: 45px; margin: 0;' : 'color:green; font-size: 45px; margin: 0;'" >
                    <span x-text="(valor1 -(valor2 + valor3)) > 0 ? 'Falta $'+(valor1 - (valor2 + valor3)).toFixed(2) : 'Vuelto $'+(valor1 - (valor2 + valor3)).toFixed(2)"></span>
                </p>
            </div> --}}

            {{-- <div style="text-align: center; height: 100%; display: flex; align-items: center; justify-content: center;">
                <p 
                    :style="(valor1 - (valor2 + valor3)) > 0 ? 'color:red; font-size: 45px; margin: 0;' : 'color:green; font-size: 45px; margin: 0;'" 
                >
                    <span x-text="(valor1 - (valor2 + valor3)) > 0 ? 'Falta $' + (valor1 - (valor2 + valor3)).toFixed(2) : 'Vuelto $' + (valor1 - (valor2 + valor3)).toFixed(2)"></span>
                </p>
            </div> --}}

            <hr>

            <div class="grid" x-show="!mostrarFormaPago">
                <div class="col">
                    <label for="">
                        Entrega
                        <input 
                            id="importeCinco"
                            style="text-align: center; font-size:45px;" 
                            type="texto"                                   
                            x-model.number="valor5" 
                            x-on:focus="$refs.inputText5.select()"   
                            x-ref="inputText5"  
                            x-on:blur="event.target.value = formatCurrency(event.target.value)"
                        />
                    </label>

                </div>
                <div class="col">

                    <div style="text-align: center; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <p 
                            :style="(valor5 - (valor1)) > 0 ? 'color:red; font-size: 45px; margin: 0;' : 'color:green; font-size: 45px; margin: 0;'" 
                        >
                            <span x-text="(valor5 - (valor1)) > 0 ? 'Vuelto $' + (valor5 - (valor1)).toFixed(2) : 'Falta $' + (valor5 - (valor1)).toFixed(2)"></span>
                        </p>
                    </div>

                </div>
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
                            {{-- wire:keydown.enter="buscarCliente" --}}
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
                        
                        <button wire:click="buscarCliente">
                            <!-- magnifying-glass icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                                <path
                                d="M 398.44357976653697 207.19066147859922 Q 398.44357976653697 155.3929961089494 372.54474708171205 111.56420233463035 L 372.54474708171205 111.56420233463035 L 372.54474708171205 111.56420233463035 Q 347.6420233463035 67.73540856031128 302.8171206225681 41.83657587548638 Q 257.9922178988327 15.937743190661479 207.19066147859922 15.937743190661479 Q 156.38910505836577 15.937743190661479 111.56420233463035 41.83657587548638 Q 66.73929961089495 67.73540856031128 41.83657587548638 111.56420233463035 Q 15.937743190661479 155.3929961089494 15.937743190661479 207.19066147859922 Q 15.937743190661479 258.988326848249 41.83657587548638 302.8171206225681 Q 66.73929961089495 346.6459143968872 111.56420233463035 372.54474708171205 Q 156.38910505836577 398.44357976653697 207.19066147859922 398.44357976653697 Q 257.9922178988327 398.44357976653697 302.8171206225681 372.54474708171205 Q 347.6420233463035 346.6459143968872 372.54474708171205 302.8171206225681 Q 398.44357976653697 258.988326848249 398.44357976653697 207.19066147859922 L 398.44357976653697 207.19066147859922 Z M 347.6420233463035 359.5953307392996 Q 290.863813229572 412.3891050583658 207.19066147859922 414.38132295719845 Q 119.53307392996109 412.3891050583658 60.762645914396884 353.61867704280155 Q 1.9922178988326849 294.84824902723733 0 207.19066147859922 Q 1.9922178988326849 119.53307392996109 60.762645914396884 60.762645914396884 Q 119.53307392996109 1.9922178988326849 207.19066147859922 0 Q 294.84824902723733 1.9922178988326849 353.61867704280155 60.762645914396884 Q 412.3891050583658 119.53307392996109 414.38132295719845 207.19066147859922 Q 412.3891050583658 290.863813229572 359.5953307392996 347.6420233463035 L 508.01556420233464 496.0622568093385 L 508.01556420233464 496.0622568093385 Q 512 502.03891050583655 508.01556420233464 508.01556420233464 Q 502.03891050583655 512 496.0622568093385 508.01556420233464 L 347.6420233463035 359.5953307392996 L 347.6420233463035 359.5953307392996 Z"
                                />
                            </svg>

                        </button>
                        
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




