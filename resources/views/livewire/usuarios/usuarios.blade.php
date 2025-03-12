<div>

    {{-- @dump($usuarios) --}}

    <div class="container">

            @if (session('mensaje'))
            <article>
                {{ session('mensaje') }}

            </article>

            @endif

            <nav>
                <li>
                    <h1>Usuarios</h1>
                    <input wire:model.live="buscarUsuario" placeholder="Buscar">

                </li>
            </nav>


        {{-- 0 => {#1579 ▼
            +"id": 3
            +"name": "MAUSE"
            +"email": "mause.javi@gmail.com"
            +"empresa_id": 1
            +"puntoVenta": 4
            +"email_verified_at": "2024-04-30 18:10:42"
            +"password": "$2y$12$rGwgiu7tiiB0s1htcYWFBebCPrVmgDs2ydI7tN9v0qBP1YJNtMsT6"
            +"role_id": 3
            +"remember_token": "BTe18C3MBNBQgOuFliYRtLxZunz0vQLCCSfzfbiepIYBi1PahFue5rRXvBOF"
            +"created_at": null
            +"updated_at": null
            +"razonSocial": "Empresa Prueba"
            +"cuit": 0
            +"domicilio": "Domicilio"
            +"fe": "no"
            +"iva": "ME"
            +"inicioActividades": "2024-04-30"
            +"telefono": null
            +"titular": "Titular Prueba"
            +"nombre": "Super"
          } --}}

          <div class="overflow-auto">
            <table>
                <thead>
                    <tr>
                        
                        <td>Nombre</td>
                        <td>Correo</td>
                        <td>Empresa (FE)</td>
                        <td>PuntoVenta</td>
                        <td>Deposito</td>
                        
                        <td>Rol</td>
                        <td>LastLogin</td>
                        <td>Eliminar</td>

                    </tr>
                </thead>
                <tbody>


                    @foreach ($usuarios as $us)
    
                        @if ($us->usuarioId == 1 OR $us->usuarioId == 2)
    
                            <tr>
                                <td>
                                    <a wire:navigate href="{{route('updateUsuario',['id'=>$us->usuarioId])}}"
                                        style="color: yellow;">
                                        {{$us->name}}
                                    </a> 
                                </td>
                                
                                <td>{{$us->email}}</td>
                                <td>{{$us->razonSocial}} ({{$us->fe}})</td>
                                <td>{{$us->puntoVenta}}</td>
                                <td>{{$us->nombreDeposito}}</td>
                                <td>{{$us->rol}}</td>
                                <td>{{$us->last_login}}</td>
                                
                            </tr>
                            
                            
                        @endif
                        
                    @endforeach



                    @foreach ($usuarios as $us)
    
                    @if ($us->usuarioId !== 1 AND $us->usuarioId !== 2)
    
                        <tr>
                            <td>
                                <a wire:navigate href="{{route('updateUsuario',['id'=>$us->usuarioId])}}">
                                    Editar:
                                    {{$us->name}}
                                </a> 
                            </td>
                            
                            <td>{{$us->email}}</td>
                            <td>{{$us->razonSocial}} ({{$us->fe}})</td>
                            <td>{{$us->puntoVenta}}</td>
                            <td>{{$us->nombreDeposito}}</td>
                            <td>{{$us->rol}}</td>
                            <td>{{$us->last_login}}</td>
                            <td>
                                <button wire:confirm="Esta seguro de eliminar?" wire:click="eliminarUsuario({{$us->usuarioId}})">Eliminar</button>
                            </td>

                            
                        </tr>
                        
                        
                    @endif
                        
                    @endforeach
                </tbody>
            </table>
          </div>



        {{-- @dump($usuarios) --}}
    </div>
</div>
