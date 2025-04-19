
  <div>
    <style>
      .carousel-container {
        display: flex;
        align-items: center;
        gap: 1rem;
      }
      .carousel {
        display: flex;
        overflow: hidden;
        width: 100%;
        touch-action: pan-y;
      }
      .carousel-track {
        display: flex;
        transition: transform 0.3s ease;
        will-change: transform;
      }
      .carousel-button {
        flex: 0 0 auto;
        margin: 0 0.5rem;
      }
    </style>
  </head>
  <body>
  {{-- <h2>Carrusel de botones con swipe</h2> --}}
  <div class="carousel-container">
    <div class="carousel" id="carousel">
    <div class="carousel-track" id="track">

      <!-- Botones del carrusel -->
      @foreach ($rubros as $r)              
      <button class="carousel-button" style="background-color: rgb(64, 64, 191);" wire:click="$dispatch('filtroRubro', { rubro: '{{ $r->nombre }}' })">{{$r->nombre}}</button>
      @endforeach

    </div>
    </div>
  </div>

  <hr>

  <div role="group">
    <button id="prev" aria-label="Anterior" style="background-color: rgb(64, 64, 191);">
    <!-- arrow-left icon by Free Icons (https://free-icons.github.io/free-icons/) -->
    <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
      <path
      d="M 3.4285714285714284 249.14285714285714 Q 0 252.57142857142858 0 256 Q 0 259.42857142857144 3.4285714285714284 262.85714285714283 L 222.85714285714286 473.14285714285717 L 222.85714285714286 473.14285714285717 Q 228.57142857142858 477.7142857142857 235.42857142857142 473.14285714285717 Q 240 466.2857142857143 235.42857142857142 459.42857142857144 L 32 265.14285714285717 L 32 265.14285714285717 L 502.85714285714283 265.14285714285717 L 502.85714285714283 265.14285714285717 Q 510.85714285714283 264 512 256 Q 510.85714285714283 248 502.85714285714283 246.85714285714286 L 32 246.85714285714286 L 32 246.85714285714286 L 235.42857142857142 52.57142857142857 L 235.42857142857142 52.57142857142857 Q 240 45.714285714285715 235.42857142857142 40 Q 228.57142857142858 34.285714285714285 221.71428571428572 38.857142857142854 L 2.2857142857142856 249.14285714285714 L 3.4285714285714284 249.14285714285714 Z"
      />
    </svg>
    </button>
  
    <button id="next" aria-label="Siguiente" style="background-color: rgb(64, 64, 191);">
    <!-- arrow-right icon by Free Icons (https://free-icons.github.io/free-icons/) -->
    <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="currentColor" viewBox="0 0 512 512">
      <path
      d="M 509.7142857142857 262.85714285714283 Q 512 259.42857142857144 512 256 Q 512 252.57142857142858 509.7142857142857 249.14285714285714 L 290.2857142857143 38.857142857142854 L 290.2857142857143 38.857142857142854 Q 283.42857142857144 34.285714285714285 276.57142857142856 38.857142857142854 Q 272 45.714285714285715 276.57142857142856 52.57142857142857 L 480 246.85714285714286 L 480 246.85714285714286 L 9.142857142857142 246.85714285714286 L 9.142857142857142 246.85714285714286 Q 1.1428571428571428 248 0 256 Q 1.1428571428571428 264 9.142857142857142 265.14285714285717 L 480 265.14285714285717 L 480 265.14285714285717 L 277.7142857142857 459.42857142857144 L 277.7142857142857 459.42857142857144 Q 272 466.2857142857143 276.57142857142856 473.14285714285717 Q 283.42857142857144 477.7142857142857 290.2857142857143 473.14285714285717 L 509.7142857142857 262.85714285714283 L 509.7142857142857 262.85714285714283 Z"
      />
    </svg>
    </button>
  </div>


  
  
  <script>
    // modificaciones

    const track = document.getElementById('track');
    const prev = document.getElementById('prev');
    const next = document.getElementById('next');
    const carousel = document.getElementById('carousel');

    // const totalButtons = document.getElementById('track').children.length;
    
    
    let currentIndex = 0;
    const visibleButtons = 2;
    const totalButtons = track.children.length;
    // console.log("Total de botones:", totalButtons);
  
    function updateCarousel() {
      const buttonWidth = track.children[0].offsetWidth + (totalButtons * 3); // ancho de un botón + margen
      const offset = currentIndex * buttonWidth;
      track.style.transform = `translateX(-${offset}px)`;
    }
  
    prev.addEventListener('click', () => {
      if (currentIndex > 0) {
        currentIndex--;
        updateCarousel();
      }
    });
  
    next.addEventListener('click', () => {
      if (currentIndex < totalButtons - visibleButtons) {
        currentIndex++;
        updateCarousel();
      }
    });
  
    window.addEventListener('resize', updateCarousel);
  
    // GESTO TÁCTIL
    let startX = 0;
    let endX = 0;
  
    carousel.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
    });
  
    carousel.addEventListener('touchmove', (e) => {
      endX = e.touches[0].clientX;
    });
  
    carousel.addEventListener('touchend', () => {
      const deltaX = endX - startX;
      const swipeThreshold = 50; // mínimo en píxeles para considerar swipe
  
      if (deltaX > swipeThreshold && currentIndex > 0) {
        currentIndex--;
        updateCarousel();
      } else if (deltaX < -swipeThreshold && currentIndex < totalButtons - visibleButtons) {
        currentIndex++;
        updateCarousel();
      }
  
      // reiniciar valores
      startX = 0;
      endX = 0;
    });
  </script>


  </div>  

