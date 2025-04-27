<div>

    <div class="container">
        <h3>Novedades</h3>

@if ($usuario->role_id == 3)
    
        <article>

                <fieldset>
                  <label>
                    {{$id}}- Titulo
                    <input
                    wire:model="titulo"
                      name="titulo"
                      placeholder="Titulo"
                      autocomplete="titulo"
                    />
                  </label>
                  <label for="">
                    Detalle
                    <textarea wire:model="detalle" name="detalle" id="" cols="30" rows="5"></textarea>
                  </label>
                  <label>
                    Nombre Ruta (Si la ruta no exite larga error!)
                    <input
                    wire:model="nombreRuta"
                      type="text"
                      name="nombreRuta"
                      placeholder="Nombre Ruta"
                      autocomplete="nombreRuta"
                    />
                  </label>
                  <label>
                    URL Externa
                    <input
                    wire:model="url"
                      type="text"
                      name="url"
                      placeholder="URL Externa"
                      autocomplete="url"
                    />
                  </label>
                  <label>
                    Pie de Tarjeta
                    <input
                    wire:model="pie"

                      type="text"
                      name="pie"
                      placeholder="Pie de Tarjeta"
                      autocomplete="pie"
                    />
                  </label>
                  <label>
                    Aplicar a:
                    <select wire:model.live="aplicarA">
                        <option value="todas">Todas las empresas</option>
                        <option value="una">Una empresa específica</option>
                    </select>
                  </label>

                      @if ($aplicarA === 'una')
                          <label>
                              Empresa:
                              <select wire:model="empresaId">
                                  <option value="">Seleccione una empresa</option>
                                  @foreach ($empresas as $empresa)
                                      <option value="{{ $empresa->id }}">{{ $empresa->id }} - {{ $empresa->razonSocial }}</option>
                                  @endforeach
                              </select>
                          </label>
                      @endif
                </fieldset>
              

                <button wire:click="guardarNovedad()">Guardar</button>
                
              </article>
@endif

          @if (session('mensaje'))
          <hr>
          <div class="alert alert-success">
              {{ session('mensaje') }}
          </div>
          @endif


        <hr>

        @foreach ($novedades as $item)

          <article>
              <header>
                <div class="grid" style="text-align: center;">
                  <h5><small>({{$item->id}})</small>-{{$item->titulo}}</h5>
                  <div class="btn-group" role="group" aria-label="Acciones" style="display: flex; justify-content: center; gap: 10px;">
                    @if ($usuario->role_id == 3)
                      <button class="btn btn-primary" wire:click="editar({{$item->id}})" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                          <path d="M 397.224171539961 38.92397660818713 Q 414.19103313840156 24.951267056530213 431.1578947368421 38.92397660818713 L 473.07602339181284 80.84210526315789 L 473.07602339181284 80.84210526315789 Q 487.04873294346976 97.80896686159844 473.07602339181284 114.77582846003898 L 419.1812865497076 169.66861598440545 L 419.1812865497076 169.66861598440545 L 342.33138401559455 92.81871345029239 L 342.33138401559455 92.81871345029239 L 397.224171539961 38.92397660818713 L 397.224171539961 38.92397660818713 Z M 319.3762183235867 115.77387914230019 L 396.2261208576998 192.62378167641324 L 319.3762183235867 115.77387914230019 L 396.2261208576998 192.62378167641324 L 161.68421052631578 427.16569200779725 L 161.68421052631578 427.16569200779725 Q 149.70760233918128 438.14424951267057 133.73879142300194 443.1345029239766 L 41.91812865497076 470.08187134502924 L 41.91812865497076 470.08187134502924 L 68.86549707602339 378.26120857699806 L 68.86549707602339 378.26120857699806 Q 73.85575048732943 362.2923976608187 84.83430799220272 350.3157894736842 L 319.3762183235867 115.77387914230019 L 319.3762183235867 115.77387914230019 Z M 453.11500974658867 15.968810916179336 Q 436.14814814814815 0 414.19103313840156 0 L 414.19103313840156 0 L 414.19103313840156 0 Q 391.23586744639374 0 374.2690058479532 15.968810916179336 L 61.87914230019493 328.35867446393763 L 61.87914230019493 328.35867446393763 Q 44.91228070175438 345.32553606237815 37.925925925925924 369.27875243664715 L 2.9941520467836256 490.0428849902534 L 2.9941520467836256 490.0428849902534 Q 0 499.02534113060426 6.98635477582846 505.0136452241715 Q 12.97465886939571 512 22.955165692007796 509.00584795321635 L 143.71929824561403 474.0740740740741 L 143.71929824561403 474.0740740740741 Q 166.67446393762182 467.0877192982456 183.64132553606237 449.12280701754383 L 496.03118908382066 137.73099415204678 L 496.03118908382066 137.73099415204678 Q 512 120.76413255360623 512 97.80896686159844 Q 512 75.85185185185185 496.03118908382066 58.8849902534113 L 453.11500974658867 15.968810916179336 L 453.11500974658867 15.968810916179336 Z"/>
                        </svg>
                      </button>
                      <button class="btn btn-danger" wire:click="eliminar({{$item->id}})" title="Eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                          <path d="M 210 32 L 302 32 L 210 32 L 302 32 Q 311 32 316 40 L 331 64 L 331 64 L 181 64 L 181 64 L 196 40 L 196 40 Q 201 32 210 32 L 210 32 Z M 369 64 L 343 23 L 369 64 L 343 23 Q 328 1 302 0 L 210 0 L 210 0 Q 184 1 169 23 L 143 64 L 143 64 L 96 64 L 48 64 Q 33 65 32 80 Q 33 95 48 96 L 66 96 L 66 96 L 92 453 L 92 453 Q 94 478 112 495 Q 130 511 156 512 L 356 512 L 356 512 Q 382 511 400 495 Q 418 478 420 453 L 446 96 L 446 96 L 464 96 L 464 96 Q 479 95 480 80 Q 479 65 464 64 L 448 64 L 369 64 Z M 414 96 L 388 450 L 414 96 L 388 450 Q 387 463 378 471 Q 369 480 356 480 L 156 480 L 156 480 Q 143 480 134 471 Q 125 463 124 450 L 98 96 L 98 96 L 414 96 L 414 96 Z"/>
                        </svg>
                      </button>
                    @endif

                    @if ($usuario->role_id == 4)
                      <button class="btn btn-danger" wire:click="eliminar({{$item->id}})" title="Eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                          <path d="M 210 32 L 302 32 L 210 32 L 302 32 Q 311 32 316 40 L 331 64 L 331 64 L 181 64 L 181 64 L 196 40 L 196 40 Q 201 32 210 32 L 210 32 Z M 369 64 L 343 23 L 369 64 L 343 23 Q 328 1 302 0 L 210 0 L 210 0 Q 184 1 169 23 L 143 64 L 143 64 L 96 64 L 48 64 Q 33 65 32 80 Q 33 95 48 96 L 66 96 L 66 96 L 92 453 L 92 453 Q 94 478 112 495 Q 130 511 156 512 L 356 512 L 356 512 Q 382 511 400 495 Q 418 478 420 453 L 446 96 L 446 96 L 464 96 L 464 96 Q 479 95 480 80 Q 479 65 464 64 L 448 64 L 369 64 Z M 414 96 L 388 450 L 414 96 L 388 450 Q 387 463 378 471 Q 369 480 356 480 L 156 480 L 156 480 Q 143 480 134 471 Q 125 463 124 450 L 98 96 L 98 96 L 414 96 L 414 96 Z"/>
                        </svg>
                      </button>
                    @endif
                    
                  </div>
                </div>
              </header>
              
              {{$item->detalle}}

              <hr>
              
              <ul>
                @if ($item->nombreRuta != '')                    
                  <li>
                    <a href="{{$item->nombreRuta}}">{{$item->titulo}}</a>
                    
                  </li>
                @endif
                @if ($item->url != '')                    
                  <li>
                    <a href="{{$item->url}}" target="_blank" rel="noopener noreferrer">{{$item->url}}</a>
                  </li>
                @endif
              </ul>
              <footer>{{$item->pie}}</footer>
          </article>
            
        @endforeach



    </div>
</div>
