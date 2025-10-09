
        <div class="hero page-inner overlay" style="background-image: url('../images/hero_bg_1.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">{{ $entity_title }}</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">{{ $entity_title }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="section">
            <div class="container">
                <div class="row mb-5 align-items-center">
                    <div class="col-lg-6 text-center mx-auto">
                        <h2 class="font-weight-bold text-primary heading">
                            Proche de vous
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="property-slider-wrap">
                            <div class="property-slider">
                                <div class="property-item">
                                </div>
                                <!-- .item -->
                            </div>

                            <div id="property-nav" class="controls" tabindex="0" aria-label="Carousel Navigation">
                                <span class="prev" data-controls="prev" aria-controls="property" tabindex="-1"><i
                                        class="bi bi-chevron-double-left"></i></span>
                                <span class="next" data-controls="next" aria-controls="property" tabindex="-1"><i
                                        class="bi bi-chevron-double-right"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="section section-Propriétés">
            <div class="container">
                <div class="row">
@forelse ($build_products as $product)
    @php
        $cart = session()->get('cart', []);
        $isInCart = isset($cart[$product['id']]);
    @endphp
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="property-item mb-30">
                            <a href="{{ route('product.datas', ['id' => $product['id']]) }}" class="img d-inline-block" style="height: 350px; overflow: hidden;">
                                <img src="{{ count($product['photos']) > 0 ? $product['photos'][0]['file_url'] : asset('assets/img/undefined.png') }}" alt="Image" class="img-fluid" />
                            </a>

                            <div class="property-content">
                                <div class="price mb-2"><span>{{ $product['converted_price'] . ' ' . $product['readable_currency'] }}</span></div>
                                <div>
                                    <span class="d-block mb-2 text-black-50">{{ $product['product_name'] }}</span>
                                    <span class="city d-block mb-3">{{ $product['city'] . ', ' . $product['country'] }}</span>

                                    <div class="specs d-flex mb-4">
                                        <span class="d-block d-flex align-items-center me-3">
                                            <span class="{{ $product['category']['icon'] }} me-2"></span>
                                            <span class="caption">{{ $product['category']['category_name'] }}</span>
                                        </span>
    @if (!empty($product['type']))
                                        <span class="d-block d-flex align-items-center">
                                            <span class="{{ $product['readable_icon'] }} me-2"></span>
                                            <span class="caption">{{ $product['readable_type'] }}</span>
                                        </span>
    @endif
                                    </div>

                                    <div id="addToCart-{{ $product['id'] }}" class="d-flex flex-row align-items-center mb-0">
                                        <a href="{{ route('product.datas', ['id' => $product['id']]) }}" class="btn adrm-btn-red me-2 py-2 px-3 rounded-pill">
                                            Voir détails
                                        </a>
    @if (!empty($current_user))
        @if ($current_user->hasProductInUnpaidCart($product['id']))
                                        <button class="btn btn-sm btn-light border border-success rounded-pill disabled">Déjà dans le panier</button>
        @else
            @if ($product['quantity'] > 0)
                                        <button class="btn btn-sm adrm-btn-green me-2 item-add-btn rounded-pill" data-id="{{ $product['id'] }}"><i class="bi bi-cart3 me-2"></i>Ajouter au panier</button>
                                        <img id="ajax-loading-{{ $product['id'] }}" class="d-none" src="{{ asset('assets/img/ajax-loading.gif') }}" alt="@lang('miscellaneous.loading')" width="30" height="30">
            @else
                                        <button class="btn btn-sm btn-dark"><i class="bi bi-exclamation-circle"></i>Stock insuffisant</button>
            @endif
        @endif
    @else
        @if ($isInCart)  <!-- Vérifie si le produit est dans la session -->
                                        <button class="btn btn-sm btn-light border border-success rounded-pill disabled">Déjà dans le panier</button>
        @else
                                        <button class="btn btn-sm adrm-btn-green me-2 item-add-btn rounded-pill" data-id="{{ $product['id'] }}"><i class="bi bi-cart3 me-2"></i>Ajouter au panier</button>
                                        <img id="ajax-loading-{{ $product['id'] }}" class="d-none" src="{{ asset('assets/img/ajax-loading.gif') }}" alt="@lang('miscellaneous.loading')" width="30" height="30">
        @endif
    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .item -->
                    </div>
@empty
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <p class="mb-0"><i class="bi bi-bricks fs-1"></i></p>
                        <p class="mb-0">La liste est vide</p>
                    </div>
@endforelse
                </div>

@if ($build_products_req->total() > 0)
                <div class="row align-items-center py-5">
                    <div class="col-lg-3">Pagination ({{ $build_products_req->currentPage() }} sur {{ $build_products_req->lastPage() }})</div>
                    <div class="col-lg-6 text-center">
                        {{ $build_products_req->links() }}
                    </div>
                </div>
@endif
            </div>
        </div>
