<div>

    <div class="container">

        <article>
            
            <hgroup>
                <h1>Importar Inventario</h1>
                <p>Ingrese nombre de las columnas TODO EN MINUSCULA!</p>
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
    
        <article wire:loading.remove>
            <form wire:submit="importarArchivo">

                <div class="grid">
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
                        <input type="text" name="stock" id=""
                        wire:model="CAMPOstock"
                        placeholder="Stock (DISABLED)"
                        disabled
                        @error('CAMPOstock')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOstock') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror
                        
                    </div>

                    <div class="col">
                        <input type="text" name="deposito" id=""
                        wire:model="CAMPOdeposito"
                        placeholder="Deposito (DISABLED)"
                        disabled
                        @error('CAMPOdeposito')aria-invalid="true" @enderror                        
                        >
                        @error('CAMPOdeposito') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>
                        @enderror
                        
                    </div>

                </div>




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
