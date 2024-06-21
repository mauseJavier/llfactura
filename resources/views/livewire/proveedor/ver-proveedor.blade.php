<div>
    <div class="container">
        @dump($proveedores)
        <h3>Proveedores</h3>
        <article>
            <input type="search" name="" id="">
        </article>

        @if (session('mensaje'))
            <article>
                <p>
                    {{ session('mensaje') }}    
                </p>     
            </article>
        @endif


        <div class="overflow-auto">
            <table class="striped">
                <thead>
                <tr>
                    <th scope="col">Razon Social</th>
                    <th scope="col">Tipo Doc</th>
                    <th scope="col">Numero</th>
                    <th scope="col">Tipo Contr.</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Domicilio</th>



                </tr>
                </thead>
                <tbody>
                    @foreach ($proveedores as $p)
                        {{-- <tr>
                            <td>
                                <button wire:click="editarCliente({{$c->id}})" @click="modalNuevoCliente = !modalNuevoCliente">
                                    {{$c->razonSocial}}
                                </button>
                            </td>
                            <td>{{$c->tipoDocumento}}</td>
                            <td>{{$c->numeroDocumento}}</td>
                            <td>{{$c->tipoContribuyente}}</td>
                            <td>{{$c->correo}}</td>
                            <td>{{$c->domicilio}}</td>


                        </tr> --}}
                    @endforeach

                </tbody>
                
            </table>
        </div>

    </div>
</div>
