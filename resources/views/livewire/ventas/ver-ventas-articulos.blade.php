<div>

<div class="container">
    <h3>Ventas por Articulo</h3>

    <article>

        <form>
            <fieldset class="grid">
                <label for="">
                    Desde
                    <input type="date" name="date" aria-label="Date" wire:model.live="fechaDesde" wire:change="calcularVenta()">
                </label>
                <label for="">
                    Hasta
                    <input type="date" name="date" aria-label="Date" wire:model.live="fechaHasta" wire:change="calcularVenta()">
                </label>

            </fieldset>

            <fieldset class="grid">
                <label for="">
                    Codigo
                    <input type="text" wire:model.live="codigo" placeholder="Codigo" wire:keyup="calcularVenta()">
                </label>
                <label for="">
                    Detalle
                    <input type="text" wire:model.live="detalle" placeholder="Detalle" wire:keyup="calcularVenta()">
                </label>
                <label for="">
                    Rubro
                    <input type="text" wire:model.live="rubro" placeholder="Rubro" wire:keyup="calcularVenta()">
                </label>
                <label for="">
                    Proveedor
                    <input type="text" wire:model.live="proveedor" placeholder="Proveedor" wire:keyup="calcularVenta()">
                </label>

            </fieldset>
          </form>
    </article>

    <article>
        <h3>Suma de Venta: ${{$precioVenta}}</h3>
    </article>

    <table class="striped">
        <thead>
          <tr>
            <th scope="col">Codigo</th>
            <th scope="col">Detalle</th>
            <th scope="col">Cant.</th>
            <th scope="col">P. Lista</th>
            <th scope="col">P. Venta</th>
            <th scope="col">Descuento</th>
            <th scope="col">Rubro</th>
            <th scope="col">Proveedor</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($articulos as $a)
                
            <tr>
              <th scope="row">{{$a->codigo}}</th>
              <td>{{$a->detalle}}</td>
              <td>{{$a->cantidad}}</td>

              <td>{{$a->precioLista}}</td>
              <td>{{$a->precio}}</td>
              <td>{{$a->descuento}}</td>
              <td>{{$a->rubro}}</td>
              <td>{{$a->proveedor}}</td>
            </tr>
            @endforeach

        </tbody>
        <tfoot>
          <tr>

          </tr>
        </tfoot>
      </table>

      {{ $articulos->links('vendor.livewire.bootstrap') }}

</div>


</div>
