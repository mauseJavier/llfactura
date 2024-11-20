<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class=" modal-is-opening" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.green.min.css"
    />

    <script src="https://kit.fontawesome.com/d361ac2d93.js" crossorigin="anonymous"></script>
    
    <style>
      .tarjetaMenu{
        text-align: center;
        color: rgb(71, 164, 23);
      }


        /* para las sombras */
        .ponerSombras:hover {
          -webkit-box-shadow: 0px 0px 18px 4px rgba(245,108,108,1);
            -moz-box-shadow: 0px 0px 18px 4px rgba(245,108,108,1);
            box-shadow: 0px 0px 18px 4px rgba(245,108,108,1);
        }

        .ponerSombras{

          -webkit-box-shadow: 17px 20px 20px -15px rgba(146,227,134,1);
          -moz-box-shadow: 17px 20px 20px -15px rgba(146,227,134,1);
          box-shadow: 17px 20px 20px -15px rgba(146,227,134,1);

        }



    </style>
  </head>
  <body>
    <div class="container-fluid">
        @livewire('menu.MenuPrincipal')
    </div>


    <main class="container-fluid">
      @yield('main')
    </main>


    <dialog id="modal-example" 
        @if (
              $generalEmpresa->where('id', Auth()->user()->empresa_id)->pluck('pagoServicio')[0] == 0 
              AND 
              $generalEmpresa->where('id', Auth()->user()->empresa_id)->pluck('vencimientoPago')[0] < date('Y-m-d') 
            )
            open
        @endif
          >
            <article>
              <header>
                <button
                  aria-label="Close"
                  rel="prev"
                  onclick="document.getElementById('modal-example').close()">
                  
                </button>
                <p><strong>🗓️ Pago de Servicio Vencido!</strong></p>
              </header>
              <p>
                  Para continuar con el sistema, abone el servicio, gracias. ! 

              </p>

              <h3>Datos de Transferencia:</h3>

              <ul>
                <li>Javier Nicolas Desmaret</li>
                <li>CVU: 0000003100044706159615</li>
                <li>Alias: llfactura.com</li>
                <li>CUIT/CUIL: 20358337164</li>
                <li>Mercado Pago</li>
              </ul>
              <hr>
              <ul>
                <li>Marcelo Horacio Gimenez</li>
                <li>CVU: 0000003100035481206346</li>
                <li>Alias: LLfactura2024</li>
                <li>CUIT/CUIL: 20350796631</li>
                <li>Mercado Pago</li>
              </ul>
            </article>
      </dialog>


  </body>
</html>

