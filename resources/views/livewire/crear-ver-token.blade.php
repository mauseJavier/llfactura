<div>
    <h2 class="text-lg font-bold mb-4">Gestión de Token de Acceso</h2>

    @if ($tokenActual)
        <div class="mb-4">
            <p class="bg-gray-100 p-2 rounded border break-all">{{ $tokenActual }}</p>
            <button wire:click="generarToken" class="bg-blue-500 text-white px-4 py-2 rounded">Regenerar Token</button>
        </div>
    @else
        <button wire:click="generarToken" class="bg-green-500 text-white px-4 py-2 rounded">Generar Token</button>
    @endif
</div>
