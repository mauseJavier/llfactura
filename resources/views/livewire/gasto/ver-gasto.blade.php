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

                    <button @click="modal = !modal">Nuevo</button>
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
                    <h4>Filtro:</h4> {{$tipo == '' ? '' : '(Tipo: '.$tipo.')'}}
                                     {{$formaPago == '' ? '' : '(F. Pago: '.$formaPago.')'}}
                                    {{$estado == '' ? '' : '(Estado: '.$estado.')'}} 
                                     {{$fechaCreado == '' ? '' : '(Fecha Creado: '.$fechaCreado.')'}} 

                </summary>
                <div class="grid">
                    <div class="col">
                        <label for="">
                            Tipo

                            <select name="tipo" aria-label="Tipo" wire:model.live="tipo">
                                <option selected disabled value="">
                                    Seleccione Tipo
                                </option>
                                <option value="">Todo</option>
                                <option selected value="Gasto">Gasto</option>
                                <option value="Factura">Factura</option>
                                <option value="Compra">Compra</option>

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
                    <th scope="col">Notificacion</th>
                    <th scope="col">Usuario</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($Gasto as $item)
                        <tr>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->tipo}}</td>
                            <td>{{$item->importe}}</td>
                            <td>{{$item->formaPago}}</td>
                            <td>{{$item->estado}}</td>
                            <td>{{$item->nombreProveedor}}</td>
                            <td>{{$item->comentario}}</td>
                            <td>{{$item->fechaNotificacion}}</td>
                            <td>{{$item->usuario}}</td>


                        </tr>
                    @endforeach

                </tbody>

              </table>
        </div>
    </div>

    <div class="container">
        {{ $Gasto->links('vendor.livewire.bootstrap') }}

    </div>




    <dialog x-bind:open="modal">
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
    
                    <div>@error('importe') {{ $message }} @enderror</div>
                    <div>@error('tipo') {{ $message }} @enderror</div>
                    <div>@error('estado') {{ $message }} @enderror</div>
                    <div>@error('formaPago') {{ $message }} @enderror</div>


    
                    <label for="">
                        Tipo

                        <select name="tipo" wire:model="tipo" >
        
                            <option selected disabled value="">
                                Seleccione Tipo
                            </option>
    
                            <option value="Gasto">Gasto</option>
                            <option value="Factura">Factura</option>
                            <option value="Compra">Compra</option>
                        </select>
                    </label>
    
                    <label>
                        Importe
                        <input
                            name="importe"
                            placeholder="Importe Gasto"
                            autocomplete="importe"
                            wire:model="importe"
                        />
                    </label>
    
                    <label for="">
                        Forma Pago 

                        <select name="formaPago" wire:model="formaPago" >
        
                            <option selected disabled value="">
                                Seleccione Forma Pago
                            </option>
    
                            @foreach ($FormaPago as $item)                        
                                <option selected value="{{$item->nombre}}">{{$item->nombre}}</option>
                            @endforeach
        
                        </select>
                    </label>
    
                    <label for="">
                        Estado

                        <select name="estado" wire:model="estado" >
        
                            <option selected disabled value="">
                                Seleccione Estado
                            </option>
    
                            <option value="Impago">Impago</option>
                            <option value="Pago" >Pago</option>
        
        
        
                        </select>
                    </label>
    
                    <label for="">
                        Proveedor

                        <select name="proveedor" wire:model="idProveedor" >
                            <option selected value="" selected>No</option>
                            @foreach ($Proveedor as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>                        
                            @endforeach
        
        
                        </select>
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
                        Notificacion

                        <input type="date" name="fechaNotificacion" aria-label="Date" wire:model="fechaNotificacion">
                    </label>
    
    
    
    
                </fieldset>
                
                <button wire:click="guardarGasto">Guardar</button>
                
            @endif


        </article>
    </dialog>
</div>


