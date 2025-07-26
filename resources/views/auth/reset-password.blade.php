@extends('layouts.app', ['page_title' => __('auth.reset-password')])

@section('app-content')

        <div class="hero page-inner overlay" style="background-image: url('images/hero_bg_1.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">@lang('auth.reset-password')</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('miscellaneous.menu.home')</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">@lang('auth.reset-password')</li>
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
                                <h4 class="mb-4 px-0">@lang('miscellaneous.reset_password_info')</h4>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-outline-success border rounded-pill fs-5">
                            @lang('miscellaneous.cancel') <i class="bi bi-chevron-double-right" style="vertical-align: -1px;"></i>
                        </a>
                    </div>
                    <div class="col-lg-5 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                        <form method="POST" action="{{ route('password.reset') }}">
    @csrf
                            <div class="row">
                                <div class="col-12 position-relative">
                                    <input type="password" name="new_password" id="register_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="@lang('miscellaneous.password.label')" />
                                    <button id="showNewPassword" class="btn bg-transparent position-absolute" style="top: 3px; right: 16px; z-index: 999; padding: 5px;" onclick="event.preventDefault(); passwordVisible(this, 'register_password');">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
    @error('new_password')
                                <div class="col-12">
                                    <p class="text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
                                </div>
    @enderror

                                <div class="col-12 position-relative mt-2">
                                    <input type="password" name="confirm_new_password" id="register_confirm_password" class="form-control @error('confirm_new_password') is-invalid @enderror" placeholder="@lang('auth.confirm-password')" />
                                    <button id="showConfirmNewPassword" class="btn bg-transparent position-absolute" style="top: 3px; right: 16px; z-index: 999; padding: 5px;" onclick="event.preventDefault(); passwordVisible(this, 'register_confirm_password');">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
    @error('confirm_new_password')
                                <div class="col-12">
                                    <p class="text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
                                </div>
    @enderror

                                <div class="col-12 mt-2">
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
