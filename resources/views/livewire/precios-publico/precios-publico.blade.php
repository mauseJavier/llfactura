
    <div 
        style="
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
        "
    >


        <div 
            class="container"
            wire:keydown.window="captureKey($event.key)"
        >



            <article >

                <div class="grid">

                    <h1>{{$empresa->razonSocial}}</h1>

                    {{-- <img width="20%;"  src="{{ asset('storage/'.$empresa->cuit.'/logo/logo.png')}}" alt="Example Image"> --}}
                    {{-- <input
                        type="search"
                        name="search"
                        placeholder="Search"
                        aria-label="Search"
                    /> --}}
                </div>

                </article>
            


            <article>
                <header><h2>{{$detalle}} ({{implode('', $this->keys) == '' ? $searchQuery : implode('', $this->keys) }})</h2></header>
                <label for="">
                    <h2 style="font-size: 500%;">{{$precio}}</h2>
                </label>
            </article>
            <div x-data="fullscreenHandler()" class="p-4 border rounded bg-gray-100">
                <p class="mb-4">Presiona el botón para pantalla completa.</p>
                <button 
                    x-on:click="toggleFullscreen" 
                    class="px-4 py-2 bg-blue-500 text-white rounded"
                >
                    Pantalla Completa
                </button>
            </div>
        </div>




        <script>
            function fullscreenHandler() {
                return {
                    isFullscreen: false,
                    async toggleFullscreen() {
                        if (!this.isFullscreen) {
                            // Entrar en pantalla completa
                            if (document.documentElement.requestFullscreen) {
                                await document.documentElement.requestFullscreen();
                            } else if (document.documentElement.webkitRequestFullscreen) { // Safari
                                await document.documentElement.webkitRequestFullscreen();
                            } else if (document.documentElement.msRequestFullscreen) { // IE11
                                await document.documentElement.msRequestFullscreen();
                            }
                            this.isFullscreen = true;
                        } else {
                            // Salir de pantalla completa
                            //if (document.exitFullscreen) {
                            //    await document.exitFullscreen();
                            //} else if (document.webkitExitFullscreen) { // Safari
                            //    await document.webkitExitFullscreen();
                            //} else if (document.msExitFullscreen) { // IE11
                            //    await document.msExitFullscreen();
                            //}
                            //this.isFullscreen = false;
                        }
                    }
                };
            }
        </script>




    </div>
