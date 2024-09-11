<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    {{-- "id" => 1
    "name" => "MAUSE"
    "email" => "mause.javi@gmail.com"
    "empresa_id" => 1
    "puntoVenta" => 4
    "email_verified_at" => "2024-05-01 09:25:06"
    "password" => "$2y$12$dzba9.nbcgsrUoW6e2US8uMXrqm8a5ExV/fe3apEcDRjXTLYRnGba"
    "role_id" => 3
    "last_login" => "2024-05-01 09:25:24"
    "remember_token" => "KHjXUsteKS"
    "created_at" => "2024-05-01 09:25:06"
    "updated_at" => "2024-05-01 09:25:24"
    ] --}}
    
    {{-- @dump($listaPuntoVenta ) --}}

    <div class="container">

        <h1>Editar Usuario </h1>
        <h3>{{$name}}</h3>
        
        <span aria-busy="true" wire:loading>Procesando Datos...</span>

        <article wire:loading.remove>
            <form wire:submit="update">
            <fieldset>
                <label>
                    Nombre
                    <input
                    wire:model="name"
                    name="name"
                    placeholder="Ingrese Nombre"
                    autocomplete="name"
                    />
                </label>
                <label>
                    Correo
                    <input
                        wire:model="email"
                        name="email"
                        placeholder="Ingrese Correo"
                        autocomplete="email"
                    />
                </label>
            <label for="">
                Empresa
                <select  wire:model="empresa_id" wire:change="buscarDepositos"  name="empresa_id" aria-label="" required>
                    
                    @foreach ($empresas as $em)
                    @if ($em->id == $empresa_id)
                    <option value="{{$em->id}}" selected>{{$em->razonSocial}}</option>
                    @else
                    <option value="{{$em->id}}">{{$em->razonSocial}}</option>
                    @endif                       
                    
                    @endforeach
                    
                </select>
            </label>

            <label for="">
                Punto de venta
                <select  wire:model.live="puntoVenta"  name="" aria-label="" required>
                    
                    @foreach ($listaPuntoVenta as $puntos)

                        <option value="{{$puntos->Nro}}">{{$puntos->Nro}} - {{$puntos->EmisionTipo}}</option>
                      
                    
                    @endforeach
                    
                </select>
            </label>

            <label>
                Domicilio del Usuario
                <input
                    wire:model="domicilio"
                    name=""
                    placeholder="Ingrese Domicilio"
                    
                />
            </label>
            
            <label for="">
                Deposito
                <select  wire:model.live="deposito_id"  name="" aria-label="" required>
                    
                    @foreach ($depositos as $de)

                        <option value="{{$de->id}}">{{$de->id}} - {{$de->nombre}}</option>
                      
                    
                    @endforeach
                    
                </select>
            </label>


            <label for="">
                Rol
                <select  wire:model="role_id"  name="role_id" aria-label="" required>
                    
                    @foreach ($roles as $rl)
                    @if ($rl->id == $role_id)
                    <option value="{{$rl->id}}" selected>{{$rl->nombre}}</option>
                    @else
                    <option value="{{$rl->id}}">{{$rl->nombre}}</option>
                    @endif                       
                    
                    @endforeach
                    
                </select>
            </label>
            
        </fieldset>
          
        <input
        type="submit"
        value="Guardar"
        />
    </form>
    
    <a wire:navigate role="button" href="{{route('usuarios')}}">Cancelar</a>
</article>
</div>

</div>