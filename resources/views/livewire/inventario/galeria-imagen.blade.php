<div>
    <h4>Galería de Imágenes</h4>
    @if(count($imagenes) > 0)
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
            @foreach($imagenes as $index => $img)
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <a href="{{ $img }}" target="_blank" rel="noopener noreferrer" style="margin-bottom: 0.5rem;">
                        <img src="{{ $img }}" alt="Imagen" style="max-width: 300px; max-height: 300px; border-radius: 8px; box-shadow: 0 2px 8px #ccc;">
                    </a>
                    <button wire:click="$parent.eliminarImagen({{ $index }})" class="btn btn-danger" style="width: 100%; max-width: 200px;">
                        Eliminar
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <p>No hay imágenes para este artículo.</p>
    @endif
</div>
