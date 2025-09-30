@extends('layouts.app', ['page_title' => (!empty($current_user) ? 'Accueil' : 'Bienvenue sur Addrressimmo')])

@section('app-content')

        <div class="hero">
            <div class="hero-slide">
                <div class="img overlay" style="background-image: url('images/hero_bg_3.jpg')"></div>
                <div class="img overlay" style="background-image: url('images/hero_bg_2.jpg')"></div>
                <div class="img overlay" style="background-image: url('images/hero_bg_1.jpg')"></div>
            </div>

            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-8 text-center">
                        <h1 class="heading" data-aos="fade-up">
                            Commencez ici
                        </h1>
                        <div id="main-search" class="card card-body">
                            <!-- Tabs navs -->
                            <ul class="nav nav-tabs nav-fill mb-3 d-flex flex-nowrap" id="ex1" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a data-mdb-tab-init class="nav-link bg-transparent active" id="ex2-tab-1" href="#ex2-tabs-1" role="tab" aria-controls="ex2-tabs-1" aria-selected="true" >
                                        <i class="bi bi-handbag me-sm-2 fs-6 align-middle"></i><span class="d-sm-inline d-block mt-2">Acheter</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a data-mdb-tab-init class="nav-link bg-transparent" id="ex2-tab-2" href="#ex2-tabs-2" role="tab" aria-controls="ex2-tabs-2" aria-selected="false" >
                                        <i class="bi bi-clock me-sm-2 fs-6 align-middle"></i><span class="d-sm-inline d-block mt-2">Louer</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a data-mdb-tab-init class="nav-link bg-transparent" id="ex2-tab-3" href="#ex2-tabs-3" role="tab" aria-controls="ex2-tabs-3" aria-selected="false">
                                        <i class="bi bi-cash-coin me-sm-2 fs-6 align-middle"></i><span class="d-sm-inline d-block mt-2">Vendre</span>
                                    </a>
                                </li>
                            </ul>
                            <!-- Tabs navs -->

                            <!-- Tabs content -->
                            <div class="tab-content" id="ex2-content">
                                <div class="tab-pane fade show active" id="ex2-tabs-1" role="tabpanel" aria-labelledby="ex2-tab-1">
                                    <form action="#" class="form-search d-flex align-items-stretch my-2 border border-success rounded-pill" data-aos="fade-up" data-aos-delay="200">
                                        <div class="input-group">
                                            <div class="input-group-text border-0 me-0 pe-1 py-4">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <input type="text" class="form-control px-3 py-4" placeholder="Saisir adresse entière, commune ou quartier" />
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="ex2-tabs-2" role="tabpanel" aria-labelledby="ex2-tab-2">
                                    <form action="#" class="form-search d-flex align-items-stretch my-2 border border-success rounded-pill" data-aos="fade-up" data-aos-delay="200">
                                        <div class="input-group">
                                            <div class="input-group-text border-0 me-0 pe-1 py-4">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <input type="text" class="form-control px-3 py-4" placeholder="Saisir adresse entière, commune ou quartier" />
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="ex2-tabs-3" role="tabpanel" aria-labelledby="ex2-tab-3">
                                    <form action="#" class="form-search d-sm-flex justify-content-center align-items-center py-sm-2 py-1" data-aos="fade-up" data-aos-delay="200" style="margin-bottom: 0.65em">
@forelse ($product_categories as $index => $category)
                                        <div class="form-check form-check-inline mt-sm-0 mt-2">
                                            <input class="form-check-input" type="radio" name="category_id" id="category_{{ $category['id'] }}" value="{{ $category['category_name'] }}" {{ $index == 0 ? 'checked' : '' }} />
                                            <label class="form-check-label" for="category_{{ $category['id'] }}">{{ $category['category_name'] }}</label>
                                        </div>
@empty
@endforelse

                                        <button type="button" class="btn adrm-btn-green mt-sm-0 mt-4 rounded-pill">Commencer</button>
                                    </form>
                                </div>
                            </div>
                            <!-- Tabs content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="container">
                <div class="row mb-5 align-items-center">
                    <div class="col-lg-6">
                        <h2 class="font-weight-bold text-primary heading">
                            Propriétés récentes
                        </h2>
                    </div>
                    <div class="col-lg-6 text-lg-end">
                        <p>
                            <a href="{{ route('product.entity', ['entity' => 'sell']) }}" target="_blank" class="btn adrm-btn-green text-white py-3 px-4">
                                Voir tout<i class="bi bi-chevron-double-right ms-2"></i>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
    @if (count($recent_properties) > 0)
                        <div class="property-slider-wrap">
                            <div class="property-slider">
        @foreach ($recent_properties as $product)
                                <div class="property-item mb-30">
                                    <a href="{{ route('product.datas', ['id' => $product['id']]) }}" class="img d-inline-block" style="max-height: 350px; overflow: hidden;">
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

                                            <a href="{{ route('product.datas', ['id' => $product['id']]) }}"
                                                class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->
        @endforeach
                            </div>

                            <div id="property-nav" class="controls" tabindex="0" aria-label="Carousel Navigation">
                                <span class="prev" data-controls="prev" aria-controls="property"
                                    tabindex="-1"><i class="bi bi-chevron-double-left"></i></span>
                                <span class="next" data-controls="next" aria-controls="property"
                                    tabindex="-1"><i class="bi bi-chevron-double-right"></i></span>
                            </div>
                        </div>
    @else
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <p class="mb-0"><i class="bi bi-house fs-1"></i></p>
                            <p class="mb-0">La liste est vide</p>
                        </div>
    @endif
                    </div>
                </div>
            </div>
        </div>

        <section class="features-1">
            <div class="container">
                <div class="row">
    @forelse ($product_categories as $category)
                    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ 200 + (($loop->index + 1) * 100) }}">
                        <div class="box-feature">
                            <span class="{{ $category['icon'] }}"></span>
                            <h3 class="mb-3">{{ $category['category_name'] }}</h3>
                            <p>
                                {{ $category['category_description'] }}
                            </p>
                            <p><a href="{{ route('product.entity', ['entity' => 'sell', 'category_id' => $category['id']]) }}" class="learn-more">Voir plus</a></p>
                        </div>
                    </div>
    @empty
    @endforelse
                </div>
            </div>
        </section>

        <div class="section sec-testimonials">
            <div class="container">
                <div class="row mb-5 align-items-center">
                    <div class="col-md-6">
                        <h2 class="font-weight-bold heading text-primary mb-4 mb-md-0">
                            Témoignage des clients
                        </h2>
                    </div>
    @if ($customer_feedbacks_req->total() > 0)
                    <div class="col-md-6 text-md-end">
                        <div id="testimonial-nav">
                            <span class="prev" data-controls="prev"><i class="bi bi-chevron-double-left"></i></span>

                            <span class="next" data-controls="next"><i class="bi bi-chevron-double-right"></i></span>
                        </div>
                    </div>
    @endif
                </div>

                <div class="row">
                    <div class="col-lg-4"></div>
                </div>

    @if ($customer_feedbacks_req->total() > 0)
                <div class="testimonial-slider-wrap">
                    <div class="testimonial-slider">
        @foreach ($customer_feedbacks as $feedback)
                        <div class="item">
                            <div class="testimonial">
                                <img src="images/person_1-min.jpg" alt="Image" class="img-fluid rounded-circle w-25 mb-4" />
                                <h3 class="h5 text-primary mb-4">{{ $feedback['user']['firstname'] . ' ' . $feedback['user']['lastname'] }}</h3>
                                <blockquote>
                                    <p>
                                        &ldquo;{{ $feedback['comment'] }}&rdquo;
                                    </p>
                                </blockquote>
                                <p class="text-black-50">{{ $feedback['user']['selected_role'] }}</p>
                            </div>
                        </div>
        @endforeach
                    </div>
                </div>
    @else
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <p class="mb-0"><i class="bi bi-chat-square-text fs-1"></i></p>
                    <p class="mb-0">La liste est vide</p>
                </div>
    @endif
            </div>
        </div>

        <div class="section section-4 bg-light">
            <div class="container">
                <div class="row section-counter text-center">
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="counter-wrap mb-5 mb-lg-0">
                            <span class="number"><span class="countup text-primary">{{ $sell_products_req->total() }}</span></span>
                            <span class="caption text-black-50"># {{ $sell_products_req->total() > 1 ? 'offres' : 'offre' }} à vendre</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                        <div class="counter-wrap mb-5 mb-lg-0">
                            <span class="number"><span class="countup text-primary">{{ $rent_products_req->total() }}</span></span>
                            <span class="caption text-black-50"># {{ $rent_products_req->total() > 1 ? 'propriétés' : 'propriété' }} en location</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
                        <div class="counter-wrap mb-5 mb-lg-0">
                            <span class="number"><span class="countup text-primary">{{ $buy_products_req->total() }}</span></span>
                            <span class="caption text-black-50"># {{ $buy_products_req->total() > 1 ? 'propriétés' : 'propriété' }} vendues</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
                        <div class="counter-wrap mb-5 mb-lg-0">
                            <span class="number"><span class="countup text-primary">{{ $agents_req->total() }}</span></span>
                            <span class="caption text-black-50"># {{ $agents_req->total() > 1 ? 'agents' : 'agent' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="row justify-content-center footer-cta" data-aos="fade-up">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="mb-4">Faites partie de nos agents immobiliers</h2>
                    <p>
                        <a href="#" target="_blank" class="btn adrm-btn-green text-white py-3 px-4">Postuler pour agent immobilier</a>
                    </p>
                </div>
                <!-- /.col-lg-7 -->
            </div>
            <!-- /.row -->
        </div>

        <div class="section section-5 bg-light">
            <div class="container">
                <div class="row justify-content-center text-center{{ $agents_req->total() > 0 ? ' mb-5' : '' }}">
                    <div class="col-lg-6 mb-5">
                        <h2 class="font-weight-bold heading text-primary mb-4">
                            Nos agents
                        </h2>
                        <p class="text-black-50">Ils vous accompagnent dans toutes vos démarches d’acquisition de maison, de parcelle ou d'appartement.</p>
                    </div>
                </div>
    @if ($agents_req->total() > 0)
                <div class="row">
        @foreach ($agents as $user)
                    <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
                        <div class="h-100 person">
                            <img src="images/person_1-min.jpg" alt="Image" class="img-fluid" />

                            <div class="person-contents">
                                <h2 class="mb-0"><a href="#">{{ $user['firstname'] . ' ' . $user['lastname'] }}</a></h2>
                                {{-- <span class="meta d-block mb-3">Real Estate Agent</span> --}}
                                <p>{{ $user['about_me'] }}</p>

                                {{-- <ul class="social list-unstyled list-inline dark-hover">
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-instagram"></span></a>
                                    </li>
                                </ul> --}}
                            </div>
                        </div>
                    </div>
        @endforeach
                </div>
    @else
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <p class="mb-0"><i class="bi bi-person fs-1"></i></p>
                    <p class="mb-0">La liste est vide</p>
                </div>
    @endif
            </div>
        </div>

@endsection
