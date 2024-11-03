<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    {{-- @dump($role) --}}
    <nav>
        <ul>
          {{-- <li><strong><a href="/panel" wire:navigate><h1>{{ config('app.name', 'Laravel') }}</h1></a></strong></li> --}}
          {{-- <li><strong>
            <li><small><h6>{{$empresa->razonSocial}}</h6></small></li> --}}
          </li>
          <li>
            <hgroup>
              <a href="/nuevoComprobante" wire:navigate><h4 style="color: forestgreen">FacturaApp</h4></a>
              
              <br>
              <small>
                <a href="/configuracion/basico" wire:navigate><h6>{{$empresa->razonSocial}}</h6></a>
  
              </small>
              {{-- <small><h6>{{$empresa->razonSocial}}</h6></small> --}}
            </hgroup>
          </li>


           
        </ul>


        @if ($articulos != 0)
          <article style="
    
                  align-items: center;
                  justify-content: center;
                  text-align: center;
              
                  top: 0;
                  width: 80%;
                  max-width: 100%;
                  min-width: 100px;
                  padding: 2px;
      
                  margin: auto;
                  ">
            <h3>$ {{$total}}</h3>
            <small>Artículos: {{$articulos}}</small>
          </article>      
        @endif


        
        <ul>
          
          {{-- <li><a href="{{route('profile')}}" class="secondary">{{$name}}</a></li> --}}
          <li>
            <a wire:navigate href="/novedades"><i class="fa-solid fa-bell fa-xl" style="color: red;"></i></a>    
          </li>
          <li>
            <a wire:navigate href="/panel"><i class="fa-solid fa-house fa-xl"></i></a>    
          </li>
          <li>
            <a wire:navigate href="{{route('venta')}}"><i class="fa-solid fa-cart-shopping fa-xl"></i></a>    
          </li>
          <li>
             
            <details class="dropdown" >
              <summary>
                <i class="fa-solid fa-ellipsis-vertical"></i>
                <i class="fa-solid fa-ellipsis-vertical"></i>
              </summary>
              <ul dir="rtl">
                <li>

                  <h5>

                    {{$name}}-({{$role->nombre}})
                  </h5>
                </li>              
                {{-- SUPER USURAIO --}}
                @if (Auth::user()->role_id == 3)
                  <li>
                    <a wire:navigate href="{{route('usuarios')}}">Usuarios</a>
                  </li>
                  <li>
                    <a wire:navigate href="{{route('empresa')}}">Empresas</a>
                  </li>
                  <li>
                    <a wire:navigate href="{{route('facturacionempresas')}}">Facturacion Empresas</a>
                  </li>
                  <li>
                    <a wire:navigate href="{{route('EstadoEmpresa')}}">Estado Empresas</a>
                  </li>
                    
                @endif

                {{-- ADMIN USUARIO --}}
                @if ((Auth::user()->role_id == 2 || Auth::User()->role_id == 3 || Auth::User()->role_id == 4))

                  <li>
                    <a wire:navigate href="{{route('inventario')}}">Inventario</a>
                  </li>
                  <li>
                    <a wire:navigate href="{{route('ventasArticulos')}}">Ventas por Art</a>
                  </li>
                  <li>
                    <a wire:navigate href="{{route('configuracionbasico')}}">Configuracion Basico</a>
                  </li>
                    
                @endif

                {{-- TODOS LOS USUARIOS --}}
                <li><a wire:navigate href="{{route('comprobante')}}">Comprobantes</a></li>
                <li><a wire:navigate href="{{route('profile')}}">Perfil</a></li>
                
                <li><a wire:click="logout" role="button" >Salir</a></li>
               
              </ul>
            </details>
          </li>
        </ul>
    </nav>
</div>
