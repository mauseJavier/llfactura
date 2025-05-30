<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{env('APP_NAME')}}</title>

    <!-- Pico.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css" />

    <!-- Custom styles for this example -->
    <style>

                /* Global CSS variables */
          :root {
            --spacing-company: 3rem;
            --font-weight: 400;
            --border-radius: 0;
          }

          /* Typography */
          h2,
          h3,
          hgroup> :last-child {
            font-weight: 200;
          }

          small {
            color: var(--muted-color);
          }

          /* Header */
          .hero {
            background-color: #394046;
            background-image: url("img/LLSERVICIO.jpg");
            background-position:left;
            background-size:initial;

            background-repeat: no-repeat;
            background-size: cover;
          }
          
          header {
            padding: var(--spacing-company) 0;
          }

          header hgroup> :last-child {
            color: var(--h3-color);
          }

          header hgroup {
            margin-bottom: var(--spacing-company);
          }

          /* Nav */
          summary[role="link"].contrast:is([aria-current], :hover, :active, :focus) {
            background-color: transparent;
            color: var(--contrast-hover);
          }

          /* Main */
          @media (min-width: 992px) {
            main .grid {
              grid-column-gap: var(--spacing-company);
              grid-template-columns: auto 25%;
            }
          }

          form.grid {
            grid-row-gap: 0;
          }

          /* Aside nav */
          aside img {
            margin-bottom: 0.25rem;
          }

          aside p {
            margin-bottom: var(--spacing-company);
            line-height: 1.25;
          }



    </style>
  </head>
  
  <body>
    
    
    
    
    
    <!-- Hero -->
    <div class="hero" data-theme="dark">
      
      {{-- <div class="container-fluid">

        <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />
        <script type="module">
          import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

          createChat({
            webhookUrl: 'https://n8n.llservicios.ar/webhook/a889d2ae-2159-402f-b326-5f61e90f602e/chat'
          });
        </script>

  
      </div> --}}

      <header class="container">



        <div class="grid">
          <div class="col">
              <hgroup>
                <h1 style="color: rgb(255, 255, 255);">Las Lajas Factura</h1>
                <h2 style="color: rgb(250, 247, 247);">Para organizar tu empresa de servicioss.</h2>
              </hgroup>


        <details name="example">
          <summary style="background-color: rgba(255, 0, 0, 0.8); color: white; padding: 15px; border-radius: 8px; font-weight: bold; text-transform: uppercase; animation: pulse 2s infinite; font-size: 1.3rem; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);">
            ¡Solicite una Demo Ahora!
          </summary>

          <iframe src="https://n8n.llservicios.ar/form/563f7721-0819-43e2-84ac-b67eb8fe77b0" width="100%" height="800px" frameborder="0"></iframe>
        </details>

        <style>
          @keyframes pulse {
            0% {
              box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.6);
              transform: scale(1);
            }
            50% {
              box-shadow: 0 0 20px 10px rgba(255, 0, 0, 0.4);
              transform: scale(1.03);
            }
            100% {
              box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
              transform: scale(1);
            }
          }
        </style>


          </div>
          <div class="col">
            <div class="container contact-info" style="background-color: rgba(0, 0, 0, 0.7); color: white; padding: 20px; border-radius: 10px;">
              <h2>Información de Contacto</h2>
              <p><strong>Teléfono:</strong> 299-4562062 - 2942-506803</p>

              <p><strong>Correo Electrónico:</strong> marce_nqn_19@hotmail.com - mause.javi@gmail.com</p>

          </div>

          </div>
        </div>


      </header>

      
    </div>
    <!-- ./ Hero -->



    
    <div class="container">
      <nav>
          <ul>
              <li>
                  {{ config('app.name', 'Laravel') }}
              </li>
  
          </ul>
          <ul>
              <li>
                  @if (Route::has('login'))
                      @auth
                          <a
                              href="{{ url('/panel') }}"
                              
                          >
                              Panel
                          </a>
                      @else
                          <a
                              href="{{ route('login') }}"
                              role="button"
                                  >
                              Entrar
                          </a>

                          | 
          
                          @if (Route::has('register'))
                              <a
                                  href="{{ route('register') }}"
                                  role="button"
                                  style="background-color: green;"
                                  >
                                  Regristro
                              </a>
                          @endif
                      @endauth
                  @endif
              </li>
          </ul>
  
      </nav>

  </div>



    <!-- Main -->
    <main class="container">
      <div class="grid">
        <section>
          <hgroup>
            <h2>Descripción de LLServicios: Gestión Integral de Servicios para Empresas y Usuarios</h2>
            <h3>Detalles:</h3>
          </hgroup>
          <p>
            LLServicios es una aplicación innovadora diseñada para ofrecer una gestión integral de servicios, brindando una solución eficiente tanto para empresas como para usuarios individuales. Con un enfoque centrado en la simplicidad y la eficacia, LLServicios simplifica la administración de servicios, mejorando la productividad y la experiencia del usuario.
          </p>
          <figure>
            <img src="img/LLServicios001.jpg" alt="Architecture" />
            <figcaption>
              Image from
              <a href="llservicios.ar" target="_blank">llservicios.ar</a>
            </figcaption>
          </figure>
          
          <h4>Características Destacadas:</h4>

          <h3>Administración Empresarial: </h3>

          Optimización de Recursos: LLServicios permite a las empresas gestionar sus servicios de manera efectiva, optimizando recursos y mejorando la eficiencia operativa.
          Seguimiento en Tiempo Real: Supervisa el estado de los servicios en tiempo real, facilitando la toma de decisiones informadas.
          <h3>Acceso para Usuarios: </h3>

          Interfaz Amigable: La aplicación proporciona a los usuarios individuales una interfaz amigable e intuitiva para solicitar y gestionar servicios con facilidad.
          Historial de Servicios: Los usuarios pueden acceder a un historial completo de los servicios utilizados, lo que brinda transparencia y control sobre sus experiencias.
          <h3>Solicitudes y Programación: </h3>

          Solicitud de Servicios: Empresas y usuarios pueden realizar solicitudes de servicios de manera rápida y sencilla, especificando detalles importantes.
          Programación Eficiente: La función de programación permite a los usuarios planificar servicios de acuerdo con sus necesidades y preferencias.
           <h3>Comunicación Integrada:</h3>

          
          <p>Notificaciones Instantáneas: Mantente informado con notificaciones instantáneas sobre el estado de tus servicios y actualizaciones relevantes.
            Comunicación Directa: Facilita la comunicación directa entre empresas y usuarios para garantizar una colaboración efectiva.
            LLServicios representa una solución completa para la administración de servicios, ofreciendo una plataforma única que conecta eficazmente a empresas y usuarios. Optimiza tus operaciones, mejora la experiencia del cliente y simplifica la gestión de servicios con LLServicios. ¡Descárgalo ahora y descubre una nueva forma de gestionar servicios de manera inteligente y eficiente!</p>
          
        </section>

        <aside>
          <a href="#" aria-label="Example" onclick="event.preventDefault()"
            ><img src="img/LLServicios004.jpeg" alt="Architecture"
          /></a>
          <p>
            <a href="#" onclick="event.preventDefault()">Conectado</a><br />
            <small>LLeva el control de todos tus servicios desde cualquier lugar.</small>
          </p>
          <a href="#" aria-label="Example" onclick="event.preventDefault()"
            ><img src="img/LLServicios007.jpg" alt="Architecture"
          /></a>
          <p>
            <a href="#" onclick="event.preventDefault()">Notificaciones Automaticas</a><br />
            <small>Olvidate de tener que recordarle a tus clientes sus pagos, Llservicios envia una notificacion de aviso de forma automatica.</small>
          </p>
          <a href="#" aria-label="Example" onclick="event.preventDefault()"
            ><img src="img/LLServicios010.jpg" alt="Architecture"
          /></a>
          <p>
            <a href="#" onclick="event.preventDefault()">Que no se te escape nada!</a><br />
            <small
              >Podrás realizar recibos para tus clientes de manera simple y rapida, manteniendo la claridad de tu negocio</small
            >
          </p>
        </aside>
      </div>
    </main>
    <!-- ./ Main -->

    <!-- Subscribe -->
    <section aria-label="Subscribe example">
      <div class="container">
        <article>
          <hgroup>
            <h2>Subscribe</h2>
            <h3>Litora torquent per conubia nostra</h3>
          </hgroup>
          <form class="grid">
            <input
              type="text"
              id="firstname"
              name="firstname"
              placeholder="First name"
              aria-label="First name"
              required
            />
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Email address"
              aria-label="Email address"
              required
            />
            <button type="submit" onclick="event.preventDefault()">Subscribe</button>
          </form>
        </article>
      </div>
    </section>
    <!-- ./ Subscribe -->

    <!-- Footer -->
    <footer class="container">
      <small
        >Built with <a href="https://picocss.com">Pico</a> •
        <a href="https://github.com/picocss/examples/tree/master/v1-company/">Source code</a></small
      >
    </footer>
    <!-- ./ Footer -->

    <!-- Minimal theme switcher -->
    <script>

                /*!
          * Minimal theme switcher
          *
          * Pico.css - https://picocss.com
          * Copyright 2019-2023 - Licensed under MIT
          */

          const themeSwitcher = {
            // Config
            _scheme: "auto",
            menuTarget: "details[role='list']",
            buttonsTarget: "a[data-theme-switcher]",
            buttonAttribute: "data-theme-switcher",
            rootAttribute: "data-theme",
            localStorageKey: "picoPreferredColorScheme",

            // Init
            init() {
              this.scheme = this.schemeFromLocalStorage;
              this.initSwitchers();
            },

            // Get color scheme from local storage
            get schemeFromLocalStorage() {
              if (typeof window.localStorage !== "undefined") {
                if (window.localStorage.getItem(this.localStorageKey) !== null) {
                  return window.localStorage.getItem(this.localStorageKey);
                }
              }
              return this._scheme;
            },

            // Preferred color scheme
            get preferredColorScheme() {
              return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            },

            // Init switchers
            initSwitchers() {
              const buttons = document.querySelectorAll(this.buttonsTarget);
              buttons.forEach((button) => {
                button.addEventListener(
                  "click",
                  (event) => {
                    event.preventDefault();
                    // Set scheme
                    this.scheme = button.getAttribute(this.buttonAttribute);
                    // Close dropdown
                    document.querySelector(this.menuTarget).removeAttribute("open");
                  },
                  false
                );
              });
            },

            // Set scheme
            set scheme(scheme) {
              if (scheme == "auto") {
                this.preferredColorScheme == "dark" ? (this._scheme = "dark") : (this._scheme = "light");
              } else if (scheme == "dark" || scheme == "light") {
                this._scheme = scheme;
              }
              this.applyScheme();
              this.schemeToLocalStorage();
            },

            // Get scheme
            get scheme() {
              return this._scheme;
            },

            // Apply scheme
            applyScheme() {
              document.querySelector("html").setAttribute(this.rootAttribute, this.scheme);
            },

            // Store scheme to local storage
            schemeToLocalStorage() {
              if (typeof window.localStorage !== "undefined") {
                window.localStorage.setItem(this.localStorageKey, this.scheme);
              }
            },
          };

          // Init
          themeSwitcher.init();


    </script>
  </body>
</html>