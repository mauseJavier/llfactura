<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="color-scheme" content="light dark" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
    />

  </head>
  <body>
    <div class="container-fluid">
        <nav>
            <ul>
                <li>
                    {{ config('app.name', 'Laravel') }}
                </li>
    
            </ul>
            <ul>
                <li>
                    @if (Route::has('login'))
                        @auth
                            <a
                                href="{{ url('/panel') }}"
                                
                            >
                                Panel
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                    >
                                Entrar
                            </a>
            
                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    >
                                    Regristro
                                </a>
                            @endif
                        @endauth
                    @endif
                </li>
            </ul>
    
        </nav>

    </div>

    <main class="container">
      <h1>Hello world!</h1>
    </main>
  </body>
</html>