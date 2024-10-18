<div>

    {{-- @dump($empresas) --}}
    <div class="container-fluid">
    
        <h3>Estado Empresas</h3>

          <article>
            <button wire:click="exportarCSV()">Exportar</button>
          </article>


        <div class="overflow-auto">
            <table>
                <thead>
                <tr>
                    <th scope="col" style="color: red;">id</th>
                    <th scope="col" style="color: red;">RazonSocial</th>
                    <th scope="col" style="color: red;">CUIT</th>
                    <th scope="col" style="color: red;">TEL</th>
                    <th scope="col" style="color: red;">TOTAL MES</th>
                    
                </tr>
                </thead>
                <tbody>
                    @foreach ($empresas as $key => $c)                        
                        <tr>
                            <th scope="row">{{$c->id}}</th>
                            <th scope="row">{{$c->razonSocial}}</th>
                            <th scope="row">{{$c->cuit}}</th>

                            <th scope="row">
                                <a href="https://wa.me/54{{$c->telefono}}" target="_blank" rel="noopener noreferrer">{{$c->telefono}}</a>
                            </th>

                            <th scope="row">${{ number_format( $c->totalFacturado ,2 )}}</th>


                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
        {{ $empresas->links('vendor.livewire.bootstrap') }}

    </div>
</div>
