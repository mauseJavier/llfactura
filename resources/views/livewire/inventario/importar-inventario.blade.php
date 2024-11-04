<div>

    <div class="container">

        <article>
            
            <hgroup>
                <h1>Importar Inventario</h1>
            </hgroup>
        </article>

        @if ($errors->any())
            <article>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </article>

        @endif

        <article aria-busy="true" wire:loading></article>

        <article>
            <p>Formato de la informacion en el archivo CSV</p>
            <div class="overflow-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Detalle</th>
                            <th>Costo</th>
                            <th>Precio1</th>
                            <th>Precio2</th>
                            <th>Precio3</th>
                            <th>Porcentaje</th>
                            <th>Iva</th>
                            <th>Rubro</th>
                            <th>Proveedor</th>
                            <th>Marca</th>
                            <th>Pesable</th>
                            <th>Imagen</th>
    
                        </tr>
                    </thead>

                    <tbody>
                        @isset($procesados)
                            @foreach ($procesados as $item)

                                <tr>
                                    <td>{{$item->codigo}}</td>
                                    <td>{{$item->detalle}}</td>
                                    <td>{{$item->costo}}</td>
                                    <td>{{$item->precio1}}</td>
                                    <td>{{$item->precio2}}</td>
                                    <td>{{$item->precio3}}</td>
                                    <td>{{$item->porcentaje}}</td>
                                    <td>{{$item->iva}}</td>
                                    <td>{{$item->rubro}}</td>
                                    <td>{{$item->proveedor}}</td>
                                    <td>{{$item->marca}}</td>
                                    <td>{{$item->pesable}}</td>
                                    <td>{{$item->imagen}}</td>

                                </tr>
                                
                            @endforeach
                            
                        @endisset
                    </tbody>
                </table>
            </div>
        </article>
    
        <article wire:loading.remove>
            <form wire:submit="importarCsv">

                {{-- <div class="grid">
                    <div class="col">
                        <input
                            type="text"
                            placeholder="Codigo"
                            name="codigo"
                            wire:model="CAMPOcodigo"
                            aria-describedby="valid-helper"
                            @error('CAMPOcodigo')aria-invalid="true" @enderror
                        />
                        @error('CAMPOcodigo') 
                        <small id="invalid-helper">
                            {{ $message }} 
                          </small>
                        @enderror
                      

                    </div>
                    <div class="col">

                        <input
                            type="text"
                            placeholder="Detalle"
                            name="detalle"
                            wire:model="CAMPOdetalle"
                            aria-describedby="valid-helper"
                            @error('CAMPOdetalle')aria-invalid="true" @enderror
                        />
                        @error('CAMPOdetalle') 
                        <small id="invalid-helper">
                            {{ $message }} 
                          </small>
                        @enderror

                    </div>
                </div>


                <div class="grid">
                    <div class="col">
                        <input
                            type="text"
                            placeholder="Costo por Defecto 0"
                            name="costo"
                            wire:model="CAMPOcosto"
                            aria-describedby="valid-helper"
                            @error('CAMPOcosto')aria-invalid="true" @enderror
                        />
                        @error('CAMPOcosto') 
                        <small id="invalid-helper">
                            {{ $message }} 
                          </small>
                        @enderror
                      

                    </div>
                    <div class="col">

                        <input
                            type="text"
                            placeholder="Iva por defecto {{$empresa->ivaDefecto}}"
                            name="iva"
                            wire:model="CAMPOiva"
                            aria-describedby="valid-helper"
                            @error('CAMPOiva')aria-invalid="true" @enderror
                        />
                        @error('CAMPOiva') 
                        <small id="invalid-helper">
                            {{ $message }} 
                          </small>
                        @enderror

                    </div>
                </div>

                <div class="grid">
                    <div class="col">
                        <input type="text" name="precio1" id=""
                        placeholder="Precio 1 por defecto 0"
                        wire:model="CAMPOprecio1"
                         @error('CAMPOprecio1')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOprecio1') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror

                    </div>
                    <div class="col">
                        <input type="text" name="precio2" id=""
                        placeholder="Precio 2 por defecto 0"
                        wire:model="CAMPOprecio2"
                         @error('CAMPOprecio2')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOprecio2') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror

                    </div>
                    <div class="col">
                        <input type="text" name="precio3" id=""
                        placeholder="Precio 3 por defecto 0"
                        wire:model="CAMPOprecio3"
                         @error('CAMPOprecio3')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOprecio3') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror

                    </div>
                </div>

                <div class="grid">
                    <div class="col">
                        <input type="text" name="rubro" id=""
                        wire:model="CAMPOrubro"
                        placeholder="Rubro por defecto General"
                        @error('CAMPOrubro')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOrubro') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror
                        
                    </div>
                    <div class="col">
                        <input type="text" name="proveedor" id=""
                        wire:model="CAMPOproveedor"
                        placeholder="Proveedor por defecto General"
                        @error('CAMPOproveedor')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOproveedor') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror
                        
                    </div>



                </div>
                
                <div class="grid">

                    <div class="col">
                        <input type="text" name="nombreLista" id=""
                        wire:model="CAMPOnombreLista"
                        placeholder="Nombre Lista por defecto PORCENTAJE"
                        @error('CAMPOnombreLista')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOnombreLista') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror
                        
                    </div>

                    <div class="col">
                        <input type="text" name="porcentaje" id=""
                        wire:model="CAMPOporcentaje"
                        placeholder="Porcentaje por defecto 0"
                        @error('CAMPOporcentaje')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOporcentaje') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror
                        
                    </div>



                </div>

 --}}


                <input type="file" wire:model="archivo"
                @error('archivo')aria-invalid="true" @enderror
                >
             
                @error('archivo') 
                <small id="invalid-helper">
                    {{ $message }} 
                  </small>
                @enderror
             
                <button type="submit">Importar</button>
            </form>
        </article>
    </div>
</div>
