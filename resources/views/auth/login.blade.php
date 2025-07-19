@extends('layouts.app', ['page_title' => 'S’identifier'])

@section('app-content')

        <div class="hero page-inner overlay" style="background-image: url('images/hero_bg_1.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">S’identifier</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">S’identifier</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-sm-6 mb-5 mb-lg-0 text-sm-start text-center" data-aos="fade-up" data-aos-delay="100">
                        <i class="bi bi-shield-lock text-danger" style="font-size: 5rem;"></i>
                        <div class="contact-info">
                            <h4 class="mb-4 px-0">Vos données sont sécurisées avec nous. Alors vous devez vous identifier pour être sûr que c’est bien vous.</h4>
                            <a href="{{ route('register') }}" class="btn btn-outline-success border pt-0 pb-sm-2 pb-0 rounded-pill fs-5">
                                Cliquez ici pour vous inscrire <span class="fs-1 d-inline-block" style="vertical-align: -3px;">&raquo;</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                        <form method="POST" action="{{ route('login') }}">
    @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" placeholder="E-mail ou n° de téléphone" value="{{ old('login') }}" />
                                </div>
                                <div class="col-12 mb-3 position-relative">
                                    <input type="password" name="password" id="sign_password" class="form-control @error('password') is-invalid @enderror" placeholder="Mot de passe" />
                                    <button id="showPassword" class="btn bg-transparent position-absolute" style="top: 3px; right: 16px; z-index: 999; padding: 5px;" onclick="event.preventDefault(); passwordVisible(this, 'sign_password');">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                                <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                                        <label class="form-check-label" for="checkDefault">
                                            Rester connecté
                                        </label>
                                    </div>

                                    <a href="{{ route('password.request') }}" class="btn-link ms-2">
                                        Mot de passe oublié
                                         <span class="fs-3 d-inline-block" style="vertical-align: -3px;">&raquo;</span>
                                    </a>
                                </div>
    @error('login')
                                <div class="col-12 mb-3">
                                    <p class="text-danger text-center" style="margin-bottom: 5px;">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
                                </div>
    @enderror
    @error('password')
                                <div class="col-12 mb-3">
                                    <p class="text-danger text-center" style="margin-bottom: 5px;">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
                                </div>
    @enderror

                                <div class="col-12">
                                    <input type="submit" value="Connexion" class="btn adrm-btn-green w-100 text-uppercase" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.untree_co-section -->

@endsection
