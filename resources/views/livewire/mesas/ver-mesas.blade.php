<div>

    <div class="container">

        <div class="grid">
            <h3>Mesas:</h3>

            <input
                type="search"
                name="buscarMesa"
                placeholder="Buscar Mesa"
                aria-label="Search"
                wire:model.live="buscarMesa"
            />


            <select name="favorite-cuisine" aria-label="Select your favorite cuisine..." required>
                <option selected disabled value="">
                  Select your favorite cuisine...
                </option>
                <option>Italian</option>
                <option>Japanese</option>
                <option>Indian</option>
                <option>Thai</option>
                <option>French</option>
              </select>

        </div>
    </div>

    {{-- @dd($mesas) --}}

    @foreach ($sector as $s)
        <details open>
            <summary>{{$s->nombre}}</summary>
            
            <div class="grid"> <!-- Aseguramos que siempre hay un grid al inicio -->
            @foreach ($mesas as $item)

                @if ($loop->index % 4 == 0 && !$loop->first)
                    </div> <!-- Cierra la grid anterior -->
                    <div class="grid"> <!-- Abre una nueva grid -->
                @endif

                @if ($item->sector == $s->id)

                    <div class="col">
                        <article 
                            x-data="{ numero: '{{$item->numero}}' }"
                            :style="numero != '{{$item->numero}}' ? 'background-color: red; text-align: center;' : 'background-color: green; text-align: center;'" 
                            wire:click="modificarMesa('{{$item->id}}')"
                        >
                            {{$item->nombre}} -
                            {{$item->id}}

                        </article>
                    </div>
                @endif
            @endforeach
            </div> <!-- Siempre cerramos la grid -->
        </details>
    @endforeach


    {{-- @foreach ($sector as $s)

    <article>
        <header>{{$s->nombre}} - {{$s->id}}</header>

        
        @foreach ($mesas as $item)

        @if ($item->sector == $s->id)
            
                    @if ($loop->index % 4 == 0)
                        @if (!$loop->first)
                            </div> <!-- Cierra la grid anterior -->
                        @endif
                        <div class="grid"> <!-- Abre una nueva grid cada 6 elementos -->
                    @endif

                <div class="col">
                    <article 
                        x-data="{ codigo: '{{$item->numero}}' }"
                        :style="codigo == '{{$item->numero}}' ? 'background-color: red; text-align: center;' : 'background-color: green; text-align: center;'" 
                        wire:click="cargarFavorito(
                                                    '{{$item->nombre}}',
                                                    '{{$item->capacidad}}',
                                                    '{{$item->titular}}',
                                                    '{{$item->estado}}',
                                                    '{{$item->usuario}}',
                                                    '{{$item->sector}}')"
                        >
                        {{$item->nombre}}
                    </article>
                </div>
        @endif

            
            @if ($loop->last)
                </div> <!-- Cierra la última grid -->
            @endif
        @endforeach


        </article>

    @endforeach --}}


        


</div>
