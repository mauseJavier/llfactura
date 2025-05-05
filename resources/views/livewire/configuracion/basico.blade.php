<div>

    <div class="container">
        <h3>Configuracion</h3>

    {{-- "id" => 1
    "razonSocial" => "Empresa Prueba"
    "cuit" => 20080202874
    "claveFiscal" => "sinclave"
    "fe" => "no"
    "iva" => "ME"
    "titular" => "Empresa Prueba"
    "logo" => null
    "vencimientoPago" => null

    PARA CONFIGURAR 
    "idFormaPago" => 1
    "pagoServicio" => 0
    "topeFacturacion" => 0.0
    "formatoImprecion" => "T"
    "imprimirSiNo" => 1
    "topeComprobantes" => 10
    "domicilio" => "Av. del Trabajo 540 Las Lajas"
    "ivaDefecto" => 21.0
    "precio2" => 50.0
    "precio3" => 100.0
    "inicioActividades" => "2024-06-24"
    "telefono" => "2994562062"
    "correo" => "marce_nqn_19@hotmail.com"
    "created_at" => null
    "updated_at" => "2024-10-14 18:54:58" --}}

    @if (session('mensaje'))
        <div class="alert alert-success">
            <h2 style="color: green;">

                {{ session('mensaje') }}
            </h2>
        </div>
    @endif

        <article>

            <form>
                <fieldset>
                    <div class="grid">

                        <label>
                          Forma de Pago Activa
                          <select  wire:model="idFormaPago">
      
                              @foreach ($formaPago as $item)
                                  @if ($item->id != 0)
                                      <option value="{{$item->id}}">{{$item->nombre}}</option>
                                      
                                  @endif
                              @endforeach
                                  
                            </select>
                        </label>
                        <label>
                          Tipo de Factura Activa
                          <select  wire:model="facturaDefault">                       
                              <option value="remito">Remito</option>      
                                              
                            @if ($empresa->iva == 'ME' AND $empresa->fe == 'si')
                                <option value="11">Factura C</option>
                            @endif                            
                            @if ($empresa->iva == 'RI' AND $empresa->fe == 'si')
                                <option value="6">Factura B</option>
                                <option value="1">Factura A</option>   
                                <option value="51">Factura M</option>                        
                    
                            @endif      
                            @if ($empresa->razonSocial == 'Empresa Prueba' )
                                <option value="11">Factura C</option>
                                <option value="6">Factura B</option>
                                <option value="1">Factura A</option>    
                                <option value="51">Factura M</option>                                
                            @endif        
                                  
                            </select>
                        </label>
                    </div>

                  <label>
                    Tope Facturacion(En proceso!)
                    <input
                      type="text"
                      name="topeFacturacion"
                      placeholder="Tope Facturacion"
                      wire:model="topeFacturacion"
                    />
                  </label>

                  <div class="grid">

                      <label>
                        Formato Impresiòn
                        <select  wire:model="formatoImprecion">                       
                            <option value="T">Ticket</option>                          
    
                            <option value="A4">A4</option>                          
                                
                          </select>
                      </label>
                      <label>
                        Imprimir?
                        <select  wire:model="imprimirSiNo">                       
                            <option value="0">No Imprimir</option>                          
    
                            <option value="1">Si Imprimir</option>                        
                          </select>
                      </label>

                      <label for="">
                        Forma de Pago 2
                        <select name="activarPago2" wire:model="activarPago2">
            
                                <option value="no">NO</option>
                                <option value="si">SI</option>
                                
                            </select>
                        </label>

                  </div>

                  <label>
                    Domiciliio
                    <input
                      wire:model="domicilio"
                      name="domicilio"
                      placeholder="Domicilio"
                      autocomplete="domicilio"
                    />
                  </label>

                  <div class="grid">

                      <label>
                          IVA por Defecto
                          <input
                          wire:model="ivaDefecto"
                          name="ivaDefecto"
                          placeholder="IVA Defecto"
                          autocomplete="ivaDefecto"
                          />
                      </label>
  
                      <label>
                          IVA Incluido
                          <select wire:model="ivaIncluido">
                              <option value="no">No</option>
                              <option value="si">Sí</option>
                          </select>
                      </label>
                  </div>
                    
                <div class="grid">
                    <label>
                        Porcentaje 2

                        <input
                          wire:model="precio2"
                          name="precio2"
                          placeholder="Precio 2"
                          autocomplete="precio2"
                        />
                    </label>
                    <label for="">
                        Porcentaje 3
                        <input
                            wire:model="precio3"
                            name="precio3"
                            placeholder="Precio 3"
                            autocomplete="precio3"
                        />
                    </label>
                </div>

                <label for="">
                    Inicio Actividades
                    <input wire:model="inicioActividades" type="date" name="inicioActividades" aria-label="Date">

                </label>

                <div class="grid">

                    <label for="">
                        Telefono Empresa
                        <input
                            wire:model="telefono"
                            name="telefono"
                            placeholder="Telefono"
                            autocomplete="telefono"
                        />
                    </label>

                    <label>
                        Teléfono de Notificación
                        <input
                            wire:model="telefonoNotificacion"
                            name="telefonoNotificacion"
                            placeholder="Teléfono de Notificación"
                            autocomplete="telefonoNotificacion"
                        />
                    </label>


                    <label for="">
                        Correo
                        <input
                            wire:model="correo"
                            name="correo"
                            placeholder="Correo"
                            autocomplete="correo"
                        />
                    </label>
                </div>

                <div class="grid">

                    <label>
                        Ingresos Brutos
                        <input
                            wire:model="ingresosBrutos"
                            name="ingresosBrutos"
                            placeholder="Ingresos Brutos"
                            autocomplete="ingresosBrutos"
                        />
                    </label>

                </div>

                <div class="grid">

                    <label>
                        Instancia WhatsApp
                        <input
                            wire:model="instanciaWhatsapp"
                            name="instanciaWhatsapp"
                            placeholder="Instancia WhatsApp"
                            autocomplete="instanciaWhatsapp"
                        />
                    </label>
                    <label>
                        Token WhatsApp
                        <input
                            wire:model="tokenWhatsapp"
                            name="tokenWhatsapp"
                            placeholder="Token WhatsApp"
                            autocomplete="tokenWhatsapp"
                        />
                    </label>
                </div>

                <div class="grid">

    
                    <label for="">
                        Opcion para Restaurantes
                        <select name="mesas" wire:model="mesas">
            
                            <option value="no">NO</option>
                            <option value="si">SI</option>
                            
                            </select>
                    </label>
                </div>




                </fieldset>
              
                <input
                    type="button"
                  value="Guardar Datos"
                  wire:click="guardarEmpresa()"
                />
            </form>

            @if (session('mensaje'))
                <div class="alert alert-success">
                    <h2 style="color: green;">
        
                        {{ session('mensaje') }}
                    </h2>
                </div>
            @endif
        </article>
        

        <nav>
            <li>
                <h1>Usuarios</h1>
                <input wire:model.live="buscarUsuario" placeholder="Buscar">

            </li>
        </nav>

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
    
    
                    @foreach ($usuariosEmpresa as $us)
    
                    @if ($us->usuarioId !== 1 AND $us->usuarioId !== 2)
    
                        <tr>
                            <td>

                                    {{$us->name}}
                                
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

          
    </div>



</div>
