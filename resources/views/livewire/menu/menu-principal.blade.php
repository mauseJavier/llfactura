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
              <a href="/nuevoComprobante" wire:navigate><h4 style="color: forestgreen">Factura</h4></a>
              <a href="/panel" wire:navigate><h4>App </h4></a></strong>         
              <small><h6>{{$empresa->razonSocial}}</h6></small>
            </hgroup>
          </li>


           
        </ul>


        
        <ul>
          
          {{-- <li><a href="{{route('profile')}}" class="secondary">{{$name}}</a></li> --}}
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
                    
                @endif

                {{-- ADMIN USUARIO --}}
                @if ((Auth::user()->role_id == 2 || Auth::User()->role_id == 3))

                  <li>
                    <a wire:navigate href="{{route('inventario')}}">Inventario</a>
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
