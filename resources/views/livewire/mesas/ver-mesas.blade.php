<div x-data="{ modalNuevaMesa: false,
              modalNuevoSector: false,
              
              

            
        }"
        
        >

    <div class="container">

        <div class="grid">
            <h3>Mesas ({{$mesaOcupada}}/{{$totalMesas}})</h3>

            
            
            <!-- Dropdown -->
            <details class="dropdown">
            <summary>Opciones</summary>
                <ul>

                  @isset($sectorMesa)
                      
                    <li>
                        <a @click="modalNuevaMesa = !modalNuevaMesa" >Nueva Mesa</a>

                    </li>
                  @endisset
  
                    <li>
                      <a @click="modalNuevoSector = !modalNuevoSector" >Nuevo Sector</a>

                  </li>

                </ul>
            </details>

            
            <input
                type="search"
                name="buscarMesa"
                placeholder="Buscar Mesa"
                aria-label="Search"
                wire:model.live="buscarMesa"
            />
        </div>
    </div>

    {{-- @dd($mesas) --}}

        <div class="div" wire:poll>


          <h1 style="color: rgb(141, 88, 88)"></h1>
          @foreach ($sector as $s)
              <details open >
                  <summary><h4>{{ $s->nombre }}</h4></summary>
      
                  
                  @php $count = 0; @endphp
                  <div 
                    class="grid" >
                  @foreach ($mesas as $item)
                      @if ($item->sector == $s->id)
                          @if ($count > 0 && $count % 4 == 0)
                              </div>
                              <div class="grid">
                          @endif
                          
                          <div 
                            class="col">
                              <article 
                                  x-data="{ numero: '{{ $item->numero }}' }"
                                  :style="'' == '{{ $item->data }}' ? 'background-color:  rgb(56, 56, 171) ; text-align: center; cursor:pointer;' : 'background-color: green; text-align: center; cursor:pointer;'" 
                                  wire:click="modificarMesa('{{ $item->id }}')"
                              >
                                  {{ $item->nombre }} - {{ $item->numero }} ({{$item->razonSocial }} - ${{number_format($item->total, 2, ',', '.')}})
                              </article>
                          </div>
                          
                          {{-- {{ isset($item->data) ? ($item->data) : 0}} --}}
      
                          @php $count++; @endphp
                      @endif
                  @endforeach
                  </div> <!-- Cierra la última fila -->
      
                  
                  
                </details>
      
          @endforeach


        </div>
          



    <dialog x-bind:open="modalNuevaMesa">
            
            <article style="width: 100%;">
              <header>
                <button aria-label="Close" rel="prev" @click="modalNuevaMesa = !modalNuevaMesa" wire:click="$refresh"></button>
                <p>
                  <strong>Nueva Mesa</strong>
                </p>
              </header>

              
                <fieldset>
                  <label>
                    Nombre
                    <input
                      name="nombre"
                      placeholder="Nombre Mesa"
                      autocomplete="nombre"
                      wire:model="nombreMesa"


                      @error('nombreMesa') 
                        aria-invalid="true"
                        aria-describedby="invalid-helper"

                      @enderror

                    />
                    @error('nombreMesa') 
                      <small id="invalid-helper">
                        {{ $message }} 
                      </small>
                    @enderror

                  </label>
                  <label>
                    Numero
                    <input
                      type="text"
                      name="numero"
                      placeholder="Numero de Mesa"
                      autocomplete="numero"
                      wire:model="numeroMesa"
                    
                      @error('numeroMesa') 
                        aria-invalid="true"
                        aria-describedby="invalid-helper"

                      @enderror

                    />
                    @error('numeroMesa') 
                      <small id="invalid-helper">
                        {{ $message }} 
                      </small>
                    @enderror


                  </label>
                </fieldset>

                <fieldset>
                    <label>
                      Capacidad

                      <select wire:model="capacidadMesa" name="" id="" required
                          @error('capacidadMesa') 
                            required aria-invalid="true"
                          
                          @enderror
                      >
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>



                        
                      </select>
                      
                      @error('capacidadMesa') 
                        <small id="invalid-helper">
                          {{ $message }} 
                        </small>
                      @enderror
                    </label>
                    <label>
                      Sector
                      <select wire:model="sectorMesa" name="favorite-cuisine" aria-label="Select your favorite cuisine..." required>

                          @foreach ($sector as $item)
        
                           <option value="{{$item->id}}">{{$item->nombre}}</option>
                              
                          @endforeach
                      </select>
                    </label>
                  </fieldset>


                  @if (session()->has('btnGuardar'))

                  <p style="color: green;">{{ session('btnGuardar') }}</p>
                      
                  @else
                  
                    <input
                      type="submit"
                      wire:click="guardarMesa"   
                      value="Guardar"
    
                    />
                      
                  @endif

    
    
              <p style="text-align: right;" @click="modalNuevaMesa = !modalNuevaMesa" wire:click="$refresh">

                Cerrar - 
    
                  <!-- square-xmark icon by Free Icons (https://free-icons.github.io/free-icons/) -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                        <path
                            d="M 73.14285714285714 18.285714285714285 Q 50.285714285714285 19.428571428571427 34.285714285714285 34.285714285714285 L 34.285714285714285 34.285714285714285 L 34.285714285714285 34.285714285714285 Q 19.428571428571427 50.285714285714285 18.285714285714285 73.14285714285714 L 18.285714285714285 438.85714285714283 L 18.285714285714285 438.85714285714283 Q 19.428571428571427 461.7142857142857 34.285714285714285 477.7142857142857 Q 50.285714285714285 492.57142857142856 73.14285714285714 493.7142857142857 L 438.85714285714283 493.7142857142857 L 438.85714285714283 493.7142857142857 Q 461.7142857142857 492.57142857142856 477.7142857142857 477.7142857142857 Q 492.57142857142856 461.7142857142857 493.7142857142857 438.85714285714283 L 493.7142857142857 73.14285714285714 L 493.7142857142857 73.14285714285714 Q 492.57142857142856 50.285714285714285 477.7142857142857 34.285714285714285 Q 461.7142857142857 19.428571428571427 438.85714285714283 18.285714285714285 L 73.14285714285714 18.285714285714285 L 73.14285714285714 18.285714285714285 Z M 0 73.14285714285714 Q 1.1428571428571428 42.285714285714285 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 Q 42.285714285714285 1.1428571428571428 73.14285714285714 0 L 438.85714285714283 0 L 438.85714285714283 0 Q 469.7142857142857 1.1428571428571428 490.2857142857143 21.714285714285715 Q 510.85714285714283 42.285714285714285 512 73.14285714285714 L 512 438.85714285714283 L 512 438.85714285714283 Q 510.85714285714283 469.7142857142857 490.2857142857143 490.2857142857143 Q 469.7142857142857 510.85714285714283 438.85714285714283 512 L 73.14285714285714 512 L 73.14285714285714 512 Q 42.285714285714285 510.85714285714283 21.714285714285715 490.2857142857143 Q 1.1428571428571428 469.7142857142857 0 438.85714285714283 L 0 73.14285714285714 L 0 73.14285714285714 Z M 166.85714285714286 166.85714285714286 Q 173.71428571428572 162.28571428571428 180.57142857142858 166.85714285714286 L 256 243.42857142857142 L 256 243.42857142857142 L 331.42857142857144 166.85714285714286 L 331.42857142857144 166.85714285714286 Q 338.2857142857143 162.28571428571428 345.14285714285717 166.85714285714286 Q 349.7142857142857 173.71428571428572 345.14285714285717 180.57142857142858 L 268.57142857142856 256 L 268.57142857142856 256 L 345.14285714285717 331.42857142857144 L 345.14285714285717 331.42857142857144 Q 349.7142857142857 338.2857142857143 345.14285714285717 345.14285714285717 Q 338.2857142857143 349.7142857142857 331.42857142857144 345.14285714285717 L 256 268.57142857142856 L 256 268.57142857142856 L 180.57142857142858 345.14285714285717 L 180.57142857142858 345.14285714285717 Q 173.71428571428572 349.7142857142857 166.85714285714286 345.14285714285717 Q 162.28571428571428 338.2857142857143 166.85714285714286 331.42857142857144 L 243.42857142857142 256 L 243.42857142857142 256 L 166.85714285714286 180.57142857142858 L 166.85714285714286 180.57142857142858 Q 162.28571428571428 173.71428571428572 166.85714285714286 166.85714285714286 L 166.85714285714286 166.85714285714286 Z"
                        />
                    </svg>
              </p>
    
    
            </article>
    </dialog>

    <dialog x-bind:open="modalNuevoSector">
            
      <article style="width: 100%;">
        <header>
          <button aria-label="Close" rel="prev" @click="modalNuevoSector = !modalNuevoSector" wire:click="$refresh"></button>
          <p>
            <strong>Nuevo Sector</strong>
          </p>
        </header>

        
          <fieldset>
            <label>
              Nombre Sector
              <input
                name="nombreSector"
                placeholder="Nombre Sector"
                autocomplete="nombreSector"
                wire:model="nombreSector"


                @error('nombreSector') 
                  aria-invalid="true"
                  aria-describedby="invalid-helper"

                @enderror

              />
              @error('nombreSector') 
                <small id="invalid-helper">
                  {{ $message }} 
                </small>
              @enderror

            </label>
            <label>
              Numero Sector
              <input
                type="text"
                name="numeroSector"
                placeholder="Numero de Sector"
                autocomplete="numeroSector"
                wire:model="numeroSector"
              
                @error('numeroSector') 
                  aria-invalid="true"
                  aria-describedby="invalid-helper"

                @enderror

              />
              @error('numeroSector') 
                <small id="invalid-helper">
                  {{ $message }} 
                </small>
              @enderror


            </label>
          </fieldset>

          <fieldset>
              <label>
                Capacidad

                <select wire:model="capacidadSector" name="" id="" required
                    @error('capacidadSector') 
                      required aria-invalid="true"
                    
                    @enderror
                >
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>

                  <option value="12">13</option>
                  <option value="12">14</option>
                  <option value="12">15</option>
                  <option value="12">16</option>
                  <option value="12">17</option>
                  <option value="12">18</option>

                  <option value="12">19</option>
                  <option value="12">20</option>





                  
                </select>
                
                @error('capacidadSector') 
                  <small id="invalid-helper">
                    {{ $message }} 
                  </small>
                @enderror
              </label>

            </fieldset>


            @if (session()->has('btnGuardar'))

            <p style="color: green;">{{ session('btnGuardar') }}</p>
                
            @else
            
              <input
                type="submit"
                wire:click="guardarSector"   
                value="Guardar"

              />
                
            @endif



        <p style="text-align: right;" @click="modalNuevoSector = !modalNuevoSector" wire:click="$refresh">

          Cerrar - 

            <!-- square-xmark icon by Free Icons (https://free-icons.github.io/free-icons/) -->
              <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="currentColor" viewBox="0 0 512 512">
                  <path
                      d="M 73.14285714285714 18.285714285714285 Q 50.285714285714285 19.428571428571427 34.285714285714285 34.285714285714285 L 34.285714285714285 34.285714285714285 L 34.285714285714285 34.285714285714285 Q 19.428571428571427 50.285714285714285 18.285714285714285 73.14285714285714 L 18.285714285714285 438.85714285714283 L 18.285714285714285 438.85714285714283 Q 19.428571428571427 461.7142857142857 34.285714285714285 477.7142857142857 Q 50.285714285714285 492.57142857142856 73.14285714285714 493.7142857142857 L 438.85714285714283 493.7142857142857 L 438.85714285714283 493.7142857142857 Q 461.7142857142857 492.57142857142856 477.7142857142857 477.7142857142857 Q 492.57142857142856 461.7142857142857 493.7142857142857 438.85714285714283 L 493.7142857142857 73.14285714285714 L 493.7142857142857 73.14285714285714 Q 492.57142857142856 50.285714285714285 477.7142857142857 34.285714285714285 Q 461.7142857142857 19.428571428571427 438.85714285714283 18.285714285714285 L 73.14285714285714 18.285714285714285 L 73.14285714285714 18.285714285714285 Z M 0 73.14285714285714 Q 1.1428571428571428 42.285714285714285 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 L 21.714285714285715 21.714285714285715 Q 42.285714285714285 1.1428571428571428 73.14285714285714 0 L 438.85714285714283 0 L 438.85714285714283 0 Q 469.7142857142857 1.1428571428571428 490.2857142857143 21.714285714285715 Q 510.85714285714283 42.285714285714285 512 73.14285714285714 L 512 438.85714285714283 L 512 438.85714285714283 Q 510.85714285714283 469.7142857142857 490.2857142857143 490.2857142857143 Q 469.7142857142857 510.85714285714283 438.85714285714283 512 L 73.14285714285714 512 L 73.14285714285714 512 Q 42.285714285714285 510.85714285714283 21.714285714285715 490.2857142857143 Q 1.1428571428571428 469.7142857142857 0 438.85714285714283 L 0 73.14285714285714 L 0 73.14285714285714 Z M 166.85714285714286 166.85714285714286 Q 173.71428571428572 162.28571428571428 180.57142857142858 166.85714285714286 L 256 243.42857142857142 L 256 243.42857142857142 L 331.42857142857144 166.85714285714286 L 331.42857142857144 166.85714285714286 Q 338.2857142857143 162.28571428571428 345.14285714285717 166.85714285714286 Q 349.7142857142857 173.71428571428572 345.14285714285717 180.57142857142858 L 268.57142857142856 256 L 268.57142857142856 256 L 345.14285714285717 331.42857142857144 L 345.14285714285717 331.42857142857144 Q 349.7142857142857 338.2857142857143 345.14285714285717 345.14285714285717 Q 338.2857142857143 349.7142857142857 331.42857142857144 345.14285714285717 L 256 268.57142857142856 L 256 268.57142857142856 L 180.57142857142858 345.14285714285717 L 180.57142857142858 345.14285714285717 Q 173.71428571428572 349.7142857142857 166.85714285714286 345.14285714285717 Q 162.28571428571428 338.2857142857143 166.85714285714286 331.42857142857144 L 243.42857142857142 256 L 243.42857142857142 256 L 166.85714285714286 180.57142857142858 L 166.85714285714286 180.57142857142858 Q 162.28571428571428 173.71428571428572 166.85714285714286 166.85714285714286 L 166.85714285714286 166.85714285714286 Z"
                  />
              </svg>
        </p>


      </article>
</dialog>

        


</div>
