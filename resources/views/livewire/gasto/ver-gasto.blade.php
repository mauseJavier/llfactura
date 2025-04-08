<div x-data="{  modal: false, modalVer: false }">

    @if (session('status'))
        <div class="container">
            {{ session('status') }}
        </div>
    @endif

    @if(session('creado'))
        <div class="container" style="color: green;">
            {{ session('creado') }}
        </div>
    @endif


    <div class="container">
        <h3>Gastos</h3>



        <article>
            <div class="grid">
                <div class="col">
                    Suma Importe.
                    <h3>${{number_format($sumaImporte, 2, ',', '.')}}</h3>

                    <h6 style="color: green;"> Pagado: ${{number_format($sumaImportePagado, 2, ',', '.')}}</h6>
                    <h6 style="color: red;"> Impago: ${{number_format($sumaImporteImpago, 2, ',', '.')}}</h6>

                </div>
                <div class="col">

                    <button @click="modal = !modal">Nuevo Gasto</button>

                </div>
                <div class="col">
                    <input
                        type="search"
                        name="Buscar"
                        placeholder="Buscar"
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
                                     {{$filtroRepetir == '' ? '' : '(Repetir: '.$filtroRepetir.')'}} 


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
                                <option value="Otros">Otros</option>
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

                        <label for="">
                            Repetir

                            <select name="estado" aria-label="Seleccione Repetir" wire:model.live="filtroRepetir">
                                <option selected disabled value="">
                                    Seleccione Repetir
                                </option>
                                <option value="">Todo</option>
                                <option selected value="No" >No</option>
                                <option selected value="Repetido" >Repetido</option>
                                <option selected value="Mes" selected>Mes</option>
                                <option selected value="Minuto" selected>Minuto</option>


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
        {{-- mostra mensaje de error de session flash error  --}}
        @if (session('error'))
            <div class="container" style="color: red;">
                {{ session('error') }}
            </div>
        @endif


        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                      <th scope="col">Acciones</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">F.Pago</th>
                    <th scope="col">Proveedor</th>
                    
                    <th scope="col">Usuario</th>
                    <th scope="col">Importe</th>
                    <th scope="col">Pagar</th>


                  </tr>
                </thead>
                <tbody>
                    
                    @foreach ($Gasto as $item)
                        <tr >

                            <td>
                                <!-- Dropdown -->
                                <details class="dropdown">
                                    <summary>Acciones</summary>
                                    <ul>
                                    <li><a wire:click="verGasto({{ $item->id }})" @click="modalVer = !modalVer" >Ver</a></li>
                                    @if (Auth::user()->role_id == 3 || Auth::user()->role_id == 4)
                                    
                                        <li>
                                            <a wire:click="editarGasto({{ $item->id }})" @click="modal = !modal" >Editar</a>
                                        </li>        
                                    @endif
                                    </ul>
                                </details>

                            </td>


                            <td 
                                 x-data="{ estado: '{{ $item->estado }}' }" :style="{ 'color': estado === 'Impago' ? 'red' : '' }"
                            >
                                {{ $item->created_at }}</td>
                            <td 
                                 x-data="{ estado: '{{ $item->estado }}' }" :style="{ 'color': estado === 'Impago' ? 'red' : '' }"
                            >
                                {{ $item->tipo }}</td>
                                <td 
                                x-data="{ estado: '{{ $item->estado }}' }" :style="{ 'color': estado === 'Impago' ? 'red' : '' }"
                                >
                                {{ $item->formaPago }}</td>
                                <td 
                                x-data="{ estado: '{{ $item->estado }}' }" :style="{ 'color': estado === 'Impago' ? 'red' : '' }"
                                >
                                {{ $item->nombreProveedor }}</td>
                                
                                <td 
                                x-data="{ estado: '{{ $item->estado }}' }" :style="{ 'color': estado === 'Impago' ? 'red' : '' }"
                                >
                                {{ $item->usuario }}
                            </td>
                            
                            <td 
                                 x-data="{ estado: '{{ $item->estado }}' }" :style="{ 'color': estado === 'Impago' ? 'red' : '' }"
                            >
                                ${{ number_format($item->importe, 2, ',', '.') }}
                            </td>

                            <td>
                                @if (Auth::user()->role_id == 3 || Auth::user()->role_id == 4)
                                    
                                    @switch($item->estado)

                                        @case('Impago')

                                            <a  role="button" wire:click="cambiarEstadoImpago({{ $item->id }})"  >Pagar</a>
                                            @break
                                            @default
                                            <p style="text-align: center">

                                                <!-- check icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                                                <svg xmlns="http://www.w3.org/2000/svg" height="2em" fill="currentColor" viewBox="0 0 512 512">
                                                    <path
                                                    d="M 501.7142857142857 83.42857142857143 Q 512 94.85714285714286 512 109.71428571428571 L 512 109.71428571428571 L 512 109.71428571428571 Q 512 124.57142857142857 501.7142857142857 136 L 209.14285714285714 428.57142857142856 L 209.14285714285714 428.57142857142856 Q 197.71428571428572 438.85714285714283 182.85714285714286 438.85714285714283 Q 168 438.85714285714283 156.57142857142858 428.57142857142856 L 10.285714285714286 282.2857142857143 L 10.285714285714286 282.2857142857143 Q 0 270.85714285714283 0 256 Q 0 241.14285714285714 10.285714285714286 229.71428571428572 Q 21.714285714285715 219.42857142857142 36.57142857142857 219.42857142857142 Q 51.42857142857143 219.42857142857142 62.857142857142854 229.71428571428572 L 182.85714285714286 350.85714285714283 L 182.85714285714286 350.85714285714283 L 449.14285714285717 83.42857142857143 L 449.14285714285717 83.42857142857143 Q 460.57142857142856 73.14285714285714 475.42857142857144 73.14285714285714 Q 490.2857142857143 73.14285714285714 501.7142857142857 83.42857142857143 L 501.7142857142857 83.42857142857143 Z"
                                                    />
                                                </svg>
                                            </p>
                                    @endswitch
                                @endif
                            </td>

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

                
                <header>
                <button aria-label="Close" wire:click="cancelar()" @click="modal = false" rel="prev"></button>
                <p>
                    <strong>Datos del Gasto</strong>
                </p>
                </header>
                                
                <fieldset>

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
                            Detalle de Gasto
                            {{-- <input name="terms" type="checkbox" role="switch" @click="isChecked = !isChecked" /> --}}
                    
                            <input type="text" name="tipo" x-model="tipo" 
                                {{-- x-bind:disabled="!isChecked"  --}}
                                @input="showSuggestions = true" @blur="setTimeout(() => showSuggestions = false, 100)" 
                            />
                    
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

                            <option value="Otros">Otros</option>
        
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

                    @if (Auth::user()->role_id != 1 AND $idGasto == '')
                        

                        <label for="">
                            Repetir  
                            <input name="terms" type="checkbox" role="switch"  @click="showSelectRepe = !showSelectRepe" />
                            
                            <select name="repetir" wire:model="repetir" x-show="showSelectRepe">
                                @if ($idGasto == '')
                                    <option selected value="No">No</option>
                                    <option selected value="Mes">Mes</option>
                                    @if (Auth()->user()->role_id == 3)
                                    
                                        <option selected value="Minuto">Minuto (Super)</option>
                                        
                                    @endif
                                    
                                @else
                                    <option selected value="No">No (Edicion)</option>

                                @endif


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

                    @else
                        <label for="">
                            <fieldset>
                                <label>
                                    Estado Repetir: {{$repetir}}
                                <input 
                                    @if ($repetir == 'No' || $repetir == 'Repetido')
                                        disabled
                                    @else
                                        checked
                                    @endif

                                    wire:click="quitarRepetir({{$idGasto}})"

                                    name="terms" type="checkbox" role="switch" />
                                
                                </label>

                            </fieldset>


                        </label>
                    @endif

    
    
    
    
    
                </fieldset>
                
                <button wire:click="guardarGasto">Guardar</button>
                
            


        </article>
    </dialog>


{{-- modal de ver gasto --}}
    <dialog x-bind:open="modalVer" >
        <article>
          <header>
            <button aria-label="Close" wire:click="cancelar()" @click="modalVer = false" rel="prev"></button>
            <p>
              <strong>{{$VerGastoObjeto ? $VerGastoObjeto->tipo : ''}}</strong>
            </p>
          </header>

                <label for="">
                    Proveedor
                    <input type="text" name="proveedor" value="{{$VerGastoObjeto ? $VerGastoObjeto->nombreProveedor : ''}}" disabled>
                </label>

                <label for="">
                    Forma Pago
                    <input type="text" name="formaPago" value="{{$VerGastoObjeto ? $VerGastoObjeto->formaPago : ''}}" disabled>
                </label>

                <label for="">
                    Importe
                    <input type="text" name="importe" value="{{$VerGastoObjeto ? $VerGastoObjeto->importe : ''}}" disabled>
                </label>

                <label for="">
                    Estado
                    <input type="text" name="estado" value="{{$VerGastoObjeto ? $VerGastoObjeto->estado : ''}}" disabled>
                </label>

                <label for="">
                    Comentario
                    <textarea name="comentario" id="" cols="30" rows="10" disabled>{{$VerGastoObjeto ? $VerGastoObjeto->comentario : ''}}</textarea>
                </label>

                <label for="">
                    Usuario
                    <input type="text" name="usuario" value="{{$VerGastoObjeto ? $VerGastoObjeto->usuario : ''}}" disabled>
                </label>

                <label for="">
                    Fecha Creado
                    <input type="text" name="created_at" value="{{$VerGastoObjeto ? $VerGastoObjeto->created_at : ''}}" disabled>
                </label>

                <label for="">
                    Fecha Modificado
                    <input type="text" name="updated_at" value="{{$VerGastoObjeto ? $VerGastoObjeto->updated_at : ''}}" disabled>
                </label>

          {{-- {
            "id": 22,
            "tipo": "Gasto sdfsdf",
            "importe": 3423,
            "formaPago": "Efectivo",
            "estado": "Impago",
            "idProveedor": null,
            "comentario": null,
            "diaNotificacion": null,
            "usuario": "MAUSE LLFACTURA",
            "repetir": "No",
            "empresa_id": 1,
            "created_at": "2025-04-08T02:14:55.000000Z",
            "updated_at": "2025-04-08T02:22:45.000000Z"
        } --}}


        </article>
      </dialog>
</div>


