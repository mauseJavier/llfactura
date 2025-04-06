<div>

    <div class="container">
        <h3>Historial de Orden de Compra</h3>
        


        {{-- @dump($ordenes) --}}
        {{-- "id" => 1
        "numeroDeOrden" => null
        "fecha" => null
        "empresa" => null
        "proveedor" => "campos"
        "cuit_proveedor" => ""
        "direccion_proveedor" => ""
        "email_proveedor" => ""
        "subtotal" => "2530.38"
        "iva" => "0.00"
        "total" => "2530.38"
        "empresa_id" => 1
        "usuario" => "MAUSE LLFACTURA"
        "usuario_id" => 1
        "estado" => "pendiente"
        "created_at" => "2025-04-03 22:14:34"
        "updated_at" => "2025-04-03 22:14:34" --}}

        {{-- recibir mensaje y error de mensaje flash --}}
        @if (session()->has('mensaje'))
            <div class="alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <article>
          <a wire:navigate role="button" href="{{Route('ordenCompra')}}">Nueva Orden</a>
        </article>

        <div class="overflow-auto">

            <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">IVA</th>
                    <th scope="col">Total</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($ordenes as $orden)
                  <tr>
                    <td>{{ $orden->id }}</td>
                    <td>{{ $orden->created_at }}</td>
                    <td>{{ $orden->proveedor }}</td>
                    <td>{{ $orden->subtotal }}</td>
                    <td>{{ $orden->iva }}</td>
                    <td>{{ $orden->total }}</td>
                    <td>{{ $orden->estado }}</td>
                    <td>
                      <!-- Dropdown -->
                        <details class="dropdown">
                          <summary>Opciones</summary>
                          <ul>
                            <li>
                              <a role="button" href="{{Route('reImprimirOrdenCompra',['id'=>$orden->id])}}" target="_blank" rel="noopener noreferrer">Imprimir</a>
                            </li>
                            <li><a role="button" wire:click="eliminarOrdenCompra({{ $orden->id }})" style="background-color: red">Eliminar</a></li>

                          </ul>
                        </details>
                      {{-- <details class="dropdown">
                        <summary role="button">
                          Dropdown as a button
                        </summary>
                        <ul>
                          <a role="button" href="{{Route('reImprimirOrdenCompra',['id'=>$orden->id])}}" target="_blank" rel="noopener noreferrer">Imprimir</a>
                          <a role="button" href="{{Route('reImprimirOrdenCompra',['id'=>$orden->id])}}" target="_blank" rel="noopener noreferrer">ELiminar</a>

                        </ul>
                      </details>


                        {{-- colocar los botones de acciones en un select     
                        <select class="form-select" aria-label="Default select example">
                          <option value="" selected disabled>Opciones</option>
                            <option>
                              <a role="button" href="{{Route('reImprimirOrdenCompra',['id'=>$orden->id])}}" target="_blank" rel="noopener noreferrer">Imprimir</a>
                            </option>
                            <option value="1">
                              <button>Eliminar</button>
                            </option>
                        </select>    --}}
                    </td>
                  </tr> 
                  @endforeach
                </tbody>

            </table>

          </div>


    </div>

</div>
