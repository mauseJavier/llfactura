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
                <label for="">
                    Marca
                    <input type="text" wire:model.live="marca" placeholder="Marca" wire:keyup="calcularVenta()">
                </label>

            </fieldset>
          </form>

          <h3>Suma de Venta: ${{$precioVenta}}</h3>
          <h4>IVA: ${{$iva}}</h4>
          <h4>Suma de Costo: ${{$costoVenta}}</h4>
          <h4>Resultado: ${{$resultadoVenta}} (%{{$resultadoPorcentaje}})</h4>


          <hr>
          <button wire:click="crearPDF()">Crear PDF</button>
          <button wire:click="exportarCSV()">Exportar CSV</button>


    </article>

    {{-- <article>
        <h3>Suma de Venta: ${{$precioVenta}}</h3>
    </article> --}}

    <div class="overflow-auto">
        <table class="striped">
            <thead>

              <tr>
                <th scope="col">Codigo</th>
                <th scope="col">Detalle</th>
                <th scope="col">Cant.</th>
                <th scope="col">Costo</th>
                <th scope="col">P.Lista</th>
                <th scope="col">Descuento</th>
                <th scope="col">P.Venta</th>
                <th scope="col">Rubro</th>
                <th scope="col">Proveedor</th>
                <th scope="col">Marca</th>
                <th scope="col">Fecha</th>


              </tr>
            </thead>
            <tbody>
                @foreach ($articulos as $a)
                    
                    {{-- 3, 8, 13, "notaRemito" documentos de nota de credito --}}
                    @if ($a->tipoComp == 3 OR $a->tipoComp == 8 OR $a->tipoComp == 13 OR $a->tipoComp == "notaRemito")
                        <tr>
                            <th scope="row">{{$a->codigo}} ({{$a->tipoComp}})</th>
                            <td>{{$a->detalle}}</td>
                            <td>{{$a->cantidad}}</td>
            
                            <td>$-{{number_format($a->costo,2,',','.')}}</td>
                            <td>$-{{number_format($a->precio,2,',','.')}}</td>
                            <td>${{number_format($a->descuento)}}</td>
                            <td>${{number_format($a->precioLista,2,',','.')}}</td>
                            <td>{{$a->rubro}}</td>
                            <td>{{$a->proveedor}}</td>
                            <td>{{$a->marca}}</td>
                            <td>{{$a->fecha}}</td>    
                        </tr>
                    @else
                        
                        <tr>
                            <th scope="row">{{$a->codigo}}</th>
                            <td>{{$a->detalle}}</td>
                            <td>{{$a->cantidad}}</td>
                
                            <td>${{number_format($a->costo,2,',','.')}}</td>
                            <td>${{number_format($a->precioLista,2,',','.')}}</td>
                            <td>${{number_format($a->descuento)}}</td>
                            <td>${{number_format($a->precio,2,',','.')}}</td>
                            <td>{{$a->rubro}}</td>
                            <td>{{$a->proveedor}}</td>
                            <td>{{$a->marca}}</td>
                            <td>{{$a->fecha}}</td>
                        </tr>
                    @endif
                @endforeach
    
            </tbody>
            <tfoot>
              <tr>
    
              </tr>
            </tfoot>
          </table>
      </div>
      

 

      {{ $articulos->links('vendor.livewire.bootstrap') }}

</div>


</div>
