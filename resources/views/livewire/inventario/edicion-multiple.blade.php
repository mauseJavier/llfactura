<div>
    <div class="container">
        <h3>Edicion Multiple</h3>
        <article>
            <div class="grid">
                <div class="col">
                    <fieldset role="group">
                      
                            <input wire:keyup="actualizar" wire:model="porcentajePrecio1"  type="text" placeholder="% Precio 1"/>
                     
                            <input wire:keyup="actualizar" wire:model="porcentajePrecio2" type="text" placeholder="% Precio 2" />
                       
                            <input wire:keyup="actualizar" wire:model="porcentajePrecio3"  type="text" placeholder="% Precio 3" />
                        
                    </fieldset>
                    @error('porcentajePrecio1') {{ $message }} <br> @enderror
                    @error('porcentajePrecio2') {{ $message }} <br> @enderror
                    @error('porcentajePrecio3') {{ $message }} <br> @enderror
                </div>
                <div class="col">
                    <button wire:click="modificarPrecio" wire:confirm="Serguro de Actualizar?">Actualizar</button>
                </div>
                <div class="col">
                    <form role="search"  wire:submit="">      
                        
                        <input wire:model.live="datoBuscado" name="search" type="search" placeholder="Buscar en Inventario" class="seleccionarTodo" />
                        {{-- <input type="submit" value="Buscar" /> --}}
                    </form>
                </div>
            </div>
        </article>

        <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    {{-- <th scope="col">id</th> --}}
                    <th scope="col">Codigo</th>
                    <th scope="col">Detalle</th>

                    <th scope="col">Precio 1</th>
                    <th scope="col">X %{{$porcentaje1}}</th>
                    <th scope="col">Precio 2</th>
                    <th scope="col">X %{{$porcentaje2}}</th>
                    <th scope="col">Precio 3</th>
                    <th scope="col">X %{{$porcentaje3}}</th>
                    
                    <th scope="col">Costo</th>
                    <th scope="col">Iva</th>
                    <th scope="col">Rubro</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">Marca</th>


                  </tr>
                </thead>
                <tbody>

                    @foreach ($inventario as $i)
                        
                        <tr>
                        {{-- <th scope="row">{{$i->id}}</th> --}}
                        <td>{{$i->codigo}}</td>
                        <td>{{$i->detalle}}</td>
                        <td style="color: green;">${{$i->precio1}}</td>
                        <td style="color: red;">${{$i->NuevoPrecio1}}</td>
                        <td style="color: green;">${{$i->precio2}}</td>
                        <td style="color: red;">${{$i->NuevoPrecio2}}</td>
                        <td style="color: green;">${{$i->precio3}}</td>
                        <td style="color: red;">${{$i->NuevoPrecio3}}</td>                                                 
                        <td>{{$i->costo}}</td>
                        <td>{{$i->iva}}</td>
                        <td>{{$i->rubro}}</td>
                        <td>{{$i->proveedor}}</td>
                        <td>{{$i->marca}}</td>
                        
                        </tr>
                    @endforeach

            </table>
        </div>

        {{ $inventario->links('vendor.livewire.bootstrap') }}

    </div>
</div>
