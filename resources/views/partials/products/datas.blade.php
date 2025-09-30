
        <div class="hero page-inner overlay" style="background-image: url('../../images/hero_bg_3.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">
                            {{ $entity_title }}
                        </h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">
                                    {{ $entity_title }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-7">
                        <div class="img-property-slide-wrap">
                            <div class="img-property-slide">
@forelse ($selected_product['photos'] as $photo)
                                <img src="{{ $photo['file_url'] }}" alt="Image" class="img-fluid" style="max-height: 500px!important; object-fit: cover;" />
@empty
                                <img src="{{ asset('assets/img/undefined.png') }}" alt="Image" class="img-fluid" />
@endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <h2 class="heading adrm-text-green mb-4">{{ $selected_product['product_name'] }}</h2>
                        <p class="meta">{{ $selected_product['address'] }}</p>
                        <pre class="text-black-50">
{{ $selected_product['product_description'] }}
                        </pre>

                        <p class="meta"><u>Prix</u> : {{ $selected_product['converted_price'] . ' ' . $selected_product['readable_currency'] }}</p>
                        <p class="mt-3 mb-0">
                            <button class="btn btn-lg adrm-btn-red"><i class="bi bi-cart3 me-2"></i>Ajouter au panier</button>
                        </p>
@if ($selected_product['user_id'] == $current_user['id'])
                        <p class="mt-3 mb-0">
                            <button class="btn btn-lg btn-primary"><i class="bi bi-pencil me-2"></i>Modifier l'offre</button>
                        </p>
@endif
                    </div>

                    <div class="col-lg-6">
                        <div class="d-block agent-box p-5">
                            <div class="img mb-4">
                                <img src="{{ $selected_product['user']['avatar_url'] }}" alt="Image" class="img-fluid" />
                            </div>

                            <div class="text">
                                <h3 class="mb-0 fw-bold">{{ $selected_product['user']['firstname'] . ' ' . $selected_product['user']['lastname'] }}</h3>
                                <div class="meta mb-3">{{ $selected_product['user']['selected_role']['role_name'] }}</div>
                                <p class="small mb-0 fst-italic"><u>Date de publication</u><br>{{ ucfirst($selected_product['created_at_explicit']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
