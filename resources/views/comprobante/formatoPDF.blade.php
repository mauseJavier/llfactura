@extends('layouts.app')

@section('main')

<div class="container" x-data="{ selectedOption: 'ticket' }">


    <article>
      <header>Formato PDF</header>
      <a href="{{route('nuevoComprobante')}}" role="button" wire:navigate>Nueva Factura</a>
      <a href="{{route('comprobante')}}" role="button" wire:navigate>Comprobantes</a>

      <hr>

      {{-- @if($comprobante_id)
          {{-- <a role="button" href="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'A4'])}}" target="_blank">Formato A4</a>   
          <a role="button" href="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'Ticket'])}}" target="_blank">Formato Ticket</a>  

          <!-- Dropdown -->
          <details class="dropdown">
            <summary>Formato</summary>
            <ul>
              @if ($tipo == 'factura')
                  <li><a  href="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'A4'])}}" target="_blank">Formato A4</a> </li>
                  <li><a  href="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'Ticket'])}}" target="_blank">Formato Ticket</a></li>
                  
              @else

                  <li><a  href="{{route('imprimirPresupuesto',['presupuesto_id'=>$comprobante_id,'formato'=>'A4'])}}" target="_blank">Formato A4</a> </li>

                  
              @endif

            </ul>
          </details>


      @else
          <a role="button" href="https://www.example.com" target="_blank">PARA VOLVER A LA FACTURA </a>
          <a role="button" href="https://www.example.com" target="_blank">PARA VOLVER A LA FACTURA </a>  
      @endif --}}

      <fieldset>
        <legend>Formato Imprecion:</legend>
        <label>
          <input type="radio" name="language" value="ticket" x-model="selectedOption" />
          Tiket
        </label>
        <label>
          <input type="radio" name="language" value="A4" x-model="selectedOption" />
          Hoja A4
        </label>
      </fieldset>

    </article>

    @switch($tipo)
        @case('factura')

            <div class="grid">
              <div class="col" x-show="selectedOption === 'ticket'">
                <iframe width="100%" height="1000px" src="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'Ticket'])}}" frameborder="0"></iframe>
              </div>
              <div class="col" x-show="selectedOption === 'A4'">
        
                <iframe width="100%" height="1000px" src="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'A4'])}}" frameborder="0"></iframe>
              </div>
            </div>
            
            @break
        @case('presupuesto')

            <iframe width="100%" height="1000px" src="{{route('imprimirPresupuesto',['presupuesto_id'=>$comprobante_id,'formato'=>'A4'])}}" frameborder="0"></iframe>

            
            @break
        @case('reciboPagoCC')
            
        
            <iframe width="100%" height="1000px" src="{{route('reciboPdf',['recibo_id'=>$comprobante_id])}}" frameborder="0"></iframe>

            @break
        @default
            <H1>default</H1>
    @endswitch

    {{-- @if ($tipo == 'factura')
        
    <div class="grid">
      <div class="col" x-show="selectedOption === 'ticket'">
        <iframe width="100%" height="1000px" src="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'Ticket'])}}" frameborder="0"></iframe>
      </div>
      <div class="col" x-show="selectedOption === 'A4'">

        <iframe width="100%" height="1000px" src="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'A4'])}}" frameborder="0"></iframe>
      </div>
    </div>

    @else

      <iframe width="100%" height="1000px" src="{{route('imprimirPresupuesto',['presupuesto_id'=>$comprobante_id,'formato'=>'A4'])}}" frameborder="0"></iframe>

        
    @endif --}}



</div>

    
@endsection