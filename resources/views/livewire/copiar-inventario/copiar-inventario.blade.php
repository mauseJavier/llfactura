<div class="container">
    <h3>Copiar Inventario</h3>
    @if (session('mensaje'))
        <div class="alert alert-success">{{ session('mensaje') }}</div>
    @endif



    <div >
        <progress wire:loading>
        </progress>  
            
    </div>
    


    <div >

        <label>
            Empresa Origen (empresa_id):
            <input type="number" wire:model.live="empresa_id_origen" required wire:keyUp="buscarEmpresaOrigen">
        </label>
        <br>
        {{-- Mostrar mensaje de error si la empresa origen no se encuentra --}}
    
            @if ($empresaOrigen)
                <p>Empresa Origen: {{ $empresaOrigen->razonSocial ?? 'No encontrada' }}</p>
                
            @endif
    
        <hr>
    
        <label>
            Empresa Destino (empresa_id):
            <input type="number" wire:model.live="empresa_id_destino" required wire:keyUp="buscarEmpresaDestino">
        </label>
        <br>
        {{-- Mostrar mensaje de error si la empresa destino no se encuentra --}}
            @if ($empresaDestino)
                <p>Empresa Destino: {{ $empresaDestino->razonSocial ?? 'No encontrada' }}</p>
            @endif
        <hr>
        <label>
            Cantidad de artículos a copiar:
            <input type="number" wire:model.live="cantidad" min="1" required>
        </label>
        <br>
    
        <form wire:submit.prevent="copiar">
            <button type="submit" class="btn btn-primary">Confirmar y Copiar</button>
        </form>
    
            @if (!empty($errores))
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errores as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    
    @if(count($articulosPreview) > 0)
        <hr>
        <h4>Artículos a copiar (previsualización)</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Detalle</th>
                    <th>Rubro</th>
                    <th>Proveedor</th>
                    <th>Marca</th>
                    <th>Actualizado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articulosPreview as $art)
                    <tr>
                        <td>{{ $art->codigo }}</td>
                        <td>{{ $art->detalle }}</td>
                        <td>{{ $art->rubro ?? 'N/A' }}</td>
                        <td>{{ $art->proveedor ?? 'N/A' }}</td>
                        <td>{{ $art->marca ?? 'N/A' }}</td>
                        <td>{{ $art->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
          {{-- {{ $articulosPreview->links('vendor.livewire.bootstrap')}} --}}
    
    
    @endif


    </div>



</div>
