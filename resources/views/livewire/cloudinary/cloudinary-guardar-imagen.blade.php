<div>
    <form wire:submit.prevent="guardar">
        <input type="file" wire:model="imagen">
        @error('imagen') <span class="error">{{ $message }}</span> @enderror
        <br>
        <input type="text" wire:model="nombreImagen" placeholder="Nombre de la imagen (opcional)">
        <br>
        <button type="submit">Subir Imagen</button>
    </form>
</div>
