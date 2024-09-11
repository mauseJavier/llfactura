<div>
    <div class="container">

        <article>
            <hgroup>
                <h1>Importar Stock</h1>
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
                            placeholder="Deposito"
                            name="costo"
                            wire:model="CAMPOdeposito"
                            aria-describedby="valid-helper"
                            @error('CAMPOdeposito')aria-invalid="true" @enderror
                        />
                        @error('CAMPOdeposito') 
                        <small id="invalid-helper">
                            {{ $message }} 
                        </small>
                        @enderror
                    

                    </div>
                    <div class="col">

                        <input
                            type="text"
                            placeholder="Stock"
                            name="stock"
                            wire:model="CAMPOstock"
                            aria-describedby="valid-helper"
                            @error('CAMPOstock')aria-invalid="true" @enderror
                        />
                        @error('CAMPOstock') 
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
