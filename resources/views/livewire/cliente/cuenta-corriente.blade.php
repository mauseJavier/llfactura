<div x-data="{ modalPago: false }">

    <div class="container">
        <h3>Cuenta Corriente</h3>
        <h6>{{$cliente->razonSocial}} (${{$saldo[0]->saldo}})</h6>

        <article>
            <div class="grid">
                <div class="col">
                    <h3>Saldo</h3>
                    <h1 style="color: red;">${{$saldo[0]->saldo}}</h1>
                </div>
                <div class="col">
                    <label for="">
                        Desde
                        <input type="date" wire:model.live="fechaDesde" name="date" aria-label="Date">
                    </label>
                </div>
                <div class="col">                      
                    <label for="">
                        
                        <button @click="modalPago = !modalPago">Realizar Pago</button>

                    </label>
                </div>
            </div>
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
                    <th scope="col">Fecha</th>
                    <th scope="col">Comprobate</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Debe</th>
                    <th scope="col">Haber</th>
                    <th scope="col">Saldo</th>    
    
    
                  </tr>
                </thead>
                <tbody>
                    @foreach ($movimientos as $c)
                        <tr>
                            <td>{{$c->created_at}}</td>
                            <td>{{$c->comprobante_id}}</td>
                            <td>{{$c->tipo}}</td>
                            <td>{{$c->comentario}}</td>
                            <td>{{$c->debe}}</td>
                            <td>{{$c->haber}}</td>
                            <td>{{$c->saldo}}</td>
    
    
    
                        </tr>
                    @endforeach
    
                </tbody>
                
              </table>
          </div>
          {{ $movimientos->links('vendor.livewire.bootstrap') }}




    </div>


    <dialog x-bind:open="modalPago">
        <article>
          <header>
            <button aria-label="Close" rel="prev" @click="modalPago = !modalPago"></button>
            <p>
              <strong>Realizar un Pago</strong>
            </p>
          </header>
            <form>
                <fieldset>
                <label>
                    Comentario
                    <input
                    name="comentario"
                    placeholder="Ingrese un Comentario"
                    
                    />
                </label>
                <label>
                    Importe Pagado
                    <input
                    type="text"
                    name="pago"
                    placeholder="Ingrese un Importe"
                    
                    />
                </label>
                </fieldset>
            
                <input
                type="submit"
                value="Realizar Pago"
                />
            </form>
        </article>

    </dialog>



</div>
