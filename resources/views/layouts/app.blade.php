<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="author" content="Untree.co" />
        <link rel="shortcut icon" href="favicon.png" />

        <meta name="description" content="" />
        <meta name="keywords" content="bootstrap, bootstrap5" />

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">

        <!-- Google font -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Font icons -->
        <link rel="stylesheet" href="{{ asset('assets/fonts/icomoon/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/fonts/flaticon/font/flaticon.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/addons/fontawesome/css/all.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/addons/bootstrap-icons/font/bootstrap-icons.min.css') }}" />

        <!-- Main styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/property/tiny-slider.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/property/aos.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/property/style.css') }}" />

        <!-- Custom style -->
        <link rel="stylesheet" href="{{ asset('assets/addons/cropper/css/cropper.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/addons/jquery/css/jquery-ui.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/addons/mdb/css/mdb.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

        <style>
            .menu-bg-wrap { background-color: #167c02; }
            .site-footer a { text-decoration: none!important; }
            #main-search .nav-link.active { font-weight: bold; color: black !important; border-bottom: 2px solid green !important; }
            .property-item .property-content .price { color: #167c02; }
            .property-item .property-content .price span:after { background-color: #167c02; }
        </style>

        <title>
@if (!empty($page_title))
            {{ $page_title }}
@else
            Addrressimmo
@endif
        </title>
    </head>

    <body>
        <div class="site-mobile-menu site-navbar-target">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close">
                    <span class="icofont-close js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>

        <nav class="site-nav">
            <div class="container">
                <div class="menu-bg-wrap">
                    <div class="site-navigation">
                        <a href="{{ route('home') }}" class="logo m-0 float-start">
                            <img src="{{ asset('assets/img/logo-reverse-1.png') }}" width="50" alt="" srcset="">
                            AddrressImmo
                        </a>

                        <ul class="js-clone-nav d-none d-lg-inline-block text-start site-menu float-end">
                            <li class="{{ Route::is('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Accueil</a></li>
                            <li class="has-children{{ Route::is('product.home') || Route::is('product.entity') ? ' active' : '' }}">
                                <a href="{{ route('product.home') }}">Services</a>
                                <ul class="dropdown">
                                    <li>
                                        <a href="{{ route('product.entity', ['entity' => 'buy']) }}"><i class="bi bi-handbag me-2"></i>Achat</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('product.entity', ['entity' => 'rent']) }}"><i class="bi bi-clock me-2"></i>Location</a>
                                    </li>
                                    <li><a href="{{ route('product.entity', ['entity' => 'build']) }}"><i class="bi bi-bricks me-2"></i>Construction</a></li>
                                    <li><a href="{{ route('product.entity', ['entity' => 'sell']) }}"><i class="bi bi-cash-coin me-2"></i>Vente</a></li>
                                    <li><a href="{{ route('product.entity', ['entity' => 'moving']) }}"><i class="bi bi-luggage me-2"></i>Déménagement</a></li>
                                    <li><a href="{{ route('product.entity', ['entity' => 'ad']) }}"><i class="bi bi-megaphone me-2"></i>Annonce</a></li>
                                </ul>
                            </li>
                            <li class="{{ Route::is('about') ? 'active' : '' }}"><a href="{{ route('about') }}">A propos</a></li>
                            <li class="{{ Route::is('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">Nous contacter</a></li>
                            <li>
@if (!empty($current_user))
                                <a href="{{ route('account.home') }}">
                                    <img src="{{ $current_user->avatar_url ?? asset('assets/img/user.png') }}" width="40" height="40" alt="{{ $current_user->firstname . ' ' . $current_user->lastname }}" class="rounded-circle">
                                </a>
@else
                                <a href="{{ route('login') }}">S'identifier</a>
@endif
                            </li>
                        </ul>

                        <a href="#" class="burger light me-auto float-end mt-1 site-menu-toggle js-menu-toggle d-inline-block d-lg-none" data-toggle="collapse" data-target="#main-navbar">
                            <span></span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

@yield('app-content')

        <div class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="widget">
                            <h3>Contact</h3>
                            <address>43 Raymouth Rd. Baltemoer, London 3910</address>
                            <ul class="list-unstyled links">
                                <li><a href="tel://11234567890">+1(123)-456-7890</a></li>
                                <li><a href="tel://11234567890">+1(123)-456-7890</a></li>
                                <li>
                                    <a href="mailto:info@mydomain.com">info@mydomain.com</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.widget -->
                    </div>
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="widget">
                            <h3>Liens utiles</h3>
                            <ul class="list-unstyled float-start links">
                                <li><a href="{{ route('about') }}">A propos</a></li>
                                <li><a href="{{ route('product.home') }}">Services</a></li>
                                <li><a href="{{ route('product.entity', ['entity' => 'sell']) }}">Vendre</a></li>
                                <li><a href="{{ route('product.entity', ['entity' => 'buy']) }}">Acheter</a></li>
                                <li><a href="{{ route('product.entity', ['entity' => 'rent']) }}">Louer</a></li>
                            </ul>
                            <ul class="list-unstyled float-start links">
                                <li><a href="{{ route('product.entity', ['entity' => 'build']) }}">Construire</a></li>
                                <li><a href="{{ route('product.entity', ['entity' => 'moving']) }}">Déménager</a></li>
                                <li><a href="{{ route('product.entity', ['entity' => 'ad']) }}">Faire annonce</a></li>
                            </ul>
                        </div>
                        <!-- /.widget -->
                    </div>
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="widget">
                            <h3>Chez nous</h3>
                            <ul class="list-unstyled links">
                                <li><a href="{{ route('contact') }}">Nous contacter</a></li>
                            </ul>

                            <ul class="list-unstyled social">
                                <li>
                                    <a href="#"><span class="icon-instagram"></span></a>
                                </li>
                                <li>
                                    <a href="#"><span class="icon-twitter"></span></a>
                                </li>
                                <li>
                                    <a href="#"><span class="icon-facebook"></span></a>
                                </li>
                                <li>
                                    <a href="#"><span class="icon-linkedin"></span></a>
                                </li>
                                <li>
                                    <a href="#"><span class="icon-pinterest"></span></a>
                                </li>
                                <li>
                                    <a href="#"><span class="icon-dribbble"></span></a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.widget -->
                    </div>
                    <!-- /.col-lg-4 -->
                </div>
                <!-- /.row -->

                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <p>
                            Copyright Addrressimmo &copy; {{ date('Y') }} . Tous droits réservés. &mdash; Designed by <a href="https://silasmas.com">SDEV</a>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /.container -->
        </div>
        <!-- /.site-footer -->

        <!-- Preloader -->
        <div id="overlayer"></div>
        <div class="loader">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>

        <script type="text/javascript" src="{{ asset('assets/addons/jquery/js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/addons/jquery/jquery-ui/jquery-ui.min.js') }}"></script>
        {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/i18n/jquery-ui-i18n.min.js"></script> --}}
        <script type="text/javascript" src="{{ asset('assets/addons/jquery/datetimepicker/js/jquery.datetimepicker.full.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/bootstrap.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/addons/mdb/js/mdb.umd.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/tiny-slider.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/aos.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/navbar.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/counter.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/custom.js') }}"></script>
        <!-- Pretype="text/javascript" loader -->
        <script type="text/javascript" src="{{ asset('assets/addons/autosize/js/autosize.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/addons/cropper/js/cropper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/scripts.custom.js') }}"></script>
    </body>
</html>
