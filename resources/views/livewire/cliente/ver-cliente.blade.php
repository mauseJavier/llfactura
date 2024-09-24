<div x-data="{ modalNuevoCliente: false }">

    <div class="container" >
        <h3>Clientes</h3>

        <article>
            <div class="grid">
                <div class="col">
                    <button @click="modalNuevoCliente = !modalNuevoCliente">Nuevo Cliente</button>

                </div>
                <div class="col">

                    <input wire:model.live="datoBuscado" type="search" name="" id="">
                </div>
            </div>
        </article>

        @if (session('mensaje'))
            <article>
                <p>
                    {{ session('mensaje') }}    
                </p>     
            </article>
        @endif
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
                <th scope="col">Razon Social</th>
                <th scope="col">Tipo Doc</th>
                <th scope="col">Numero</th>
                <th scope="col">Tipo Contr.</th>
                <th scope="col">Correo</th>
                <th scope="col">Domicilio</th>

                <th scope="col">Saldo</th>



              </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $c)
                    <tr>
                        <td>
                            <button wire:click="editarCliente({{$c->id}})" @click="modalNuevoCliente = !modalNuevoCliente">
                                {{$c->razonSocial}}
                            </button>
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
                        <td>{{$c->correo}}</td>
                        <td>{{$c->domicilio}}</td>
                        <td><a href="{{route('cuentaCorriente',['cliente'=>$c->id])}}">${{($c->saldo) ? $c->saldo : 0}}</a></td>



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

            @if (session('mensaje'))
                <article>
                    <p>
                        {{ session('mensaje') }}    
                    </p>     
                </article>
            @endif

            <form wire:submit="guardarCliente">
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
                        <select name="" wire:model="tipoDocumento">
                            <option selected value="99">Consumidor Final</option>
                            <option  value="80">CUIT</option>
                            <option  value="86">CUIL</option>
                            <option  value="96">DNI</option>
                        </select>
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
            
                <input
                type="submit"
                value="Guardar Cliente"
                />
            </form>

            <button @click="modalNuevoCliente = !modalNuevoCliente">Cancelar</button>
        </article>
    </dialog>


</div>
