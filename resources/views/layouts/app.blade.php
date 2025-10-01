<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="author" content="Untree.co" />
        <meta name="description" content="" />
        <meta name="keywords" content="bootstrap, bootstrap5" />
        <meta name="adrm-url" content="{{ getWebURL() }}">
        <meta name="adrm-api-url" content="{{ getApiURL() }}">
        <meta name="adrm-visitor" content="{{ !empty($current_user) ? $current_user['id'] : null }}">
        <meta name="adrm-ref" content="{{ !empty($current_user) ? $current_user['api_token'] : null }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <link rel="stylesheet" href="{{ asset('assets/addons/jquery/jquery-ui/jquery-ui.min.css') }}" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{{ asset('assets/addons/mdb/css/mdb.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

        <style>
            textarea { resize: none; }
            .alert { z-index: 9999; }
            #showPassword i, #showConfirmPassword i, #showNewPassword i, #showConfirmNewPassword i { font-size: 1.6rem; }
            #offerForm label { font-size: 14px; }
            #offerForm .form-control { height: 2.3rem; }
            .menu-bg-wrap { background-color: #167c02; }
            .site-footer a { text-decoration: none!important; }
            #main-search .nav-link.active { font-weight: bold; color: black !important; border-bottom: 2px solid green !important; }
            .property-item .property-content .price { color: #167c02; }
            .property-item .property-content .price span:after { background-color: #167c02; }
            /* Image preview to upload */
            #image-preview-container { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
            .preview-thumbnail { position: relative; display: inline-block; width: 100px; height: 100px; }
            .preview-thumbnail img { width: 100%; height: 100%; object-fit: cover; border-radius: 5px; }
            .preview-thumbnail .remove-image { position: absolute; top: 0; right: 0; background-color: rgba(255, 0, 0, 0.7); color: white; border-radius: 50%; cursor: pointer; font-size: 14px; padding: 0 5.5px; }
            .preview-thumbnail .remove-image:hover { background-color: rgba(255, 0, 0, 0.3); }
            .agent-box .text h3 { font-size: 23px; }
            @media screen and (min-width: 780px) {
                #contactInfo { max-width: 90%; }
            }
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
        <!-- ### Crop user image ### -->
        <div class="modal fade" id="cropModalUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header py-0 border-bottom-0">
                        <button type="button" class="btn-close mt-1" data-bs-dismiss="modal" aria-label="@lang('miscellaneous.close')"></button>
                    </div>
                    <div class="modal-body pb-3">
                        <h5 class="text-center text-muted">@lang('miscellaneous.crop_before_save')</h5>

                        <div class="container">
                            <div class="row">
                                <div class="col-12 mb-sm-0 mb-4">
                                    <div class="bg-image">
                                        <img src="" id="retrieved_image" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-pill text-dark" data-bs-dismiss="modal">@lang('miscellaneous.cancel')</button>
                        <button type="button" id="crop_avatar" class="btn btn-primary px-4 rounded-pill" data-bs-dismiss="modal">{{ __('miscellaneous.register') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ### Crop other user image ### -->
        <div class="modal fade" id="cropModal_profile" tabindex="-1" aria-hidden="true" data-bs-backdrop="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header py-0 border-bottom-0">
                        <button type="button" class="btn-close mt-1" data-bs-dismiss="modal" aria-label="@lang('miscellaneous.close')"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-center text-muted">@lang('miscellaneous.crop_before_save')</h5>

                        <div class="container">
                            <div class="row">
                                <div class="col-12 mb-sm-0 mb-4">
                                    <div class="bg-image">
                                        <img src="" id="retrieved_image_profile" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-pill text-dark" data-bs-dismiss="modal">@lang('miscellaneous.cancel')</button>
                        <button type="button" id="crop_profile" class="btn btn-primary px-4 rounded-pill" data-bs-dismiss="modal">@lang('miscellaneous.register')</button>
                    </div>
                </div>
            </div>
        </div>

@if (Route::is('account.entity'))
        <!-- ### Publish an offer ### -->
        <div class="modal fade" id="addOfferModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header adrm-bg-green text-center">
                        <button type="button" class="btn-close btn-sm position-absolute rounded-circle" style="top: 1rem; right: 1rem; background-color: rgba(300, 300, 300, 0.5);" data-bs-dismiss="modal" aria-label="@lang('miscellaneous.close')"></button>

                        <h2 class="modal-title w-100 text-white" style="font-weight: 500;">Publier une offre</h2>
                    </div>

                    <div class="modal-body">
                        <form id="offerForm" action="{{ route('product.home') }}" method="POST" enctype="multipart/form-data">
    @csrf
                            <div class="row g-3">
                                <!-- Is service -->
                                <div class="col-12 text-center">
                                    <label class="form-label">L'offre est-elle un service ?</label>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_service" id="service_yes" value="1">
                                            <label class="form-check-label" for="service_yes">Oui</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_service" id="service_no" value="0">
                                            <label class="form-check-label" for="service_no">Non</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product name -->
                                <div class="col-sm-6 col-12">
                                    <label for="product_name" class="form-label mb-0">Nom de l'offre</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                                </div>

                                <!-- Description -->
                                <div class="col-sm-6 col-12">
                                    <label for="product_description" class="form-label mb-0">Description</label>
                                    <textarea class="form-control" id="product_description" name="product_description" rows="2"></textarea>
                                </div>

                                <!-- Action -->
                                <div class="col-sm-6 col-12">
                                    <label for="action" class="form-label mb-0">Action</label>
                                    <select class="form-select" id="action" name="action">
                                        <option value="sell">Vente</option>
                                        <option value="rent">Location</option>
                                        <option value="build">Construction</option>
                                        <option value="moving">Déménagement</option>
                                    </select>
                                </div>

                                <!-- Type -->
                                <div class="col-sm-6 col-12">
                                    <label for="type" class="form-label mb-0">Type</label>
                                    <select class="form-select" id="type" name="type">
                                        <option class="small" disabled selected>Choisir un type</option>
                                        <option value="equipped_house">Maison équipée</option>
                                        <option value="empty_house">Maison vide</option>
                                        <option value="unfinished_house">Maison inachevée</option>
                                        <option value="equipped_apartment">Appartement équipé</option>
                                        <option value="empty_apartment">Appartement vide</option>
                                        <option value="empty_plot">Parcelle vide</option>
                                        <option value="house_plot">Concession maisons</option>
                                    </select>
                                </div>

                                <!-- Category -->
                                <div class="col-sm-6 col-12">
                                    <label for="category_id" class="form-label mb-0">@lang('miscellaneous.admin.product.data.category')</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option class="small" disabled selected>Choisir une catégorie</option>
    @forelse ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
    @empty
                                        <option disabled>@lang('miscellaneous.empty_list')</option>
    @endforelse
                                    </select>
                                </div>

                                <!-- Country -->
                                <div class="col-sm-6 col-12">
                                    <label for="country" class="form-label mb-0">Pays</label>
                                    <select class="form-select" id="country" name="country">
                                        <option class="small" disabled>Choisir pays</option>
    @forelse ($countries as $country)
                                        <option>{{ $country['name'] }}</option>
    @empty
    @endforelse
                                    </select>
                                </div>

                                <!-- City -->
                                <div class="col-sm-6 col-12">
                                    <label for="city" class="form-label mb-0">Ville</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>

                                <!-- Address -->
                                <div class="col-sm-6 col-12">
                                    <label for="address" class="form-label mb-0">Adresse</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                                </div>

                                <!-- Municipality -->
                                <div class="col-sm-6 col-12">
                                    <label for="municipality" class="form-label mb-0">Commune / Zone</label>
                                    <input type="text" class="form-control" id="municipality" name="municipality">
                                </div>

                                <!-- Neighborhood -->
                                <div class="col-sm-6 col-12">
                                    <label for="neighborhood" class="form-label mb-0">Quartier</label>
                                    <input type="text" class="form-control" id="neighborhood" name="neighborhood">
                                </div>

                                <!-- Street -->
                                <div class="col-sm-6 col-12">
                                    <label for="street" class="form-label mb-0">Avenue</label>
                                    <input type="text" class="form-control" id="street" name="street">
                                </div>

                                <!-- Quantity -->
                                <div class="col-sm-6 col-12">
                                    <label for="quantity" class="form-label mb-0">Quantité</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" step="1">
                                </div>

                                <!-- Price -->
                                <div class="col-sm-6 col-6">
                                    <label for="price" class="form-label mb-0">Prix</label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                                </div>

                                <!-- Currency -->
                                <div class="col-sm-6 col-6">
                                    <label for="currency" class="form-label mb-0">Devise</label>
                                    <select class="form-select" id="currency" name="currency">
                                        <option class="small" disabled>Choisir devise</option>
                                        <option>USD</option>
                                        <option>CDF</option>
                                    </select>
                                </div>

                                <!-- Upload images -->
                                <div class="col-sm-12">
                                    <label for="files_urls">@lang('miscellaneous.upload.upload_images')</label>
                                    <input type="file" id="files_urls" name="files_urls[]" class="form-control" multiple>
                                </div>

                                <div class="col-sm-12">
                                    <div id="image-preview-container" class="mt-2"></div> <!-- Conteneur pour les vignettes -->
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-start;">
                                <button type="submit" class="btn adrm-btn-red rounded-pill" style="width: 250px">
                                    <span style="color: #fff;">@lang('miscellaneous.register')</span>
                                </button>
                                <img id="loading-icon" class="ms-2 d-none" src="{{ asset('assets/img/ajax-loading.gif') }}" alt="" width="40" height="40">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endif

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
                                    <li><a href="{{ route('product.entity', ['entity' => 'sell']) }}"><i class="bi bi-handbag me-2"></i>Achat</a></li>
                                    <li><a href="{{ route('product.entity', ['entity' => 'rent']) }}"><i class="bi bi-clock me-2"></i>Location</a></li>
                                    <li><a href="{{ route('account.entity', ['entity' => 'offers', 'action' => 'sell']) }}"><i class="bi bi-cash-coin me-2"></i>Vente</a></li>
                                    <li><a href="{{ route('product.entity', ['entity' => 'moving']) }}"><i class="bi bi-luggage me-2"></i>Déménagement</a></li>
                                    <li><a href="{{ route('product.entity', ['entity' => 'build']) }}"><i class="bi bi-bricks me-2"></i>Construction</a></li>
                                    <li><a href="{{ route('product.entity', ['entity' => 'design']) }}"><i class="bi bi-droplet-half me-2"></i>Décoration intérieure</a></li>
                                </ul>
                            </li>
                            <li class="{{ Route::is('about') ? 'active' : '' }}"><a href="{{ route('about') }}">A propos</a></li>
                            <li class="{{ Route::is('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">Nous contacter</a></li>
                            <li>
                                <a class="d-inline-block position-relative" href="{{ !empty($current_user) ? route('account.entity', ['entity' => 'cart']) : route('cart') }}">
                                    <i class="bi bi-cart3 fs-5 align-middle"></i>
@if (!empty($current_user))
    @if (count($user_orders) > 0)
                                    <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="top: 10px; right: -5px;">
                                        {{ count($user_orders) }}
                                        <span class="visually-hidden">{{ trans_choice('miscellaneous.items', count($user_orders), ['count' => count($user_orders)]) }}</span>
                                    </span>
    @endif
@else
    @session('cart')
        @php
            $cartItems = session()->get('cart', []);
        @endphp

        @if (count($cartItems) > 0)
                                    <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="top: 10px; right: -5px;">
                                        {{ count($cartItems) }}
                                        <span class="visually-hidden">{{ trans_choice('miscellaneous.items', count($cartItems), ['count' => count($cartItems)]) }}</span>
                                    </span>
        @endif
    @endsession
@endif
                                </a>
                            </li>
@if (!empty($current_user))
                            <li class="has-children">
                                <a role="button">
                                    <img src="{{ !empty($current_user['avatar_url']) ? $current_user['avatar_url'] : asset('assets/img/user.png') }}" width="40" height="40" alt="{{ $current_user['firstname'] . ' ' . $current_user['lastname'] }}" class="user-image rounded-circle">
                                </a>
                                <ul class="dropdown">
                                    <li><a href="{{ route('account.home') }}"><i class="bi bi-person me-2"></i>Mon compte</a></li>
                                    <li><a href="{{ route('account.entity', ['entity' => 'cart']) }}"><i class="bi bi-cart3 me-2"></i>Mon panier</a></li>
                                    <li><a href="{{ route('account.entity', ['entity' => 'offers']) }}"><i class="bi bi-house-door me-2"></i>Mes offres</a></li>
                                    {{-- <li><a href="{{ route('account.entity', ['entity' => 'customers']) }}"><i class="bi bi-people me-2"></i>Mes clients</a></li> --}}
                                    <hr>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
@csrf
                                            <button type="submit" class="btn-sm bg-transparent py-0 border-0 text-start text-dark" style="padding-left: 1.2rem;"><i class="bi bi-power me-2"></i>Quitter la session</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
@else
                            <li>
                                <a href="{{ route('login') }}">S'identifier</a>
                            </li>
@endif
                        </ul>

                        <a role="button" class="burger light me-auto float-end mt-1 site-menu-toggle js-menu-toggle d-inline-block d-lg-none" data-toggle="collapse" data-target="#main-navbar">
                            <span></span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div id="ajax-alert-container"></div>
@if (\Session::has('success_message'))
        <div class="position-relative">
            <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                <div class="col-lg-4 col-sm-6 mx-auto">
                    <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
                        <i class="bi bi-info-circle me-2 fs-4" style="vertical-align: -3px;"></i> {!! \Session::get('success_message') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                </div>
            </div>
        </div>
@endif
@if (\Session::has('error_message'))
        <div class="position-relative">
            <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                <div class="col-lg-4 col-sm-6 mx-auto">
                    <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
                        <i class="bi bi-exclamation-triangle me-2 fs-4" style="vertical-align: -3px;"></i> {!! \Session::get('error_message') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                </div>
            </div>
        </div>
@endif

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
        {{-- <script type="text/javascript" src="{{ asset('assets/addons/jquery/datetimepicker/js/jquery.datetimepicker.full.min.js') }}"></script> --}}
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{ asset('assets/addons/mdb/js/mdb.umd.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/tiny-slider.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/aos.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/navbar.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/counter.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/property/custom.js') }}"></script>
        <!-- Pretype="text/javascript" loader -->
        <script type="text/javascript" src="{{ asset('assets/addons/autosize/js/autosize.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/addons/cropper/js/cropper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/my_alg.js') }}"></script>
        <script type="text/javascript">
            /**
             * Perform action on element
             */
            function performAction(action, entity, entity_id) {
                if (action === 'delete') {
                    var entityId = parseInt(entity_id.split('-')[1]);

                    Swal.fire({
                        title: "<?= __('miscellaneous.alert.attention.delete') ?>",
                        text: "<?= __('miscellaneous.alert.confirm.delete') ?>",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#04471a",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "<?= __('miscellaneous.alert.yes.delete') ?>",
                        cancelButtonText: "<?= __('miscellaneous.cancel') ?>"

                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                headers: headers,
                                type: "DELETE",
                                url: `${currentHost}/delete/${entity}/${entityId}`,
                                contentType: false,
                                processData: false,
                                data: JSON.stringify({ "entity" : entity, "id" : entityId }),
                                success: function (result) {
                                    if (!result.success) {
                                        Swal.fire({
                                            title: "<?= __('miscellaneous.alert.oups') ?>",
                                            text: result.message,
                                            icon: "error"
                                        });

                                    } else {
                                        Swal.fire({
                                            title: "<?= __('miscellaneous.alert.perfect') ?>",
                                            text: result.message,
                                            icon: "success"
                                        });
                                        location.reload();
                                    }
                                },
                                error: function (xhr, error, status_description) {
                                    console.log(xhr.responseJSON);
                                    console.log(xhr.status);
                                    console.log(error);
                                    console.log(status_description);
                                }
                            });

                        } else {
                            Swal.fire({
                                title: "<?= __('miscellaneous.cancel') ?>",
                                text: "<?= __('miscellaneous.alert.canceled.delete') ?>",
                                icon: "error"
                            });
                        }
                    });
                }
            }

            /**
             * Update order quantity
             */
            function updateProductQuantity(action, orderId, quantity = null) {
                let url = `${currentHost}/products/update-order-quantity/${orderId}`;
                let data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    action: action
                };

                // If the action is "update", we add the specific quantity
                if (action === 'update') {
                    data.quantity = quantity;

                } else {
                    // For "increment" or "decrement", the quantity is always 1
                    data.quantity = 1;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Update the UI if everything is fine
                        if (response.inCart) {
                            // Update the quantity in the input
                            $(`#order-quantity-${orderId}`).val(response.newQuantity);
                            // Display success message
                            $('#ajax-alert-container').html(`<div class="position-relative">
                                                                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                                                                    <div class="col-lg-4 col-sm-6 mx-auto">
                                                                        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
                                                                            <i class="bi bi-info-circle me-2 fs-4" style="vertical-align: -3px;"></i> ${response.message || 'Commande mise à jour !'}
                                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>`);
                        }

                        if (!response.inStock) {
                            // Update the quantity in the input
                            $(`#order-quantity-${orderId}`).val(response.newQuantity);
                            // Display error message if the stock is insufficient
                            $('#ajax-alert-container').html(`<div class="position-relative">
                                                                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                                                                    <div class="col-lg-4 col-sm-6 mx-auto">
                                                                        <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
                                                                            <i class="bi bi-exclamation-triangle me-2 fs-4" style="vertical-align: -3px;"></i> Stock insuffisant
                                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>`);
                        }

                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Update the quantity in the input
                        $(`#order-quantity-${orderId}`).val(xhr.responseJSON.newQuantity);
                        // Display error alert
                        $('#ajax-alert-container').html(`<div class="position-relative">
                                                            <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                                                                <div class="col-lg-4 col-sm-6 mx-auto">
                                                                    <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
                                                                        <i class="bi bi-exclamation-triangle me-2 fs-4" style="vertical-align: -3px;"></i> ${xhr.responseJSON.message}
                                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>`);
                        location.reload();
                    }
                });
            }

            $(function () {
                /**
                 * Image preview to upload
                 */
                $('#files_urls').on('change', function (e) {
                    // Récupérer les fichiers
                    const files = e.target.files;
                    const imagePreviewContainer = $('#image-preview-container');

                    // Effacer les vignettes existantes
                    imagePreviewContainer.empty();

                    // Créer une vignette pour chaque fichier sélectionné
                    Array.from(files).forEach(file => {
                        const reader = new FileReader();

                        reader.onload = function (e) {

                            const imageUrl = e.target.result;
                            const fileName = file.name;

                            // Créer l'élément de la vignette avec la croix
                            const imageThumbnail = $(`<div class="preview-thumbnail">
                                                        <img src="${imageUrl}" alt="${fileName}" />
                                                        <span class="remove-image">&times;</span>
                                                    </div>`);

                            // Ajouter la vignette au conteneur
                            imagePreviewContainer.append(imageThumbnail);

                            // Gérer la suppression de l'image
                            imageThumbnail.find('.remove-image').on('click', function () {

                                // Supprimer le fichier de l'input
                                const fileList = Array.from($('#files_urls')[0].files);
                                const index = fileList.findIndex(f => f.name === fileName);

                                if (index !== -1) {
                                    fileList.splice(index, 1);
                                }

                                // Créer un objet DataTransfer pour mettre à jour la liste des fichiers
                                const dataTransfer = new DataTransfer();

                                fileList.forEach(f => dataTransfer.items.add(f));

                                // Mettre à jour les fichiers de l'input
                                $('#files_urls')[0].files = dataTransfer.files;

                                // Supprimer la vignette de l'UI
                                imageThumbnail.remove();
                            });
                        };

                        reader.readAsDataURL(file);
                    });
                });

                $('#addOfferModal').on('shown.bs.modal', function () {
                    // Code to execute when the modal is fully shown
                    console.log('The modal is now fully visible!');

                    // For example, focusing an input field inside the modal:
                    $('#product_name').focus(); // Replace 'myInput' with the ID of your input
                });

                /**
                 * Add to cart button
                 */
                $('.item-add-btn').on('click', function () {
                    const productId = $(this).data('id');
                    const productContainer = $(`#product-${productId}`); // Le conteneur du produit à mettre à jour

                    // Cacher le texte et afficher l'icône de chargement pour ce produit spécifique
                    $(`#addToCart-${productId} .btn`).addClass('disabled');
                    $(`#ajax-loading-${productId}`).removeClass('d-none');

                    $.ajax({
                        url: `${currentHost}/products/add-to-cart/${productId}`,
                        method: 'POST',
                        data: {
                            quantity: 500,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success(response) {
                            $('#ajax-alert-container').html(`<div class="position-relative">
                                                                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                                                                    <div class="col-lg-4 col-sm-6 mx-auto">
                                                                        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
                                                                            <i class="bi bi-info-circle me-2 fs-4" style="vertical-align: -3px;"></i> ${response.message || 'Offre ajoutée au panier !'}
                                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>`);
                            location.reload();
                        },
                        error(xhr) {
                            // Afficher une alerte d'erreur
                            $('#ajax-alert-container').html(`<div class="position-relative">
                                                                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                                                                    <div class="col-lg-4 col-sm-6 mx-auto">
                                                                        <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
                                                                            <i class="bi bi-exclamation-triangle me-2 fs-4" style="vertical-align: -3px;"></i> ${xhr.responseJSON.message || 'L’ajout a échoué'}
                                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>`);
                            $(`#addToCart-${productId} .btn`).removeClass('disabled');
                            $(`#ajax-loading-${productId}`).addClass('d-none');
                        }
                    });
                });
            });
        </script>
@if (Route::is('account.entity'))
        <script type="text/javascript">
            $(function () {
                /**
                 * Modal event
                 */
                var offerModal = new bootstrap.Modal(document.getElementById('addOfferModal'), { keyboard: false });

                /**
                 * Ajax to send offer
                 */
                $('#offerForm').on('submit', function (e) {
                    e.preventDefault();

                    // Afficher l'animation de chargement
                    $('#loading-icon').removeClass('d-none');

                    // Effacer les alertes précédentes
                    $('#ajax-alert-container').empty();

                    var formData = new FormData(this);

                    // Ajouter les images à FormData (dans le cas où il y en a)
                    var images = $('#files_urls')[0].files;

                    for (var i = 0; i < images.length; i++) {
                        formData.append('files_urls[' + i + ']', images[i]);
                    }

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // Cacher l'animation de chargement
                            $('#loading-icon').addClass('d-none');

                            // Afficher une alerte de succès
                            $('#ajax-alert-container').html(`<div class="position-relative">
                                                                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                                                                    <div class="col-lg-4 col-sm-6 mx-auto">
                                                                        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
                                                                            <i class="bi bi-info-circle me-2 fs-4" style="vertical-align: -3px;"></i> ${response.message || 'Offre ajoutée avec succès !'}
                                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>`);

                            // Optionnellement, fermer le modal après un succès
                            offerModal.hide();

                            // Réinitialiser tous les champs du formulaire
                            $('#offerForm')[0].reset();

                            // Réinitialiser le champ de fichiers (images)
                            $('#files_urls').val(null);

                            location.reload();
                        },
                        error: function (error) {
                            // Cacher l'animation de chargement
                            $('#loading-icon').addClass('d-none');

                            // Afficher une alerte d'erreur
                            $('#ajax-alert-container').html(`<div class="position-relative">
                                                                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                                                                    <div class="col-lg-4 col-sm-6 mx-auto">
                                                                        <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
                                                                            <i class="bi bi-exclamation-triangle me-2 fs-4" style="vertical-align: -3px;"></i> {{ __('notifications.error_while_processing') }}
                                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>`);
                        }
                    });
                });
            });
        </script>
@endif
    </body>
</html>
