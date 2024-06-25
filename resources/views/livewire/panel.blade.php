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
                        <i class="fa-solid fa-tag fa-10x"></i>
                        <hr>              
                        <h1><strong>Nueva Venta</strong></h1>
                    </article>
                </a>

            </div>
            <div class="col" style="text-align: center;">

                <a href="{{route('comprobante')}}" wire:navigate>                       
                    <article class="ponerSombras">
                        <i class="fa-solid fa-receipt fa-10x"></i>   
                        <hr>
                        
                        <h1><strong>Comprobantes</strong></h1> 
                                 
                    </article>
                </a>
            </div>

        </div>

        <div class="grid">
            <div class="col" style="text-align: center;">
                <a href="/stock" wire:navigate>                    
                    <article class="ponerSombras">
                        {{-- <i class="fa-solid fa-file-invoice-dollar fa-10x"></i>   --}}
                        <i class="fa-solid fa-boxes-packing fa-10x"></i>
                        <hr>              
                        <h1><strong>Stock</strong></h1>
                    </article>
                </a>
            </div>
            <div class="col" style="text-align: center;">
                <a href="/cliente" wire:navigate>                    
                    <article class="ponerSombras">
                        <i class="fa-solid fa-users fa-10x"></i>
                        <hr>              
                        <h1><strong>Clientes</strong></h1>
                    </article>
                </a>
            </div>

            <div class="col" style="text-align: center;">
                <a href="/presupuesto" wire:navigate>                    
                    <article class="ponerSombras">
                        <i class="fa-solid fa-file-invoice-dollar fa-10x"></i>  
                        <hr>              
                        <h1><strong>Presupuestos</strong></h1>
                    </article>
                </a>
            </div>

        </div>


        
        {{-- //una vista solo pra los admin y super  --}}
        @if ((Auth::user()->role_id == 2 || Auth::User()->role_id == 3))

            <div class="grid">
                <div class="col" style="text-align: center;">
                    <a href="{{route('inventario')}}" wire:navigate>                    
                    <article class="ponerSombras">
                        {{-- <i class="fa-solid fa-file-invoice-dollar fa-10x"></i>                 --}}
                        <i class="fa-solid fa-boxes-stacked fa-10x"></i>
                        <hr>
                        <h1><strong>Inventario</strong></h1>
                    </article>
                    </a>
                </div>
                <div class="col" style="text-align: center;">
                    <a href="/proveedor" wire:navigate>                    
                        <article class="ponerSombras">
                            {{-- <i class="fa-solid fa-users fa-10x"></i> --}}
                            <i class="fa-solid fa-dolly fa-10x"></i>
                            <hr>              
                            <h1><strong>Proveedores</strong></h1>
                        </article>
                    </a>
                </div>
            </div>
        @endif

    </div>



    
    

</div>
