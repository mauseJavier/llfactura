<div>
    <div class="grid"
    wire:poll.5s

  >
  @php $count = 0; @endphp

    @foreach ($mesas as $item)
        @if ($item->sector == $idSector)
            @if ($count > 0 && $count % 4 == 0)
                </div><div class="grid">
            @endif


            <div class="col">
                <article 

                    wire:poll.5s

                    wire:key="mesa-{{ $item->id }}"
                    x-data="{ numero: '{{ $item->numero }}' }"
                    :style="'' == '{{ $item->data }}' ? 'background-color: rgb(56, 56, 171); text-align: center; cursor:pointer;' : 'background-color: green; text-align: center; cursor:pointer;'"
                    wire:click="modificarMesa('{{ $item->id }}')"
                >
                    {{ $item->nombre }} - {{ $item->numero }} ({{ $item->razonSocial }} - ${{ number_format($item->total, 2, ',', '.') }})
                </article>
            </div>


            @php $count++; @endphp
        @endif
    @endforeach
  </div>
                       
</div>

