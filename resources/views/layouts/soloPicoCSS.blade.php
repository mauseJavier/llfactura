<!doctype html>
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
  </head>
  <body>
            
    @yield('body', 'Default content')


  </body>
</html>