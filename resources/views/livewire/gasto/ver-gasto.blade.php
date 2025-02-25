<div x-data="{  modal: false}">

    @if (session('status'))
        <div class="container">
            {{ session('status') }}
        </div>
    @endif


    <div class="container">
        <h3>Gastos</h3>

        <article>
            <div class="grid">
                <div class="col">
                    Suma Importe.
                    <h3>${{number_format($sumaImporte, 2, ',', '.')}}</h3>

                </div>
                <div class="col">

                    <button @click="modal = !modal">Nuevo Gasto</button>

                </div>
                <div class="col">
                    <input
                        type="search"
                        name="Buscar"
                        placeholder="Buscar (Proveedor/Usuario)"
                        aria-label="Buscar"
                        wire:model.live="buscar"
                    />
                </div>
            </div>

            <hr>


            <details>
                <summary>
                    <h4>Filtro:</h4> {{$filtroTipo == '' ? '' : '(Tipo: '.$filtroTipo.')'}}
                                     {{$formaPago == '' ? '' : '(F. Pago: '.$formaPago.')'}}
                                    {{$estado == '' ? '' : '(Estado: '.$estado.')'}} 
                                     {{$fechaCreado == '' ? '' : '(Fecha Creado: '.$fechaCreado.')'}} 

                </summary>
                <div class="grid">
                    <div class="col">
                        <label for="">
                            Tipo

                            <select name="tipo" aria-label="Tipo" wire:model.live="filtroTipo">
                                <option selected disabled value="">
                                    Seleccione Tipo
                                </option>
                                <option value="">Todo</option>
                                @foreach ($tiposUnicos as $item)
                                    
                                    <option value="{{$item}}">{{$item}}</option>
                                @endforeach

                            </select>
                        </label>

                        <label for="">
                            Forma Pago 

                            <select name="FormaPago" aria-label="Forma Pago" wire:model.live="formaPago">
                                <option selected disabled value="">
                                    Seleccione Forma Pago
                                </option>
                                <option value="">Todo</option>
                                @foreach ($FormaPago as $item)                        
                                    <option selected value="{{$item->nombre}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                        </label>

                        <label for="">
                            Estado

                            <select name="estado" aria-label="Seleccione Estado" wire:model.live="estado">
                                <option selected disabled value="">
                                    Seleccione Estado
                                </option>
                                <option value="">Todo</option>
                                <option selected value="Impago" selected>Impago</option>
                                <option selected value="Pago" >Pago</option>
                            </select>

                        </label>

                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Fecha de Modificacion:</legend>
                            <label>
                                <input type="radio" name="language" wire:click="filtroFecha('')" {{$fechaCreado == '' ? 'checked' : ''}}   />
                                Todo
                            </label>
                            <label>
                              <input type="radio" name="language" wire:click="filtroFecha('Hoy')" />
                              Hoy
                            </label>
                            <label>
                              <input type="radio" name="language" wire:click="filtroFecha('Esta Semana')" />
                              Esta Semana
                            </label>
                            <label>
                              <input type="radio" name="language" wire:click="filtroFecha('Este Mes')" />
                              Este Mes
                            </label>
                            <label>
                              <input type="radio" name="language" wire:click="filtroFecha('Mes Pasado')" />
                              Mes Pasado
                            </label>

                            <label for="">
                                Mes
                                <input type="month" name="month" aria-label="Month" wire:model.live="fechaCreado">

                            </label>

                          </fieldset>
            
                    </div>
                </div>
            </details>

        </article>


    </div>
    
    <div class="container">
        
        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Importe</th>
                    <th scope="col">F.Pago</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Dia Notif.</th>
                    <th scope="col">Repetir</th>

                    <th scope="col">Usuario</th>
                  </tr>
                </thead>
                <tbody>
                    
                    @foreach ($Gasto as $item)
                        <tr x-data="{ repetir: '{{ $item->repetir }}' }" :style="{ 'background-color': repetir === 'Repetido' ? 'green' : '' }">
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->tipo }}</td>
                            <td>${{ number_format($item->importe, 2, ',', '.') }}</td>
                            <td>{{ $item->formaPago }}</td>
                            <td>{{ $item->estado }}</td>
                            <td>{{ $item->nombreProveedor }}</td>
                            <td>{{ $item->comentario }}</td>
                            <td>{{ $item->diaNotificacion }}</td>
                            <td>
                                @if ($item->repetir == 'No' || $item->repetir == 'Repetido')
                                    {{ $item->repetir }}
                                @else
                                    {{ $item->repetir }} <a wire:click="quitarRepetir({{ $item->id }})">Quitar</a>
                                @endif
                            </td>
                            <td>{{ $item->usuario }}</td>
                        </tr>
                    @endforeach
                

                </tbody>

              </table>
        </div>
    </div>

    <div class="container">
        {{ $Gasto->links('vendor.livewire.bootstrap') }}

    </div>



{{-- //MODAL DE NUEVO GASTO --}}
    <dialog x-bind:open="modal" x-data="{ showSelectProveedor: false,showSelectNotifi: false, showSelectRepe: false, isChecked: false }">
        <article>

            @if (session('creado'))
                <h3>
                    {{ session('creado') }}
                </h3>

                <a href="{{route('gasto')}}" role="button">Cerrar</a>

                
            @else
                
                <header>
                <button aria-label="Close" @click="modal = false" rel="prev"></button>
                <p>
                    <strong>Datos del Gasto</strong>
                </p>
                </header>
                
                
                
                <fieldset>
    
                    <div x-data="{
                        
                        tipo: @entangle('tipo'),
                        tipos: @js($tiposUnicos),
                        filteredTipos: [],
                        showSuggestions: false,
                        get filteredTipos() {
                            return this.tipos.filter(tipo => tipo.toLowerCase().includes(this.tipo.toLowerCase()));
                        }
                    }">
                        <label for="">
                            Tipo de Gasto
                            <input name="terms" type="checkbox" role="switch" @click="isChecked = !isChecked" />
                    
                            <input type="text" name="tipo" x-model="tipo" x-bind:disabled="!isChecked" @input="showSuggestions = true" @blur="setTimeout(() => showSuggestions = false, 100)" />
                    
                            <ul x-show="showSuggestions && tipo.length > 0">
                                <template x-for="suggestion in filteredTipos" :key="suggestion">
                                    <li @click="tipo = suggestion; showSuggestions = false">
                                        <span style="color: red;" x-text="suggestion"></span>
                                    </li>
                                </template>
                            </ul>
                        </label>
                    </div>
                    
                    

                    <label for="">
                        Forma Pago

                        <select name="formaPago" wire:model="formaPago"                             
                        @error('formaPago') aria-invalid="true" @enderror  >
        
                            <option selected disabled value="">
                                Seleccione Forma Pago
                            </option>
    
                            @foreach ($FormaPago as $item)                        
                                <option selected value="{{$item->nombre}}">{{$item->nombre}}</option>
                            @endforeach
        
                        </select>
                        @error('formaPago') 
                            <small id="invalid-helper">
                                {{ $message }}  
                            </small>
                        @enderror
                    </label>
    
                    <label for="">
                        Estado

                        <select name="estado" wire:model="estado" 
                        @error('estado') aria-invalid="true" @enderror >
        
                            <option selected disabled value="">
                                Seleccione Estado
                            </option>
    
                            <option value="Impago">Impago</option>
                            <option value="Pago" >Pago</option>
        
        
        
                        </select>
                        @error('estado') 
                            <small id="invalid-helper">
                                {{ $message }}  
                            </small>
                        @enderror
                    </label>
    

                    

                    <label>
                        Importe
                        <input
                            name="importe"
                            placeholder="Importe Gasto"
                            autocomplete="importe"
                            wire:model="importe"
                            @error('importe') aria-invalid="true" @enderror

                        />
                        @error('importe') 
                            <small id="invalid-helper">
                                {{ $message }}  
                            </small>
                        @enderror
                    </label>
                    
                    <label for="">
                        Comentario

                        <textarea
                            name="comentario"
                            placeholder="Comentario"
                            aria-label=""
                            wire:model="comentario"
                            >
                        </textarea>
                    </label>
    
                    <label for="">
                        Proveedor 
                        <input name="terms" type="checkbox" role="switch"  @click="showSelectProveedor = !showSelectProveedor" />
                    
                        <select name="proveedor" wire:model="idProveedor" x-show="showSelectProveedor">
                            <option selected value="">No</option>
                            @foreach ($Proveedor as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label for="">
                        Dia Notificacion 
                        <input name="terms" type="checkbox" role="switch"  @click="showSelectNotifi = !showSelectNotifi" />
                        
                        <select name="dia_mes" wire:model="diaNotificacion" x-show="showSelectNotifi">
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}">Dia: {{ $i }}</option>
                            @endfor
                        </select>
                        


                    </label>
                    <label for="">
                        Repetir  
                        <input name="terms" type="checkbox" role="switch"  @click="showSelectRepe = !showSelectRepe" />
                        
                        <select name="repetir" wire:model="repetir" x-show="showSelectRepe">
                            <option selected value="No">No</option>
                            <option selected value="Mes">Mes</option>
                            @if (Auth()->user()->role_id == 3)
                            
                                <option selected value="Minuto">Minuto (Super)</option>
                                
                            @endif


                        </select>
                    </label>
    
    
    
    
                </fieldset>
                
                <button wire:click="guardarGasto">Guardar</button>
                
            @endif


        </article>
    </dialog>
</div>


