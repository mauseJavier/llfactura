<div x-data="{ modalNuevoCliente: false }">

    <div class="container" >
        <h3>Clientes</h3>

        <article>
            <div class="grid">


                <div class="col">
                        <fieldset role="group">
                            <input wire:model.live="datoBuscado" type="search" name="" id="" placeholder="Buscar Cliente">

                            <button @click="modalNuevoCliente = !modalNuevoCliente" data-tooltip="Nuevo Cliente" >
                                    <!-- plus-large icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                                    <path
                                    d="M 264 8 Q 263 1 256 0 Q 249 1 248 8 L 248 248 L 248 248 L 8 248 L 8 248 Q 1 249 0 256 Q 1 263 8 264 L 248 264 L 248 264 L 248 504 L 248 504 Q 249 511 256 512 Q 263 511 264 504 L 264 264 L 264 264 L 504 264 L 504 264 Q 511 263 512 256 Q 511 249 504 248 L 264 248 L 264 248 L 264 8 L 264 8 Z"
                                    />
                                </svg>
                            </button>


                        </fieldset>
                </div>
                
                {{-- <div class="col">
                    
                    <input wire:model.live="datoBuscado" type="search" name="" id="" placeholder="Buscar Cliente">
                </div>
                
                <div class="col" style="display: flex; justify-content: flex-start; align-items: center;">
                    <button @click="modalNuevoCliente = !modalNuevoCliente" data-tooltip="Nuevo Cliente" >
                            <!-- plus-large icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                            <path
                            d="M 264 8 Q 263 1 256 0 Q 249 1 248 8 L 248 248 L 248 248 L 8 248 L 8 248 Q 1 249 0 256 Q 1 263 8 264 L 248 264 L 248 264 L 248 504 L 248 504 Q 249 511 256 512 Q 263 511 264 504 L 264 264 L 264 264 L 504 264 L 504 264 Q 511 263 512 256 Q 511 249 504 248 L 264 248 L 264 248 L 264 8 L 264 8 Z"
                            />
                        </svg>
                    </button>
                </div> --}}

                @if ((Auth::user()->role_id == 3) OR (Auth::user()->role_id == 4))

                    <div class="col" style="text-align: right;">
                        Saldos Negativos.
                        <h3 style="color: red;">${{number_format($sumaSaldoNegativo, 2, ',', '.')}}</h3>
                    
                        <h6 style="color: green;">Pagos Mes actual: ${{number_format($sumaPagosMesActual, 2, ',', '.')}}</h6>
                    
                    </div>
                    
                @endif

            </div>
        </article>

        @if (session('mensaje'))
            <article>
                <p>
                    {{ session('mensaje') }}    
                </p>     
            </article>
        @endif
        @if (session('guardado'))
            <article>
                <p style="color: green;">
                    {{-- <strong>Pago Realizado</strong> --}}
                    {{ session('guardado') }}    
                </p>     
            </article>
        @endif

        @if (session('error'))
            <article>
                <p style="color: red;">
                    {{-- <strong>Pago Realizado</strong> --}}
                    {{ session('error') }}    
                </p>     
            </article>
        @endif


        <article>
            Ordenado Por: {{$ordenarPor}} <br>
            Orden: {{$ordenarDireccion == 'asc'? 'Ascendente' : 'Descendente' }} <br>
        </article>

        {{-- "id" => 1
        "tipoDocumento" => 99
        "tipoContribuyente" => 5
        "numeroDocumento" => 0
        "razonSocial" => "Consumidor Final"
        "domicilio" => null
        "correo" => null
        "empresa_id" => 1
        "created_at" => "2024-06-10 17:38:21"
        "updated_at" => "2024-06-10 17:38:21"
      ] --}}

      <div class="overflow-auto">
        <table class="striped">
            <thead>
              <tr>
                <th>Acciones</th>
                <th scope="col"><button  class="outline secondary" wire:click="ordenar('razonSocial')"
                    style="display: inline-block; min-width: 300px; text-align: center;">Razon Social</button>
                </th>
                <th scope="col">Tipo Doc</th>
                <th scope="col">Numero</th>
                <th scope="col">Tipo Contr.</th>
                <th scope="col">Telefono.</th>


                <th scope="col"><button  class="outline secondary" wire:click="ordenar('saldo')" 
                    style="display: inline-block; min-width: 300px; text-align: center;">Saldo</button>
                </th>



              </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $c)
                    <tr>
                        <td>
                            <details class="dropdown">
                                <summary>Acciones</summary>
                                <ul>
                                    <li>
                                        <a wire:click="editarCliente({{$c->id}})" @click="modalNuevoCliente = !modalNuevoCliente" >
                                            Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('cuentaCorriente',['cliente'=>$c->id])}}">Ver Cuenta Corriente</a>
                                    </li>
                                    <li>
                                        <a wire:click="eliminarCliente({{$c->id}})" onclick="confirm('¿Estas seguro de eliminar el cliente? Esto tambien borra los mobimientos de CC.') || event.stopImmediatePropagation()" >
                                            Eliminar
                                        </a>
                                    </li>

                                </ul>
                            </details>
                        </td>
                        <td>
                            
                            <!-- Dropdown -->
                            <details class="dropdown">
                                <summary>{{$c->razonSocial}}</summary>
                                <ul>
                                    <li>Correo: {{$c->correo}}</li>
                                    <li>Domicilio: {{$c->domicilio}}</li>

                                </ul>
                            </details>

                        </td>
                        <td>{{$c->tipoDocumento}}</td>
                        <td>{{$c->numeroDocumento}}</td>
                        <td>
                            {{-- <option value="5">Consumidor Final</option>
                            <option value="13">Monotributista</option>
                            <option value="6">Responsable Inscripto</option>
                            <option value="4">Exento</option> --}}
                            @switch($c->tipoContribuyente)
                                @case(4)
                                IVA Sujeto Exento      
                                    @break
                                @case(13)
                                Monotributista
                                    @break
                                @case(5)
                                Consumidor Final
                                    @break
                                @case(6)
                                Responsable Inscripto
                                    @break
                                @default
                                    
                            @endswitch
                            ({{$c->tipoContribuyente}})
                        </td>
                        <td><a href="http://wa.me/{{$c->telefono}}" target="_blank" rel="noopener noreferrer">{{$c->telefono}}</a></td>

                        <td>
                            <a 
                                role="button" 
                                href="{{ route('cuentaCorriente', ['cliente' => $c->id]) }}"
                                style="display: inline-block; min-width: 300px; text-align: center; 
                                    @if($c->saldo < 0)
                                        background-color: #b30000; color: #ffcccc;
                                    @endif"
                            >
                                ${{ ($c->saldo) ? number_format($c->saldo, 2, '.', ',') : '0.00' }}
                            </a>
                        </td>



                    </tr>
                @endforeach

            </tbody>
            
          </table>
      </div>


    </div>

    <dialog x-bind:open="modalNuevoCliente">      
        <article>
                
                <header>
                <button aria-label="Close" rel="prev" @click="modalNuevoCliente = !modalNuevoCliente"></button>
                <p>
                    <strong>Nuevo Cliente</strong>
                </p>
                </header>
        
                    <fieldset>
                        <label>
                            Razon Social
                            <input
                            type="text"
                            name=""
                            placeholder="Razon Social (Nombre Completo)"
                            wire:model="razonSocial"
                            @error('razonSocial') aria-invalid="true" @enderror
                            />
                            @error('razonSocial') 
                                <small id="invalid-helper">
                                    {{ $message }}  
                                </small>
                            @enderror
                        </label>
                        <label>
                            Cuit
                            <input
                            type="text"
                            name=""
                            placeholder="Cuit-Cuil-DNI"
                            wire:model="cuit"
                            @error('cuit') aria-invalid="true" @enderror
                            />
                            @error('cuit') 
                                <small id="invalid-helper">
                                    {{ $message }}  
                                </small>
                            @enderror
                        </label>
                        <label>
                            Tipo de documento
                            <select name="" wire:model="tipoDocumento"
                            @error('tipoDocumento') aria-invalid="true" @enderror
                            >
                                <option selected value="99">Consumidor Final</option>
                                <option  value="80">CUIT</option>
                                <option  value="86">CUIL</option>
                                <option  value="96">DNI</option>
                            </select>
                            @error('tipoDocumento') 
                                <small id="invalid-helper">
                                    {{ $message }}  
                                </small>
                            @enderror
                        </label>

                        <label>
                            Telefono
                            <input
                            type="text"
                            name=""
                            placeholder="Telefono"
                            wire:model="telefono"
                            
                            />
                        </label>


                        <label>
                            Domicilio
                            <input
                            type="text"
                            name=""
                            placeholder="Domicilio"
                            wire:model="domicilio"
                            />
                        </label>
                        <label>
                            Correo
                            <input
                            type="text"
                            name=""
                            placeholder="Correo"
                            wire:model="correoCliente"
                            />
                        </label>
                        <label for="">
                            Tipo Contribuyente
                            <select name="" aria-label=""  required wire:model="tipoContribuyente">       
    
                                    <option value="5">Consumidor Final</option>
                                    <option value="13">Monotributista</option>
                                    <option value="6">Responsable Inscripto</option>
                                    <option value="4">Exento</option>
                            
                            </select>
                            
                        </label>
                    </fieldset>
                
    
                
                @if ($idCliente)
                
                    <button wire:click="updateCliente" >Editar Cliente</button>
                
                @else
                    <button wire:click="guardarCliente" >Guardar Cliente</button>
                
                @endif
                <button wire:click="cancelar" @click="modalNuevoCliente = !modalNuevoCliente">Cancelar</button>
        </article>
    </dialog>


</div>
