<div>
    <div class="container">

        {{-- @dump($inicioActividades) --}}

        <h1>Empresa</h1>
        <article>
            
            <div class="grid">

                <div class="col">
                    <div role="group">
                        {{-- <button class="outline">Rubro</button>
                        <button class="outline">Proveedor</button> --}}
                        <button wire:click="cambiarModal">Nueva Empresa</button>
                    </div>
                </div>

                <div class="col">
                    <form role="search"  wire:submit="">        
                        
                        <input wire:model.live="datoBuscado" name="search" type="search" placeholder="Buscar en Empresas" class="seleccionarTodo" />
                        {{-- <input type="submit" value="Buscar" /> --}}
                    </form>
                </div>
            </div>
                
        </article>

        <article aria-busy="true" wire:loading></article>

        <div class="overflow-auto" wire:loading.remove>
            <table class="striped">
                <thead>
                  <tr>
                    <th scope="col">Razon Social</th>
                    <th scope="col">Titular</th>
                    <th scope="col">Cuit</th>
                    <th scope="col">ClaveF</th>
                    <th scope="col">FacturaE</th>
                    <th scope="col">IVA</th>
                    <th scope="col">IVA Defecto</th>
                    <th scope="col">Inicio Actividades</th>
                    <th scope="col">Precio-2</th>
                    <th scope="col">Precio-3</th>
                    <th scope="col">Domicilio</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Correo</th>

                  </tr>
                </thead>
                <tbody>
                    @foreach ($empresas as $e)
                        <tr>
                        <th scope="row">
                            <button wire:click="editarId({{$e->id}})"> <i class="fa-regular fa-pen-to-square"></i> {{$e->razonSocial}}</button>     
                        </th>
                        <th scope="row">
                            <button  wire:click="datosEmpresa({{$e->id}})" class="outline"> <i class="fa-solid fa-list"></i> {{$e->titular}}</button>     
                        </th>

                        <td>{{$e->cuit}}</td>
                        <td>{{$e->claveFiscal}}</td>
                        <td>{{$e->fe}}</td>
                        <td>{{$e->iva}}</td>
                        <td>{{$e->ivaDefecto}}</td>
                        <td>{{$e->inicioActividades}}</td>
                        <td>{{$e->precio2}}</td>
                        <td>{{$e->precio3}}</td>
                        <td>{{$e->domicilio}}</td>
                        <td>{{$e->telefono}}</td>
                        <td>{{$e->correo}}</td>
                        
                        </tr>
                        
                    @endforeach
                </tfoot>
              </table>
        </div>



    </div>

    <dialog {{$modal}}>
        <article>
          <header>
            <button aria-label="Close" rel="prev" wire:click="cambiarModal"></button>
            <p>
              <strong>Nueva Empresa</strong>
            </p>
          </header>

          <form wire:submit="guardarEmpresa">
            <fieldset>
              <label>
                Razon Social
                    <input
                    name="razonSocial"
                    placeholder="Razon Social"
                    autocomplete="razonSocial"
                    wire:model.blur="razonSocial"
                    @error('razonSocial') aria-invalid="true" @enderror
                    />
                    @error('razonSocial') 
                        <small id="invalid-helper">
                            {{ $message }} 
                        </small>                               
                    @enderror
              </label>
              <label>
                Titular
                <input
                  type="text"
                  name="titular"
                  placeholder="Titular"
                  autocomplete="titular"
                  wire:model.blur="titular"
                  @error('titular') aria-invalid="true" @enderror
                  />
                  @error('titular') 
                      <small id="invalid-helper">
                          {{ $message }} 
                      </small>                               
                  @enderror
              </label>
            </fieldset>

            <div class="grid">
                <div class="col">
                    <label for="algo">Cuit
                        <input type="text" wire:model.blur="cuit" 
                            minlength="1" maxlength="11"
                            placeholder="Cuit"
                        @error('cuit') aria-invalid="true" @enderror
                        />
                        @error('cuit') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
                <div class="col">
                    <label for="algo">Clave Fiscal
                        <input type="text" wire:model.blur="claveFiscal"  
                            placeholder="Clave Fiscal"
                            @error('claveFiscal') aria-invalid="true" @enderror
                        >
                        @error('claveFiscal') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
            </div>
            <div class="grid">
                <div class="col">
                    <label for="">Factura Eletronica
                        <select name="" wire:model.blur="fe" >
                            <option value="si">SI</option>
                            <option value="no">NO</option>
                           </select>
                    </label>
                </div>

                <div class="col">
                    <label for="">Condicion IVA
                        <select name="" wire:model.blur="iva" >
                            <option value="ME">Monotributo</option>
                            <option value="RI">Responsable Inscripto</option>
                           </select>
                    </label>
                </div>

            </div>
            <div class="grid">
                <div class="col">
                    <label for="">IVA por Defecto
                        <input type="text" wire:model.blur="ivaDefecto"
                        @error('ivaDefecto') aria-invalid="true" @enderror
                        />
                        @error('ivaDefecto') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
                <div class="col">
                    <label for="">Inicio Actividades
                        <input type="date" wire:model.blur="inicioActividades"
                        value="{{$inicioActividades}}"
                        @error('inicioActividades') aria-invalid="true" @enderror
                        />
                        @error('inicioActividades') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
                <div class="col">
                    <label for="">Precio 2 Defecto
                        <input type="text" wire:model.blur="precio2"
                        @error('precio2') aria-invalid="true" @enderror
                        />
                        @error('precio2') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
                <div class="col">
                    <label for="">Precio 3 Defecto
                        <input type="text" wire:model.blur="precio3"
                        @error('precio3') aria-invalid="true" @enderror
                        />
                        @error('precio3') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
            </div>
            <div class="grid">
                <div class="col">
                    <label for="algo">Domicilio
                        <input type="text" wire:model.blur="domicilio"  
                            placeholder="Domicilio"
                        @error('domicilio') aria-invalid="true" @enderror
                        />
                        @error('domicilio') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
                <div class="col">
                    <label for="algo">Telefono
                        <input type="text" wire:model.blur="telefono"  
                            placeholder="Telefono"
                            @error('telefono') aria-invalid="true" @enderror
                        >
                        @error('telefono') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
                <div class="col">
                    <label for="algo">Logo
                        <input type="text" wire:model.blur="logo"  
                            placeholder="Logo"
                            disabled
                            @error('logo') aria-invalid="true" @enderror
                        >
                        @error('logo') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>
                </div>
            </div>
            <div class="grid">
                <div class="col">
                    <label for="algo">Correo Electronico
                        <input type="text" wire:model.blur="correo"  
                            placeholder="Correo"
                            
                            @error('correo') aria-invalid="true" @enderror
                        >
                        @error('correo') 
                            <small id="invalid-helper">
                                {{ $message }} 
                            </small>                               
                        @enderror
                    </label>

                </div>

                <div class="col">
                    <label for="algo">Generar Certificado
                        <select name="" wire:model.blur="generarCertificado" >
                            <option value="si">SI</option>
                            <option value="no">NO</option>
                        </select>
                    </label>

                </div>

            </div>




          
            <div role="group">
               
                    <button aria-busy="true" wire:loading >
                
                    <button type="submit" wire:loading.remove >Guardar</button>                    
                
            </div>
            
        </form>
        <button wire:click="cambiarModal" class="outline">Cancelar</button>

        </article>
    </dialog>

</div>
