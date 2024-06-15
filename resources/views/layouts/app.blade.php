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

      @media (max-width: 1000px) {
            .esconderCelular {
                display: none;
            }
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

    <dialog id="modal-example">
      <article>
        <header>
          <button
            aria-label="Close"
            rel="prev"
            data-target="modal-example"
            onclick="toggleModal(event)">
          </button>
          <p>
            <strong>🗓️ Thank You for Registering!</strong>
          </p>
        </header>
        <p>
          We're excited to have you join us for our
          upcoming event. Please arrive at the museum 
          on time to check in and get started.
        </p>
        <ul>
          <li>Date: Saturday, April 15</li>
          <li>Time: 10:00am - 12:00pm</li>
        </ul>
      </article>
    </dialog>

  </body>
</html>

