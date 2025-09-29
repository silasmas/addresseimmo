@extends('layouts.app', ['page_title' => !empty($entity_title) ? $entity_title : 'Mon compte'])

@section('app-content')

        <div class="hero page-inner overlay" style="background-image: url('../images/hero_bg_1.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">{{ !empty($entity_title) ? $entity_title : 'Mon compte' }}</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
    @if (!empty($entity))
                                <li class="breadcrumb-item"><a href="{{ route('account.home') }}">Mon compte</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">{{ $entity_title }}</li>
    @else
                                <li class="breadcrumb-item active text-white-50" aria-current="page">Mon compte</li>
    @endif
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="container-fluid container-lg">
                <div class="row g-3">
                    <div class="col-lg-3 col-sm-4 mx-auto mb-3">
                        <div class="card border mb-3 rounded-4">
                            <div class="card-body text-center px-2">
                                <div class="bg-image mb-3 position-relative">
                                    <img src="{{ !empty($current_user['avatar_url']) ? $current_user['avatar_url'] : asset('assets/img/user.png') }}" alt="{{ $current_user['firstname'] . ' ' . $current_user['lastname'] }}" class="user-image img-fluid img-thumbnail rounded-4">
    @if (Route::is('account.entity'))
                                    <form method="POST">
                                        <input type="hidden" name="user_id" id="user_id" value="{{ $current_user['id'] }}">
                                        <label for="avatar" class="btn btn-secondary position-absolute p-2 rounded-circle" style="width: 2.5rem; height: 2.5rem; top: 0.5rem; left: 0.5rem; z-index: 999;" title="@lang('miscellaneous.change_image')" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                            <span class="bi bi-pencil-fill"></span>
                                            <input type="file" name="avatar" id="avatar" class="d-none">
                                        </label>
                                    </form>
    @endif
                                </div>

                                <h4 class="h4 m-0 fw-bold">{{ $current_user['firstname'] . ' ' . $current_user['lastname'] }}</h4>
    @if (!empty($current_user['username']))
                                <p class="card-text m-0 text-muted">{{ '@' . $current_user['username'] }}</p>
    @endif
                            </div>
                        </div>

                        <div class="list-group">
                            <a href="{{ route('account.home') }}" class="list-group-item list-group-item-action{{ Route::is('account.home') ? ' active' : '' }}">
                                <i class="bi bi-person me-3 fs-5 align-middle"></i>@lang('miscellaneous.account.personal_infos.title')
                            </a>
                            <a href="{{ route('account.entity', ['entity' => 'settings']) }}" class="list-group-item list-group-item-action{{ !empty($entity) && $entity == 'settings' ? ' active' : '' }}">
                                <i class="bi bi-gear me-3 fs-5 align-middle"></i>Paramètres du compte
                            </a>
                            <a href="{{ route('account.entity', ['entity' => 'cart']) }}" class="list-group-item list-group-item-action{{ !empty($entity) && $entity == 'cart' ? ' active' : '' }}">
                                <i class="bi bi-cart3 me-3 fs-5 align-middle"></i>Panier
                            </a>
                            <a href="{{ route('account.entity', ['entity' => 'offers']) }}" class="list-group-item list-group-item-action{{ !empty($entity) && $entity == 'offers' ? ' active' : '' }}">
                                <i class="bi bi-box2 me-3 fs-5 align-middle"></i>Offres
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-9 col-sm-8 col-12 mb-3">
    @if (!empty($entity))
        @include('partials.account.' . $entity)
    @else
        @include('partials.account.home')
    @endif
                    </div>
                </div>
            </div>
        </div>

@endsection
