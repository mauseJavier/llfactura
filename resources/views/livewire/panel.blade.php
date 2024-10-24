<div>

    @if (session('mensaje'))
        <article>
            {{ session('mensaje') }}

        </article>

    @endif

    <div class="container">

        <div class="grid">
            <div class="col" style="text-align: center;">
                <a href="/venta" wire:navigate>                    
                    <article class="ponerSombras">
                        <i class="fa-solid fa-tag fa-5x"></i>
                        <hr>              
                        <h3><strong>Nueva Venta</strong></h3>
                    </article>
                </a>

            </div>
            <div class="col" style="text-align: center;">

                <a href="{{route('comprobante')}}" wire:navigate>                       
                    <article class="ponerSombras">
                        <i class="fa-solid fa-receipt fa-5x"></i>   
                        <hr>
                        
                        <h3><strong>Comprobantes</strong></h3> 
                                 
                    </article>
                </a>
            </div>

        </div>

        <div class="grid">
            <div class="col" style="text-align: center;">
                <a href="/stock" wire:navigate>                    
                    <article class="ponerSombras">
                        {{-- <i class="fa-solid fa-file-invoice-dollar fa-10x"></i>   --}}
                        <i class="fa-solid fa-boxes-packing fa-5x"></i>
                        <hr>              
                        <h3><strong>Stock</strong></h3>
                    </article>
                </a>
            </div>
            <div class="col" style="text-align: center;">
                <a href="/cliente" wire:navigate>                    
                    <article class="ponerSombras">
                        <i class="fa-solid fa-users fa-5x"></i>
                        <hr>              
                        <h3><strong>Clientes</strong></h3>
                    </article>
                </a>
            </div>

            <div class="col" style="text-align: center;">
                <a href="/presupuesto" wire:navigate>                    
                    <article class="ponerSombras">
                        <i class="fa-solid fa-file-invoice-dollar fa-5x"></i>  
                        <hr>              
                        <h3><strong>Presupuestos</strong></h3>
                    </article>
                </a>
            </div>

        </div>


        
        {{-- //una vista solo pra los admin y super  --}}
        @if ((Auth::user()->role_id == 2 || Auth::User()->role_id == 3 || Auth::User()->role_id == 4))

            <div class="grid">
                <div class="col" style="text-align: center;">
                    <a href="{{route('inventario')}}" wire:navigate>                    
                    <article class="ponerSombras">
                        {{-- <i class="fa-solid fa-file-invoice-dollar fa-10x"></i>                 --}}
                        <i class="fa-solid fa-boxes-stacked fa-5x"></i>
                        <hr>
                        <h3><strong>Inventario</strong></h3>
                    </article>
                    </a>
                </div>
                <div class="col" style="text-align: center;">
                    <a href="/proveedor" wire:navigate>                    
                        <article class="ponerSombras">
                            {{-- <i class="fa-solid fa-users fa-10x"></i> --}}
                            <i class="fa-solid fa-dolly fa-5x"></i>
                            <hr>              
                            <h3><strong>Proveedores</strong></h3>
                        </article>
                    </a>
                </div>
            </div>
        @endif

    </div>



        <!-- Agregamos el script para capturar los eventos keyup -->
        <script>
            document.addEventListener('keyup', function(event) {
                // Emitir el evento de Livewire con la tecla presionada
                if(event.code == 'Enter'){

                    
                    

                }

            });
        </script>
    

</div>
