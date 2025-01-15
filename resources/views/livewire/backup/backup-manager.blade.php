<div>
    <button  wire:loading.remove wire:click="runBackup" class="btn btn-primary">Crear Backup</button>

    <progress wire:loading> 
    </progress>



    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif


    <hr>

    <h1 class="text-lg font-bold">Lista de Archivos</h1>

    @if(session()->has('error'))
        <div class="text-red-500">{{ session('error') }}</div>
    @endif

    <ul class="list-disc ml-5">
        @forelse($files as $file)
            <li class="mb-2">
                {{ basename($file) }} 
                <button wire:click="download('{{ $file }}')" 
                        class="ml-2 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                    Descargar
                </button>

                <button wire:click="delete('{{ basename($file) }}')" 
                    style="background-color: red;">
                    Borrar
                </button>


            </li>
        @empty
            <li>No hay archivos disponibles.</li>
        @endforelse
    </ul>
</div>

