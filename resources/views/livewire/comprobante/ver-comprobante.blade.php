<div >
  {{-- ESN ESTE COMPONENTE UTILIZO ALPINE JS PARA LOS MODALES ABRIR Y CERRAR  --}}
    <div class="container" x-data="{ modalFiltro: false }">

        <h3>Ver Comprobantes</h3>


        @if (Auth()->user()->role_id == 2 OR Auth()->user()->role_id == 3 OR Auth()->user()->role_id == 4)
            
          <div class="grid">
            <div class="col">
              <article>
                @foreach ($totales as $item)  
                  <label for="">{{$item['nombre']}}: ${{number_format($item['total'], 2, ',', '.')}}</label>                           
                @endforeach   
              </article>
              
            </div>
            <div class="col">
              <article>
                @foreach ($sumComprobantes as $item)

                  @switch($item->tipoComp)
                    @case(1)
                      <label for="">Factura A: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break
                    @case(6)
                      <label for="">Factura B: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break
                    @case(11)
                      <label for="">Factura C: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break
                    @case(51)
                      <label for="">Factura M: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break

                    @case('remito')
                      <label for="">Remito: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break

                    @case('3')
                    <label for="">NC A: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break
                    @case('8')
                      <label for="">NC B: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break
                    @case('13')
                      <label for="">NC C: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break
                  @case('notaRemito')
                      <label for="">NC R: ${{number_format($item->sumTotal, 2, ',', '.')}}</label>
                    @break
                  @default

                  @endswitch
                @endforeach
              </article>

            </div>
          </div>

          <article>

            <label for="">Total: ${{number_format($sumTotal, 2, ',', '.')}}</label>
            <label for="">Total IVA: ${{number_format($ivaPeriodo, 2, ',', '.')}}</label>

            <hr>
            <button wire:click="crearPDF()">Crear PDF</button>
            <button wire:click="exportarCSV()">Exportar CSV</button>
          </article>

        @endif



        <article>

          <div class="grid">
            
            <div class="col" style="text-align: center;" wire:click="restarDia()">
              <!-- arrow-left icon by Free Icons (https://free-icons.github.io/free-icons/) -->
              <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                <path
                  d="M 3.4285714285714284 249.14285714285714 Q 0 252.57142857142858 0 256 Q 0 259.42857142857144 3.4285714285714284 262.85714285714283 L 222.85714285714286 473.14285714285717 L 222.85714285714286 473.14285714285717 Q 228.57142857142858 477.7142857142857 235.42857142857142 473.14285714285717 Q 240 466.2857142857143 235.42857142857142 459.42857142857144 L 32 265.14285714285717 L 32 265.14285714285717 L 502.85714285714283 265.14285714285717 L 502.85714285714283 265.14285714285717 Q 510.85714285714283 264 512 256 Q 510.85714285714283 248 502.85714285714283 246.85714285714286 L 32 246.85714285714286 L 32 246.85714285714286 L 235.42857142857142 52.57142857142857 L 235.42857142857142 52.57142857142857 Q 240 45.714285714285715 235.42857142857142 40 Q 228.57142857142858 34.285714285714285 221.71428571428572 38.857142857142854 L 2.2857142857142856 249.14285714285714 L 3.4285714285714284 249.14285714285714 Z"
                />
              </svg>            
            </div>
            <div class="col" style="text-align: center;">
              <small>
                <label  for="">De: {{date("d-m-Y", strtotime($fechaFiltroDesde)) }} A: {{date("d-m-Y", strtotime($fechaFiltroHasta))}}</label>
              </small>

            </div>
            <div class="col" style="text-align: center;" wire:click="agregarDia()">
              <!-- arrow-right icon by Free Icons (https://free-icons.github.io/free-icons/) -->
              <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
                <path
                  d="M 509.7142857142857 262.85714285714283 Q 512 259.42857142857144 512 256 Q 512 252.57142857142858 509.7142857142857 249.14285714285714 L 290.2857142857143 38.857142857142854 L 290.2857142857143 38.857142857142854 Q 283.42857142857144 34.285714285714285 276.57142857142856 38.857142857142854 Q 272 45.714285714285715 276.57142857142856 52.57142857142857 L 480 246.85714285714286 L 480 246.85714285714286 L 9.142857142857142 246.85714285714286 L 9.142857142857142 246.85714285714286 Q 1.1428571428571428 248 0 256 Q 1.1428571428571428 264 9.142857142857142 265.14285714285717 L 480 265.14285714285717 L 480 265.14285714285717 L 277.7142857142857 459.42857142857144 L 277.7142857142857 459.42857142857144 Q 272 466.2857142857143 276.57142857142856 473.14285714285717 Q 283.42857142857144 477.7142857142857 290.2857142857143 473.14285714285717 L 509.7142857142857 262.85714285714283 L 509.7142857142857 262.85714285714283 Z"
                />
              </svg>
            </div>
            
            
            
              
              
      
            
            

          </div>

        </article>
          
          <hr />

          <div class="grid">
            <div class="col">

              <fieldset>
                <label>
                  <input name="terms" type="checkbox" role="switch" @click="modalFiltro = !modalFiltro" x-bind:checked="modalFiltro" />
                  Filtros
                </label>
                
              </fieldset>
            </div>

            <div class="col">

              <a href="{{route('cierrecaja')}}" style="cursor: pointer;">Cierre Caja</a>

            </div>
            <div class="col">              
              
              <input  style="width: 50%; height: 50%;" type="text"  wire:keyup="modificarFechas($event.target.value)" name="numeroComprobanteFiltro" placeholder="Filtro Numero o Cliente" />
              
            </div>

          </div>


          <div class="overflow-auto">
            <table class="striped">
                <thead>
                  <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">
                      Comp.
                    </th>
                    <th scope="col">Numero</th>
                    <th scope="col">Cae</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">CuitCliente</th>
                    <th scope="col">
                      Usuario
                    </th>
                    <th scope="col">Importe</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($comprobantes as $item)
                        <tr>
                            <th scope="row"> 
                              <!-- Dropdown -->
                              <details class="dropdown">
                                <summary>Acciones</summary>
                                <ul>
                                  <li><a wire:navigate href="{{route('productosComprobante',['idComprobante'=>$item->id])}}">Ver</a></li>
                                  <li><a wire:navigate href="{{route('formatoPDF',['tipo'=>'factura','comprobante_id'=>$item->id])}}">Imprimir</a></li>
                                  @if ($item->tipoComp == 1 OR $item->tipoComp == 6 OR $item->tipoComp == 11 OR $item->tipoComp == 'remito')
                                    <li><a wire:navigate href="{{route('remitoscomprobante')}}">Remitos</a></li>
                                    <li><a wire:navigate href="{{route('notacredito',['comprobante'=>$item->id])}}">Nota Credito</a></li>
                                  @endif
                                  @if ($item->tipoComp == 'remito')
                                    <li><a wire:navigate href="{{route('facturarRemito',['idComprobante'=>$item->id])}}">Facturar</a></li>
                                  @endif
                                    <li><a wire:navigate href="{{route('cargarComprobante',['comp'=>$item->id])}}">Cargar</a></li>

                                </ul>
                              </details>                       
                            </th>
                            <td>{{$item->fecha}}</td>
                            <td>
                              @switch($item->tipoComp)
                                  @case(11)
                                      C
                                      @break
                                  @case(6)
                                      B
                                      @break
                                  @case(1)
                                      A
                                      @break
                                  @case(51)
                                      M
                                      @break
                                  @case('remito')
                                      R
                                    @break
                                  @case(3)
                                    NC A
                                    @break
                                  @case(8)
                                    NC B
                                    @break
                                  @case(13)
                                    NC C
                                    @break
                                  @default
                                  {{$item->tipoComp}}
                              @endswitch
                              
                            </td>
                            <td>{{$item->numero}}</td>
                            <td>{{$item->cae}}</td>
                            <td>{{$item->razonSocial}}</td>
                            <td>{{$item->cuitCliente}}</td>
                            <td>{{$item->usuario}}</td>
                            <td>${{$item->total}}</td>
                          </tr>
                    @endforeach

                
                </tbody>
                <tfoot>
                  <tr>

                  </tr>
                </tfoot>
            </table>
          </div>

          {{-- {{ $comprobantes->links('paginacion.paginacion') }} --}}
          {{ $comprobantes->links('vendor.livewire.bootstrap') }}
  


          {{-- <dialog x-bind:open="modalFiltro">
            <article>
              <header>
                <button aria-label="Close" rel="prev" @click="modalFiltro = !modalFiltro"></button>
                <p>
                  <strong>Filtro Comprobantes</strong>
                </p>
              </header>

              <fieldset>
                <legend>Language preference:</legend>

                <div class="grid">

                  <label>
                    <input type="radio" name="fechaHoy" checked />
                    HOY
                  </label>
                  <label>
                    <input type="radio" name="fechaEstaSemana" />
                    Esta Semana
                  </label>
                  <label>
                    <input type="radio" name="fechaEsteMes" />
                    Este Mes
                  </label>
                  <label>
                    <input type="radio" name="fechaMesPasado" />
                    Mes Pasado
                  </label>

                </div>
              </fieldset>



              <div class="grid">
                <div class="col">

                  <label for="">
                    Fecha Desde
                    <input type="datetime-local" wire:model.live="fechaFiltroDesde" id="fechaDesde" />
                  </label>
                </div>
                <div class="col">

                  <label for="">
                    Fecha Hasta
                    <input type="datetime-local" wire:model.live="fechaFiltroHasta" id="fechaHasta" />
                  </label>
                </div>

              </div>
              
              <div class="grid">
                <div class="col">
                  <label for="">
                    Comp.
                    <select name="tipoComp" wire:model.live="tipoComp">
                      <option value="">Todo</option>

                      @foreach ($tiposComprobantes as  $key => $value)
                        <option value="{{$key}}">{{$value}}</option>                          
                      @endforeach


                    </select>
                  </label>                  
                </div>
                <div class="col">
                  <label for="">
                    Usuario
                    <input type="text" wire:model.live="usuarioFiltro" name="usuario" placeholder="Usuario"  id="">
                  </label>
                </div>
              </div>

              <label for="">
                Cerrar
                <input name="terms" type="checkbox" role="switch" @click="modalFiltro = !modalFiltro" x-bind:checked="modalFiltro" />
              </label>

            </article>
          </dialog> --}}

          <dialog x-bind:open="modalFiltro">
            <article x-data="fechaHandler()">
                <header>
                    <button aria-label="Close" rel="prev" @click="modalFiltro = !modalFiltro"></button>
                    <p>
                        <strong>Filtro Comprobantes</strong>
                    </p>
                </header>
        
                <fieldset>
                    <legend>Fecha:</legend>
                    <div class="grid">
                        <label>
                            <input type="radio" name="fecha" x-model="selectedOption" value="hoy" @change="setFecha('hoy')" />
                            HOY
                        </label>
                        <label>
                            <input type="radio" name="fecha" x-model="selectedOption" value="semana" @change="setFecha('semana')" />
                            Esta Semana
                        </label>
                        <label>
                            <input type="radio" name="fecha" x-model="selectedOption" value="mes" @change="setFecha('mes')" />
                            Este Mes
                        </label>
                        <label>
                            <input type="radio" name="fecha" x-model="selectedOption" value="mesPasado" @change="setFecha('mesPasado')" />
                            Mes Pasado
                        </label>
                    </div>
                </fieldset>
        
                <div class="grid">
                    <div class="col">
                        <label for="">
                            Fecha Desde
                            <input wire:model.live="fechaFiltroDesde" type="datetime-local" x-model="fechaDesde" id="fechaDesde" />
                        </label>
                    </div>
                    <div class="col">
                        <label for="">
                            Fecha Hasta
                            <input wire:model.live="fechaFiltroHasta" type="datetime-local" x-model="fechaHasta" id="fechaHasta" />
                        </label>
                    </div>
                </div>
                
                <div class="grid">
                    <div class="col">
                        <label for="">
                            Comp.
                            <select name="tipoComp" wire:model.live="tipoComp">
                                <option value="">Todo</option>
                                @foreach ($tiposComprobantes as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </label>                  
                    </div>

                    @if (Auth::user()->role_id == 3 OR Auth::user()->role_id == 4)
                        
                        <div class="col">
                            <label for="">
                                Usuario
                                <select name="selectUsuario" id="selectUsuario" wire:model.live="usuarioFiltro">
                                  <option value="">Todos</option>
                                  @foreach ($usuariosEmpresa as $u)
                                    <option value="{{$u->name}}">{{$u->name}}</option>
                                      
                                  @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="col">
                          <label for="">
                              Deposito

                              <select name="selectDeposito" id="selectDeposito" wire:model.live="depositoFiltro">
                                <option value="">Todos</option>
                                @foreach ($depositosEmpresa as $d)
                                  <option value="{{$d->id}}">{{$d->nombre}}</option>
                                    
                                @endforeach
                              </select>
                          </label>
                      </div>
                    @endif
                    
                </div>
        
                <label for="">
                    Cerrar
                    <input name="terms" type="checkbox" role="switch" @click="modalFiltro = !modalFiltro" x-bind:checked="modalFiltro" />
                </label>
        
            </article>
                <!-- Manejar el evento de Alpine.js para actualizar las fechas en Livewire -->
                <script>
                  document.addEventListener('actualizar-fechas', event => {
                      @this.actualizarFechas(event.detail.fechaDesde, event.detail.fechaHasta);
                  });
              </script>

        </dialog>
        
        <script>
            function fechaHandler() {

              var hoy = today = new Date()
              var horaInicio =  '00:' + '00' ;
              var horaFin =  '23:' + '59' ;

              fechaHoy = hoy.getFullYear() + '-' + ('0' + (hoy.getMonth() + 1)).slice(-2) + '-' + ('0' + hoy.getDate()).slice(-2) ;


                return {


                    fechaDesde: fechaHoy +'T'+ horaInicio, // Inicializar con el día de hoy
                    fechaHasta: fechaHoy +'T'+ horaFin, // Inicializar con el día de hoy
                    selectedOption: 'hoy', // Por defecto seleccionamos "Hoy"

                    setFecha(option) {
                        let startDate, endDate,principioMes, primerDiaDelSiguienteMes,ultimoDiaDelMes,finMes;

                        switch (option) {
                            case 'hoy':

                                 fecha = hoy.getFullYear() + '-' + ('0' + (hoy.getMonth() + 1)).slice(-2) + '-' + ('0' + hoy.getDate()).slice(-2) ;


                                //  hora = ('0' + hoy.getHours()).slice(-2) + ':' + ('0' + hoy.getMinutes()).slice(-2);


                                 startDate = fecha +'T'+ horaInicio;
                                 endDate = fecha +'T'+ horaFin;

                            break;

                            case 'semana':

                                // Obtener el día de la semana actual (0 = Domingo, 1 = Lunes, ..., 6 = Sábado)
                                const dayOfWeek = today.getDay();

                                // Calcular la diferencia en días hasta el lunes (si today es lunes, la diferencia será 0)
                                const diffToMonday = (dayOfWeek === 0 ? -6 : 1) - dayOfWeek;

                                lunes = hoy.getFullYear() + '-' + ('0' + (hoy.getMonth() + 1)).slice(-2) + '-' + ('0' + (today.getDate() + diffToMonday) ).slice(-2) ;

                                // Crear una nueva fecha (puedes cambiar la fecha según tus necesidades)
                                let domingo = new Date(lunes);

                                // Sumar 6 días a la fecha
                                domingo.setDate(domingo.getDate() + 7);
                                

                                domingo = hoy.getFullYear() + '-' + ('0' + (domingo.getMonth() + 1)).slice(-2) + '-' + ('0' + domingo.getDate() ).slice(-2) ;


                                 startDate = lunes +'T'+ horaInicio;
                                 endDate = domingo +'T'+ horaFin;


                                break;
                            case 'mes':
                                // let startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                                principioMes = today.getFullYear() + '-' + ('0' + (today.getMonth()+1)).slice(-2) + '-' + ('01').slice(-2) ;

                                primerDiaDelSiguienteMes = new Date(today.getFullYear(), today.getMonth() + 1, 1);

                                // Restar un día para obtener el último día del mes actual
                                ultimoDiaDelMes = new Date(primerDiaDelSiguienteMes - 1);

                                finMes = hoy.getFullYear() + '-' + ('0' + (ultimoDiaDelMes.getMonth()+1)).slice(-2) + '-' + ('0'+ (ultimoDiaDelMes.getDate()) ).slice(-2) ;
                             

                                startDate = principioMes +'T'+ horaInicio;
                                endDate = finMes +'T'+ horaFin;
                                break;
                            case 'mesPasado':
                                // let startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                                 principioMes = today.getFullYear() + '-' + ('0' + (today.getMonth())).slice(-2) + '-' + ('01').slice(-2) ;

                                 primerDiaDelSiguienteMes = new Date(today.getFullYear(), today.getMonth() , 1);

                                // Restar un día para obtener el último día del mes actual
                                 ultimoDiaDelMes = new Date(primerDiaDelSiguienteMes - 1);

                                 finMes = hoy.getFullYear() + '-' + ('0' + (ultimoDiaDelMes.getMonth()+1)).slice(-2) + '-' + ('0'+ (ultimoDiaDelMes.getDate()) ).slice(-2) ;
                             

                                startDate = principioMes +'T'+ horaInicio;
                                endDate = finMes +'T'+ horaFin;
                                break;
                        }

                        // Convertir a ISO string sin cambiar a UTC
                        this.fechaDesde = startDate;
                        this.fechaHasta = endDate;

                        // Despachar evento para actualizar Livewire
                        this.$dispatch('actualizar-fechas', { fechaDesde: this.fechaDesde, fechaHasta: this.fechaHasta });

                        // Deshabilita las demás opciones
                        this.disableOthers(option);
                    },

                    disableOthers(selected) {
                        const options = ['hoy', 'semana', 'mes', 'mesPasado'];
                        options.forEach(option => {
                            const element = document.querySelector(`input[value=${option}]`);
                            element.disabled = option !== selected;
                        });
                    }
                }
            }


        </script>
        
        
</div>
