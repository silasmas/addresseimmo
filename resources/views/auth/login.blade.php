@extends('layouts.app', ['page_title' => __('miscellaneous.login_title1')])

@section('app-content')

        <div class="hero page-inner overlay" style="background-image: url('images/hero_bg_1.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">@lang('miscellaneous.login_title1')</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('miscellaneous.menu.home')</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">@lang('miscellaneous.login_title1')</li>
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
                        <div class="d-sm-flex align-items-sm-center">
                            <i class="bi bi-shield-lock mx-3 text-danger" style="font-size: 5rem;"></i>
                            <div id="contactInfo" class="contact-info">
                                <h4 class="mb-4 px-0">@lang('miscellaneous.login_description')</h4>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="btn btn-outline-success border rounded-pill fs-5">
                            @lang('miscellaneous.go_register') <i class="bi bi-chevron-double-right" style="vertical-align: -1px;"></i>
                        </a>
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
                                    <a role="button" id="showPassword" class="btn bg-transparent position-absolute" style="top: 3px; right: 16px; z-index: 999; padding: 5px;" onclick="event.preventDefault(); passwordVisible(this, 'sign_password');">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </div>
                                <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                                        <label class="form-check-label" for="checkDefault">
                                            @lang('miscellaneous.remember_me')
                                        </label>
                                    </div>

                                    <a href="{{ route('password.request') }}" class="btn-link ms-2">
                                        @lang('miscellaneous.forgotten_password')
                                         <i class="bi bi-chevron-double-right" style="vertical-align: -1px;"></i>
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
                                    <input type="submit" value="@lang('auth.login')" class="btn adrm-btn-green w-100 text-uppercase" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.untree_co-section -->

@endsection
