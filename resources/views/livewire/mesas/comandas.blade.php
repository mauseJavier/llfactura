<div >

    <div class="container" >
        <h3>Comandas</h3>
        <button wire:click="borrarTodo" wire:confirm="Esta Seguro">Borrar Todo!</button>


        <div  class="overflow-auto" x-data="{
            estadoColors: {
                'Nuevo': 'font-weight: bold; color: green',
                'Impreso': 'font-weight: bold; color:rgb(109, 184, 176)',
                'cancelado': 'font-weight: bold; color:red'
            }
        }">
            <table class="striped" wire:poll>
                <thead>
                    <tr>
                        <th scope="col">Estado</th>
                        <th scope="col">Id</th>
                        <th scope="col">Mesa</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Mesero</th>
                        <th scope="col">Fecha H.</th>
                        <th scope="col">Modificado</th>
                        <th scope="col">Imp</th>
                        <th scope="col">Borrar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comandas as $c)
                        <tr>
                            <td x-bind:style="estadoColors['{{ $c->estado }}']">{{ $c->estado }}</td>
                            <th x-bind:style="estadoColors['{{ $c->estado }}']">{{ $c->id }}</th>
                            <td x-bind:style="estadoColors['{{ $c->estado }}']">{{ $c->numeroMesa }}</td>
                            <td x-bind:style="estadoColors['{{ $c->estado }}']">{{ $c->nombreMesa }}</td>
                            <td x-bind:style="estadoColors['{{ $c->estado }}']">{{ $c->nombreMesero }}</td>
                            <td x-bind:style="estadoColors['{{ $c->estado }}']">{{ $c->created_at }}</td>
                            <td x-bind:style="estadoColors['{{ $c->estado }}']">{{ $c->updated_at }}</td>
                            <td>
                                <button wire:click="imprimir({{ $c->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                                        <path d="M 80 160 L 64 160 L 80 160 L 64 160 L 64 64 L 64 64 Q 65 37 83 19 Q 101 1 128 0 L 358 0 L 358 0 Q 384 0 403 19 L 429 45 L 429 45 Q 448 64 448 91 L 448 160 L 448 160 L 432 160 L 432 160 L 432 91 L 432 91 Q 432 71 418 57 L 391 30 L 391 30 Q 377 16 358 16 L 128 16 L 128 16 Q 108 17 94 30 Q 81 44 80 64 L 80 160 L 80 160 Z M 448 208 L 64 208 L 448 208 L 64 208 Q 44 209 30 222 Q 17 236 16 256 L 16 384 L 16 384 Q 17 399 32 400 L 64 400 L 64 400 L 64 352 L 64 352 Q 64 338 73 329 Q 82 320 96 320 L 416 320 L 416 320 Q 430 320 439 329 Q 448 338 448 352 L 448 400 L 448 400 L 480 400 L 480 400 Q 495 399 496 384 L 496 256 L 496 256 Q 495 236 482 222 Q 468 209 448 208 L 448 208 Z M 448 416 L 448 480 L 448 416 L 448 480 Q 448 494 439 503 Q 430 512 416 512 L 96 512 L 96 512 Q 82 512 73 503 Q 64 494 64 480 L 64 416 L 64 416 L 32 416 L 32 416 Q 18 416 9 407 Q 0 398 0 384 L 0 256 L 0 256 Q 1 229 19 211 Q 37 193 64 192 L 448 192 L 448 192 Q 475 193 493 211 Q 511 229 512 256 L 512 384 L 512 384 Q 512 398 503 407 Q 494 416 480 416 L 448 416 L 448 416 Z M 96 336 Q 81 337 80 352 L 80 480 L 80 480 Q 81 495 96 496 L 416 496 L 416 496 Q 431 495 432 480 L 432 352 L 432 352 Q 431 337 416 336 L 96 336 L 96 336 Z M 432 256 Q 447 257 448 272 Q 447 287 432 288 Q 417 287 416 272 Q 417 257 432 256 L 432 256 Z"/>
                                    </svg>
                                </button>
                            </td>
                            <td>
                                <button style="background-color: red;" wire:click="borrar({{ $c->id }})" wire:confirm="Esta Seguro?">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                                        <path d="M 210 16 L 302 16 L 210 16 L 302 16 Q 315 16 323 27 L 346 64 L 346 64 L 166 64 L 166 64 L 189 27 L 189 27 Q 197 16 210 16 L 210 16 Z M 176 19 L 148 64 L 176 19 L 148 64 L 40 64 L 40 64 Q 33 65 32 72 Q 33 79 40 80 L 472 80 L 472 80 Q 479 79 480 72 Q 479 65 472 64 L 364 64 L 364 64 L 336 19 L 336 19 Q 324 1 302 0 L 210 0 L 210 0 Q 188 1 176 19 L 176 19 Z M 80 119 Q 79 112 71 112 Q 64 113 64 121 L 92 461 L 92 461 Q 95 483 110 497 Q 126 512 148 512 L 364 512 L 364 512 Q 386 512 402 497 Q 417 483 420 461 L 448 121 L 448 121 Q 448 113 441 112 Q 433 112 432 119 L 404 459 L 404 459 Q 402 475 391 485 Q 380 496 364 496 L 148 496 L 148 496 Q 132 496 121 485 Q 110 475 108 459 L 80 119 L 80 119 Z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">Sin comandas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <dialog {{$modalImprimir}}>
        <article>
          <header>
            <button 
                aria-label="Close" rel="prev"     
                wire:click="cerrarModal"                
                >
            </button>
            <p>
              <strong>Imprimir</strong>
            </p>
          </header>          
          <iframe width="100%" height="1000px" src="{{route('imprimirComanda',['comanda'=>$id])}}" frameborder="0"></iframe>
        </article>
      </dialog>
          




</div>
