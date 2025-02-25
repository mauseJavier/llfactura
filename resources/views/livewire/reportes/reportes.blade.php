<div>

    <div class="container">
        <h3>Reportes</h3>



        @if (Route::has($ruta))
            
            <iframe width="100%" height="1000px" src="{{route($ruta)}}" frameborder="0"></iframe>
        
        @else

        <article>
            <h3>Ruta incorrecta</h3>
        </article>
            
        @endif

    </div>
</div>
