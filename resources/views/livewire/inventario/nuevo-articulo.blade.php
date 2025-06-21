<div>
    
    <div class="container">

        <a wire:navigate role="button" href="{{route('inventario')}}">Inventario</a>
        <hr>
        <h2>@if($idArticulo) Editar Artículo ID:{{$idArticulo}} @else Nuevo Artículo @endif</h2>

        <fieldset>
            <label>
                <input name="terms" type="checkbox" role="switch"
                wire:click="cambiarAgregarVariosArticulos"
                @if ($agregarVariosArticulos)
                    checked                    
                @endif />
                Volver a Agregar/Editar

            </label>
        </fieldset>

        @if (session('mensaje'))
            <div style="color: red;">{{ session('mensaje') }}</div>
        @endif
        
        <form wire:submit.prevent="guardar">
            <div wire:loading wire:target="guardar" class="alert alert-info" style="margin-bottom: 1rem; text-align:center;">
                Procesando... Por favor espere.
                <progress />
            </div>

            <div class="div" wire:loading.remove wire:target="guardar">

                <div class="grid">
                    <div class="col">
                        <article>
                            <h4>Artículo</h4>
                            <label>
                                Código
                                <input type="text" wire:model.defer="codigo" required placeholder="Código del artículo">
                                @error('codigo') <span class="error">{{ $message }}</span> @enderror
                            </label>
                            <label>
                                Detalle
                                <input type="text" wire:model.defer="detalle" required placeholder="Descripción o detalle">
                                @error('detalle') <span class="error">{{ $message }}</span> @enderror
                            </label>

                            <label>
                                Pesable
                                <select wire:model.defer="pesable">
                                    <option value="no">No</option>
                                    <option value="si">Sí</option>
                                </select>
                            </label>

                        </article>


                        <article>

                            <h4>Grupos</h4>
                            
                            <label>
                                Rubro
                                <select wire:model.live="rubro" required>
                                    <option value="General">General</option>
                                    @foreach($listaRubros as $rubroItem)
                                        <option value="{{ $rubroItem->nombre }}">{{ $rubroItem->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('rubro') <span class="error">{{ $message }}</span> @enderror
                            </label>
                            <label>
                                Proveedor
                                <select wire:model.live="proveedor" required>
                                    <option value="General">General</option>
                                    @foreach($listaProveedores as $proveedorItem)
                                        <option value="{{ $proveedorItem->nombre }}">{{ $proveedorItem->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('proveedor') <span class="error">{{ $message }}</span> @enderror
                            </label>
                            <label>
                                Marca
                                <select wire:model.live="marca" required>
                                    <option value="General">General</option>
                                    @foreach($listaMarcas as $marcaItem)
                                        <option value="{{ $marcaItem->nombre }}">{{ $marcaItem->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('marca') <span class="error">{{ $message }}</span> @enderror
                            </label>

                        </article>

                        <article>
                            <h4>Stocks</h4>

                            <label>
                                Depósito
                                <select wire:model.live="idDeposito" required>
                                    @foreach($depositos as $depositoItem)
                                        <option value="{{ $depositoItem->id }}">{{ $depositoItem->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('deposito') <span class="error">{{ $message }}</span> @enderror
                            </label>


                            <label>
                                Agregar stock (0=Sin Control)
                                <input
                                    wire:model.live="nuevoStock"
                                    name="nuevoStock"
                                    placeholder="Stock"
                                    autocomplete="nuevoStock"
                                    @error('nuevoStock') aria-invalid="true" @enderror
                                />
                                @error('nuevoStock') 
                                <small id="invalid-helper">
                                    {{ $message }} 
                                    </small>
                                @enderror
                            </label>


                        </article>


                    </div>


                    
                    
                    <div class="col">

                        <article>
                            <h4>Impuestos</h4>
    
                            <label>
                                IVA incluido en costo
                                <input type="checkbox" wire:model="ivaIncluido" wire:change="calcularPrecios()">
                            </label>
                            <label>
                                IVA
                                <select wire:model.live="iva" required wire:change="calcularPrecios()">
                                    <option value="" disabled>Seleccione IVA</option>
                                    <option value="21">21%</option>
                                    <option value="10.5">10.5%</option>
                                </select>
                                @error('iva') <span class="error">{{ $message }}</span> @enderror
                            </label>
    
    
                        </article>

                        <article>
                            <h4>Precios</h4>
                            <label>
                                Costo
                                <input type="number" step="0.01" wire:keydown="calcularPrecios()" wire:model.live="costo" required placeholder="Costo">
                                @error('costo') <span class="error">{{ $message }}</span> @enderror
                            </label>

                            
                            <label>
                                Porcentaje 1 (Rentavilidad)
                                <fieldset role="group">
                                    <input type="number" wire:keydown="calcularPrecios()" step="0.01" wire:model.live="porcentaje" required placeholder="Porcentaje de ganancia 1">
                                    
                                    <select wire:model.live="porcentaje" required wire:change="calcularPrecios()">
                                        <option value="0">Seleccione Lista de Precios</option>
                                        @foreach($listaPrecios as $precio)
                                            <option value="{{ $precio->porcentaje }}">{{ $precio->nombre }}(%{{ $precio->porcentaje }})</option>
                                        @endforeach
                                    </select>
                                    @error('listaPrecios') <span class="error">{{ $message }}</span> @enderror
                                </fieldset>
                                @error('porcentaje') <span class="error">{{ $message }}</span> @enderror
                            </label>

                            <label>
                                Precio 1
                                <input type="number" step="0.01" wire:model.live="precio1" required placeholder="Precio de venta">
                                @error('precio1') <span class="error">{{ $message }}</span> @enderror
                            </label>
                            <label>
                                Precio 2 ({{$empresa->precio2}}% de P1)
                                <input type="number" step="0.01" wire:model.live="precio2" placeholder="Precio de venta 2">
                                @error('precio2') <span class="error">{{ $message }}</span> @enderror
                            </label>
                            <label>
                                Precio 3 ({{$empresa->precio3}}% de P1)
                                <input type="number" step="0.01" wire:model.live="precio3" placeholder="Precio de venta 3">
                                @error('precio3') <span class="error">{{ $message }}</span> @enderror
                            </label>
                        </article>
                        <article>
                            <h4>Agregar Imagen</h4>
                            <label>
                                Imagen

                                @if ($imagenFile)
                                <hr>
                                    <img width="50%" src="{{$imagenFile->temporaryUrl()}}" alt="">                                    
                                @endif
                                <input type="file" wire:model.live="imagenFile" accept="image/png,image/jpeg"  placeholder="Seleccionar imagen">
                                @error('imagenFile') <span class="error">{{ $message }}</span> @enderror
                            </label>




                        </article>

                        
                    </div>

                </div>
                
                @if($idArticulo) 
                    <article>
                        <div class="container">

                        <livewire:inventario.galeria-imagen :imagenes="$arrayImagen"/>
                        </div>
                    </article>
                @endif

            </div>
            <div style="margin-top: 1rem; text-align: right;">
                <button type="submit" class="btn btn-primary">@if($idArticulo) Actualizar @else Guardar @endif</button>
            </div>
        </form>


    </div>

    


</div>
