@extends('layouts.app', ['page_title' => 'Mon panier'])

@section('app-content')

        <div class="hero page-inner overlay" style="background-image: url('../images/hero_bg_1.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">Mon panier</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">Mon panier</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="container-fluid container-lg">
                <div class="row g-3">
                    <div class="col-lg-10 col-sm-11 col-12 mx-auto">

                            <div class="card card-body border pt-sm-4 pt-0 px-4 rounded-4 position-relative">
                                <div id="ajaxLoader" class="spinner-border text-secondary position-absolute d-none" role="status" style="top: 1rem; right: 1rem;">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>


                                <div class="mt-sm-0 my-4 text-center">
                                    <h1 class="card-title fw-bolder"><span class="adrm-text-green d-inline">Mon panier</span></h1>
                                </div>

@if (!empty($items))
                                <div id="dataList" class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center bg-secondary">
                                                <th class="fw-bold">Offre</th>
                                                <th class="fw-bold">Prix unitaire</th>
                                                <th class="fw-bold">Quantité</th>
                                                <th class="fw-bold">Prix total</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
    @foreach ($items as $item)
                                            <tr>
                                                <td style="max-width: 16rem;">
                                                    <img src="{{ count($item['photos']) > 0 ? $item['photos'][0] : asset('assets/img/undefined.png') }}" alt="{{ $item['product_name'] }}" width="50" style="float: left; margin-right: 1rem;">
                                                    <a href="{{ route('product.datas', ['id' => $item['id']]) }}">
                                                        {{ $item['product_name'] }}
                                                    </a>
                                                </td>
                                                <td class="text-center">{{ formatDecimalNumber($item['price'], 3) . ' $' }}</td>
                                                <td>
                                                    <div class="d-flex flex-row">
                                                        <input type="text" name="quantity" id="order-quantity-{{ $item['id'] }}" class="form-control text-center" value="{{ $item['quantity'] }}" onchange="updateProductQuantity('update', {{ $item['id'] }}, this.value)" style="width: 80px;">
                                                        <div class="d-flex flex-column">
        @if ($item['quantity'] > 0)
                                                            <a role="button" class="btn btn-secondary px-2 pt-1 pb-0" onclick="event.preventDefault(); document.getElementById('ajaxLoader').classList.remove('d-none'); updateProductQuantity('increment', {{ $item['id'] }});">
                                                                <i class="fa fa-plus"></i>
                                                            </a>
        @endif
		@if ($item['quantity'] > 1)
                                                            <a role="button" class="btn btn-secondary px-2 pt-1 pb-0" onclick="event.preventDefault(); document.getElementById('ajaxLoader').classList.remove('d-none'); updateProductQuantity('decrement', {{ $item['id'] }});">
                                                                <i class="fa fa-minus"></i>
                                                            </a>
		@endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ formatDecimalNumber($cartService->subtotalPrice($item, 'USD')) . ' $' }}</td>
                                                <td>
                                                    <a role="button" class="btn btn-secondary px-2 pt-1 pb-0 rounded-circle text-primary" onclick="event.preventDefault(); performAction('delete', 'order', 'item-{{ $item['id'] }}')" title="Retirer du panier" style="width: 30px; height: 30px;">
                                                        <i class="bi bi-x-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
    @endforeach
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered mt-3">
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-end">
                                                    <strong>{{ 'TOTAL : ' . formatDecimalNumber($session_cart_total) . ' $' }}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="mt-4 text-end">
                                        <a href="{{ route('login', ['cart' => '1']) }}" class="btn btn-lg adrm-btn-red rounded-pill" data-bs-toggle="modal" data-bs-target="#payModal">Effectuer le paiement</a>
                                    </div>
                                </div>
@else
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <p class="mb-0"><i class="bi bi-cart3 fs-1"></i></p>
                                    <p class="mb-0">La liste est vide</p>
                                </div>
@endif
                            </div>

					</div>
                </div>
            </div>
        </div>

@endsection
