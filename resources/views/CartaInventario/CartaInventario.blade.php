<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Carta Digital {{$empresa->razonSocial}}</title>
    <meta name="author" content="">

    <!-- SEO -->
    <meta name="description" content="Sistema Integral de Gestion Administrativo Web ">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link rel="shortcut icon" href="img/favicon.ico">

    <!-- Responsive Tag -->
    <meta name="viewport" content="width=device-width">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('storage/cartaDigital/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/cartaDigital/css/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/cartaDigital/css/plugin.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/cartaDigital/css/main.css') }}">

    <!--[if lt IE 9]>
            <script src="js/vendor/html5-3.6-respond-1.4.2.min.js"></script>
        <![endif]-->
</head>

<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Preloder-->
    <div class="preloder animated">
        <div class="scoket">
            <img src="{{ asset('storage/cartaDigital/img/preloader.svg') }}" alt="" />
        </div>
    </div>

    <div class="body">

        <div class="main-wrapper">
            <!-- Navigation-->
            <nav class="navbar navbar-fixed-top">
                {{-- <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="./index.html">
                            <img src="img/nav-logo.png" alt="nav-logo">
                        </a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <!--a href="./index.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Home<span class="caret"></span></a-->
                                <ul class="dropdown-menu">
                                    <li><a href="./index.html">Home - Image</a></li>
                                    <li><a href="./index_slider.html">Home - Header Slider</a></li>
                                    <li><a href="./index_video.html">Home - Video Background</a></li>
                                    <li><a href="./index_parallax.html">Home - Parallax</a></li>
                                    <li><a href="./index_animation.html">Home - Scroll Animation</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <!--a href="./menu_all.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CONFITERIA<span class="caret"></span></a-->
                                <ul class="dropdown-menu">
                                    <li><a href="./menu_list.html">Menu - List</a></li>
                                    <li><a href="./menu_overlay.html">Menu - Overlay</a></li>
                                    <li><a href="./menu_tile.html">Menu - Tile</a></li>
                                    <li><a href="./menu_grid.html">Menu - Grid</a></li>
                                    <li><a href="./menu_all.html">Menu All</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <!--a href="./reservation.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reservation<span class="caret"></span></a-->
                                <ul class="dropdown-menu">
                                    <li><a href="./reservation.html">Reservation</a></li>
                                    <li><a href="./reservation-ot.html">Reservation - Opentable</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <!--a href="./about.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pages<span class="caret"></span></a-->
                                <ul class="dropdown-menu">
                                    <li><a href="./about.html">About</a></li>
                                    <li><a href="./gallery.html">Gallery</a></li>
                                    <li><a href="./elements.html">Shortcodes</a></li>
                                    <li><a href="./shop_account.html">Login / Signup</a></li>
                                    <li><a href="./404.html">404 Page</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <!--a href="./recipe.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Recipe<span class="caret"></span></a-->
                                <ul class="dropdown-menu">
                                    <li><a href="./recipe.html">Recipe - 2Col</a></li>
                                    <li><a href="./recipe_3col.html">Recipe - 3Col</a></li>
                                    <li><a href="./recipe_4col.html">Recipe - 4Col</a></li>
                                    <li><a href="./recipe_masonry.html">Recipe - Masonry</a></li>
                                    <li>
                                        <a href="./recipe_detail-image.html">Recipe - Single <span class="caret-right"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="./recipe_detail-image.html">Recipe - Image</a></li>
                                            <li><a href="./recipe_detail-slider.html">Recipe - Gallery</a></li>
                                            <li><a href="./recipe_detail-video.html">Recipe - Video</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <!--a href="./blog_right_sidebar.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Blog<span class="caret"></span></a-->
                                <ul class="dropdown-menu">
                                    <li><a href="./blog_right_sidebar.html">Blog - Right Sidebar</a></li>
                                    <li><a href="./blog_left_sidebar.html">Blog - Left Sidebar</a></li>
                                    <li><a href="./blog_fullwidth.html">Blog - Fullwidth</a></li>
                                    <li><a href="./blog_masonry.html">Blog - Masonry</a></li>
                                    <li>
                                        <a href="./blog_single_image.html">Blog - Single <span class="caret-right"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="./blog_single_image.html">Blog - Image</a></li>
                                            <li><a href="./blog_single_slider.html">Blog - Gallery</a></li>
                                            <li><a href="./blog_single_video.html">Blog - Video</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <!--a href="./shop_fullwidth.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Shop<span class="caret"></span></a-->
                                <ul class="dropdown-menu">
                                    <li><a href="./shop_fullwidth.html">Shop - Full</a></li>
                                    <li><a href="./shop_left_sidebar.html">Shop - Left Sidebar</a></li>
                                    <li><a href="./shop_right_sidebar.html">Shop - Right Sidebar</a></li>
                                    <li>
                                        <!--a href="./shop_single_full.html">Shop - Single <span class="caret-right"></span></a-->
                                        <ul class="dropdown-menu">
                                            <li><a href="./shop_single_full.html">Shop - Full</a></li>
                                            <li><a href="./shop_single_left.html">Shop - Left Sidebar</a></li>
                                            <li><a href="./shop_single_right.html">Shop - Right Sidebar</a></li>
                                        </ul>
                                    </li>
                                    <!--li><a href="./shop_cart.html">Shop - Cart</a></li>
                                    <li><a href="./shop_checkout.html">Shop - Checkout</a></li>
                                    <li><a href="./shop_account.html">Shop - Account</a></li>
                                    <li><a href="./shop_account_detail.html">Shop - Account Detail</a></l-->
                                </ul>
                            </li>
                            <!--li><a href="./contact.html">MENU</a></li-->
                            <li class="dropdown">
                                <!--ACA ESTA EL CARRITO-->
                                <!--a class="css-pointer dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-shopping-cart fsc pull-left"></i><span class="cart-number">3</span><span class="caret"></span></a-->
                                <div class="cart-content dropdown-menu">
                                    <div class="cart-title">
                                        <!--h4>Carrito</h4-->
                                    </div>
                                    <div class="cart-items">
                                        <div class="cart-item clearfix">
                                            <div class="cart-item-image">
                                                <!--a href="./shop_single_full.html"><img src="img/cart-img1.jpg" alt="Breakfast with coffee"></a>
                                            </div>
                                            <div class="cart-item-desc">
                                                <!--a href="./shop_single_full.html">Breakfast with coffee</a-->
                                                <!--span class="cart-item-price">$19.99</span-->
                                                <!--span class="cart-item-quantity">x 2</span-->
                                                <i class="fa fa-times ci-close"></i>
                                            </div>
                                        </div>
                                        <!--div class="cart-item clearfix"-->
                                            <!--div class="cart-item-image"-->
                                                <!--a href="./shop_single_full.html"><img src="img/cart-img2.jpg" alt="Chicken stew"></a-->
                                            </div>
                                            <!--div class="cart-item-desc"-->
                                                <!--a href="./shop_single_full.html">Chicken stew</a-->
                                                <!--span class="cart-item-price">$24.99</span-->
                                                <!--span class="cart-item-quantity">x 3</span-->
                                                <i class="fa fa-times ci-close"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!--div class="cart-action clearfix">
                                        <span class="pull-left checkout-price">$ 114.95</span>
                                        <a class="btn btn-default pull-right" href="./shop_cart.html">View Cart</a>
                                    </div-->
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!--/.navbar-collapse -->
                </div> --}}
            </nav>

<section class="home">
                <div class="tittle-block">
                    <div class="logo">
                        <a href="./index.html">
                            <img src="{{ asset('storage/'.$empresa->cuit.'/logo/logo.png') }}" alt="logo">
                        </a>
                    </div>
                    <h1>{{$empresa->razonSocial}}</h1>
                    <h2>{{$empresa->domicilio}}</h2>
                </div>
                <div class="scroll-down">
                    <a href="#about">
                        <img src="{{ asset('storage/cartaDigital/img/arrow-down.png') }}" alt="down-arrow">
                    </a>
                </div>
            </section>


            <!-- menu-->
            <section class="menu space60">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page-header wow fadeInDown">
                                <h1>{{$empresa->razonSocial}}<small>Menu</small></h1>
                            </div>
                        </div>
                    </div>
                    <div class="food-menu wow fadeInUp">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="menu-tags">
                                    {{-- <span data-filter="*" class="tagsort-active" onclick="FILTROcomidas('TODO')">TODO</span>
                                    <span data-filter=".HAMBURGUESAS" onclick="FILTROcomidas('HAMBURGUESAS')">HAMBURGUESAS</span>
                                    <span  onclick="FILTROcomidas('PAPAS')">PAPAS</span>
                                    <span  onclick="FILTROcomidas('PIZZAS')" >PIZZAS</span>
                                    <span onclick="FILTROcomidas('CAFETERÍA')" >CAFETERÍA</span>
                                    <span onclick="FILTROcomidas('TARTAS')" >TARTAS</span> --}}
                                    
                                    @foreach ($rubro as $r)
                                    
                                        <span onclick="FILTROcomidas('{{$r->nombre}}')" >{{$r->nombre}}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row menu-items" id="comidas">
                            @foreach ($inventario as $i)

                                {{-- @dump($i) --}}
                                <div class="menu-item col-sm-6 col-md-12 starter dinner desserts {{$i->rubro}} - {{$i->proveedor}}">
                                    <div class="clearfix menu-wrapper">
                                        <h4>{{$i->detalle}}</h4>
                                        <span class="price">${{$i->precio1}}</span>
                                        <div class="dotted-bg"></div>
                                    </div>
                                    <p>{{$i->rubro}} - {{$i->proveedor}}</p>
                                </div>
                                
                            @endforeach

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="menu-btn">
                                    <!-- a class="btn btn-default btn-lg" href="./menu_all.html" role="button">Explore our menu</a-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- subscribe -->
            <!-- section class="subscribe">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1>Subscribe</h1>
                            <p>Get updates about new dishes and upcoming events</p>
                            <form class="form-inline" action="php/subscribe.php" id="invite" method="POST">
                                <div class="form-group">
                                    <input class="e-mail form-control" name="email" id="address" type="email" placeholder="Your Email Address" required>
                                </div>
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-angle-right"></i>
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </section-->

            <!-- Footer-->
            <section class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <h1>{{$empresa->razonSocial}}</h1>
                            <p>Vení a disfrutar de una inolvidable noche con amigos y familia, la mejor comida y shows en vivo con los mejores artistas. </p>
                             <!-- a href="./about.html">Read more &rarr;</a-->
                        </div>
                        <<!--div class="col-md-4  col-sm-6">
                            <h1>PLATOS</h1>
                            <div class="footer-blog clearfix">
                                <a href="./blog_right_sidebar.html">
                                    <img src="img/thumb8.png" class="img-responsive footer-photo" alt="blog photos">
                                    <p class="footer-blog-text">CAPUCCINO</p>
                                    <p class="footer-blog-date">4 marzo 2022</p>
                                </a>
                            </div-->
                            <!--div class="footer-blog clearfix last">
                                <a href="./blog_right_sidebar.html">
                                    <img src="img/thumb9.png" class="img-responsive footer-photo" alt="blog photos">
                                    <p class="footer-blog-text">Especial dias de san valentín</p>
                                    <p class="footer-blog-date">14 febrero 2022</p>
                                </a>
                            </div>
                        </div-->
                        <div class="col-md-4  col-sm-6">
                            <h1>ENCONTRANOS</h1>
                            <div class="footer-social-icons">
                                <a href="http://www.facebook.com">
                                    <i class="fa fa-facebook-square"></i>
                                </a>
                                <a href="http://www.twitter.com">
                                    <i class="fa fa-twitter"></i>
                                </a>
                                <a href="http://plus.google.com">
                                    <i class="fa fa-google"></i>
                                </a>
                                <a href="http://www.youtube.com">
                                    <i class="fa fa-youtube-play"></i>
                                </a>
                                <a href="http://www.vimeo.com">
                                    <i class="fa fa-vimeo"></i>
                                </a>
                                <a href="http://www.pinterest.com">
                                    <i class="fa fa-pinterest-p"></i>
                                </a>
                                <a href="http://www.linkedin.com">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            </div>
                            <div class="footer-address">
                                <p><i class="fa fa-map-marker"></i>{{$empresa->domicilio}}</p>
                                <p><i class="fa fa-phone"></i>Tel: {{$empresa->telefono}}</p>
                                <p><i class="fa fa-envelope-o"></i>{{$empresa->correo}}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer - Copyright -->
                <div class="footer-copyrights">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <img src="img/favicon.ico" alt="nav-logo">
                                <p><i class="fa fa-copyright"></i>LLFactura</p>
                                <p><i ></i> Contacto: 2994562062 - 2942506803 </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div> 

    <!-- Javascript -->
    
    
    {{-- <script src="js/vendor/jquery-1.11.2.min.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/vendor/jquery.flexslider-min.js"></script>
    <script src="js/vendor/spectragram.js"></script>
    <script src="js/vendor/owl.carousel.min.js"></script>
    <script src="js/vendor/velocity.min.js"></script>
    <script src="js/vendor/velocity.ui.min.js"></script>
    <script src="js/vendor/bootstrap-datepicker.min.js"></script>
    <script src="js/vendor/bootstrap-clockpicker.min.js"></script>
    <script src="js/vendor/jquery.magnific-popup.min.js"></script>
    <script src="js/vendor/isotope.pkgd.min.js"></script>
    <script src="js/vendor/slick.min.js"></script>
    <script src="js/vendor/wow.min.js"></script>
    <script src="js/animation.js"></script>
    <script src="js/vendor/vegas/vegas.min.js"></script>
    <script src="js/vendor/jquery.mb.YTPlayer.js"></script>
    <script src="js/vendor/jquery.stellar.js"></script>
    <script src="js/main.js"></script>
    <script src="js/vendor/mc/jquery.ketchup.all.min.js"></script>
    <script src="js/vendor/mc/main.js"></script> --}}
    
    <script src="{{ asset('storage/cartaDigital/js/myapp/app.js') }}"></script>
    
    <script src="{{ asset('storage/cartaDigital/js/vendor/jquery-1.11.2.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/jquery.flexslider-min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/spectragram.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/velocity.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/velocity.ui.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/slick.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/wow.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/animation.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/vegas/vegas.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/jquery.mb.YTPlayer.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/jquery.stellar.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/main.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/mc/jquery.ketchup.all.min.js') }}"></script>
    <script src="{{ asset('storage/cartaDigital/js/vendor/mc/main.js') }}"></script>

    

</body>

</html>


