<div x-data>


    <label for="">
        Buscar Cliente (Nombre-CUIT)
        <input type="text" wire:keydown="busquedaDeCliente" wire:model.live="clienteBuscado" placeholder="Buscar Cliente..."
            x-on:click="event.target.select()"

        >
    </label>
    
    @if(!empty($resultados) AND !empty($clienteBuscado))

        @foreach($resultados as $c)
            <article wire:click="seleccionar({{$c->id}},'{{$c->razonSocial}}')" >{{ $c->razonSocial }} ({{ $c->numeroDocumento }})</article>
        @endforeach
    @endif
</div>
