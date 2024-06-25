@extends('layouts.app')

@section('main')

<div class="container">


    <article>
      <header>Formato PDF</header>


      @if($comprobante_id)
          {{-- <a role="button" href="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'A4'])}}" target="_blank">Formato A4</a>   
          <a role="button" href="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id,'formato'=>'Ticket'])}}" target="_blank">Formato Ticket</a>   --}}

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
      @endif

    {{--   
      
      @if (\Session::has('comprobante'))
        <form action="{{route('imprimirComprobante')}}" target="_blank">        
      @elseif(isset($comprobante_id))
        <form action="{{route('imprimirComprobante',['comprobante_id'=>$comprobante_id])}}" target="_blank">
      @else
        <form action="{{route('factura')}}">
      @endif
      

          <select name="formatoPDF" aria-label="Seleccione formato" required>
              <option selected value="A4">
                Hoja A4
              </option>
              <option value="T">Ticket</option>

            </select>

            <button type="submit">Imprimir</button>

      </form> --}}

      <a href="{{route('nuevoComprobante')}}" role="button" wire:navigate>Nueva Factura</a>
      <a href="{{route('comprobante')}}" role="button" wire:navigate>Comprobantes</a>
    </article>

</div>

    
@endsection