
                        <div class="card card-body border pt-sm-4 pt-0 px-4 rounded-4">
                            <div class="d-sm-flex justify-content-between mt-sm-0 my-4 text-center">
                                <h1 class="card-title fw-bolder"><span class="adrm-text-green d-inline">Mes offres</span></h1>

                                <button type="button" class="btn btn-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#addOfferModal">
                                    Publier une offre
                                </button>
                            </div>

                            <div class="row g-3">
@forelse ($items as $product)
                                <div class="col-sm-6">
                                    <div class="property-item mb-30">
                                        <a href="{{ route('product.datas', ['id' => $product['id']]) }}" class="img d-inline-block" style="max-height: 350px; overflow: hidden;">
                                            <img src="{{ count($product['photos']) > 0 ? $product['photos'][0]['file_url'] : asset('assets/img/undefined.png') }}" alt="Image" class="img-fluid" />
                                        </a>

                                        <div class="property-content">
                                            <div class="price mb-2"><span>{{ $product['converted_price'] . ' ' . $current_user['readable_currency'] }}</span></div>
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
                                </div>
@empty
                                <div class="col-12 d-flex justify-content-center align-items-center flex-column">
                                    <p class="mb-0"><i class="bi bi-house fs-1"></i></p>
                                    <p class="mb-0">La liste est vide</p>
                                </div>
@endforelse
                            </div>
                        </div>
