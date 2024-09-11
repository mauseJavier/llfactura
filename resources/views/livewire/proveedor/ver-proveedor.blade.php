<div x-data="{ modalNuevoProveedor: false }">
    <div class="container">
        
        <h3>Proveedores</h3>
        <article>
            <div class="grid">
                <div class="col">
                    <button @click="modalNuevoProveedor = !modalNuevoProveedor">Nuevo Proveedor</button>
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


        <div class="overflow-auto">
            <table class="striped">
                <thead>
                <tr>
                    <th scope="col">Razon Social</th>
                    <th scope="col">cuit</th>

                </tr>
                </thead>
                <tbody>
                    @foreach ($proveedores as $p)
                        <tr>
                            <td>
                                <button wire:click="editarProveedor({{$p->id}})" @click="modalNuevoProveedor = !modalNuevoProveedor">
                                    {{$p->nombre}}
                                </button>
                            </td>
                            <td>{{$p->cuit}}</td>

                        </tr>
                    @endforeach

                </tbody>
                
            </table>
        </div>

        <dialog x-bind:open="modalNuevoProveedor">      
            <article>
              <header>
                <button aria-label="Close" rel="prev" @click="modalNuevoProveedor = !modalNuevoProveedor"></button>
                <p>
                  <strong>Nuevo Proveedor</strong>
                </p>
              </header>
    
                @if (session('mensaje'))
                    <article>
                        <p>
                            {{ session('mensaje') }}    
                        </p>     
                    </article>
                @endif
    
                <form wire:submit="guardarProveedor">
                    <fieldset>
                        <label>
                            Nombre
                            <input
                            type="text"
                            name=""
                            placeholder="Nombre Proveedor"
                            wire:model="nombre"
                            @error('nombre') aria-invalid="true" @enderror
                            />
                            @error('nombre') 
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
                            placeholder="Cuit"
                            wire:model="cuit"
                            @error('cuit') aria-invalid="true" @enderror
                            />
                            @error('cuit') 
                                <small id="invalid-helper">
                                    {{ $message }}  
                                </small>
                            @enderror
                        </label>

                    </fieldset>
                
                    <input
                    type="submit"
                    value="Guardar Proveedor"
                    />
                </form>
    
                <button @click="modalNuevoProveedor = !modalNuevoProveedor">Cancelar</button>
            </article>
        </dialog>

    </div>
</div>
