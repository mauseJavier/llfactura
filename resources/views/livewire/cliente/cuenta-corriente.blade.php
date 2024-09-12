<div x-data="{ modalPago: false }">

    {{-- @dd($saldo) --}}

    <div class="container">
        <h3>Cuenta Corriente</h3>
        <h6>{{$cliente->razonSocial}} (${{$saldo}})</h6>

        <article>
            <div class="grid">
                <div class="col">
                    <h3>Saldo</h3>
                    <h1 style="color: red;">${{$saldo}}</h1>
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
                    <th scope="col">Comp.</th>
                    <th scope="col">VER</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Debe</th>
                    <th scope="col">Haber</th>
                    <th scope="col">Saldo</th>  
                    <th scope="col">Usuario</th>    
  
    
    
                  </tr>
                </thead>
                <tbody>
                    @foreach ($movimientos as $c)
                        <tr>
                            <td>{{$c->created_at}}</td>
                            <td>{{$c->comprobante_id}}</td>
                            @if ($c->tipo == 'venta')
                                
                                <td><a target="_blank" rel="noopener noreferrer" href="{{route('reciboPdf',['recibo_id'=>$c->id])}}">Ver Comp. {{$c->comprobante_id}}</a> </td>
                            @else
                                <td><a target="_blank" rel="noopener noreferrer" href="{{route('reciboPdf',['recibo_id'=>$c->id])}}">Ver Pago {{$c->id}}</a> </td>
                                
                            @endif
                            <td>{{$c->comentario}}</td>
                            <td>{{$c->debe}}</td>
                            <td>{{$c->haber}}</td>
                            <td>{{$c->saldo}}</td>
                            <td>{{$c->usuario}}</td>

    
    
    
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
            <form wire:submit="pagar">
                <fieldset>
                <label>
                    Comentario
                    <input
                    wire:model="comentario"
                    name="comentario"
                    placeholder="Ingrese un Comentario"
                    
                    @error('comentario') 
                        aria-invalid="true"
                        aria-describedby="invalid-helper"
                    @enderror

                    >
                @error('comentario') 
                    <small id="invalid-helper">
                        {{ $message }}
                    </small>
                @enderror



                </label>
                <label>
                    Importe Pagado
                    <input
                    wire:model="importePagado"
                    type="text"
                    name="pago"
                    placeholder="Ingrese un Importe"
                    
                    @error('importePagado') 
                        aria-invalid="true"
                        aria-describedby="invalid-helper"
                    @enderror

                    >
                @error('importePagado') 
                    <small id="invalid-helper">
                        {{ $message }}
                    </small>
                @enderror
                
                </label>
                </fieldset>
                

                <input type="button" value="Cancelar"
                    @click="modalPago = !modalPago"
                    style="background-color: red;">
                <input
                type="submit"
                value="Realizar Pago"
                />
            </form>
        </article>

    </dialog>



</div>
