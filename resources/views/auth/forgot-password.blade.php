@extends('layouts.app', ['page_title' => 'S’identifier'])

@section('app-content')

        <div class="hero page-inner overlay" style="background-image: url('images/hero_bg_2.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">{{ session()->has('email') || session()->has('phone') ? __('notifications.token_title') : __('auth.verify-email-phone') }}</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">{{ session()->has('email') || session()->has('phone') ? __('notifications.token_title') : __('auth.verify-email-phone') }}</li>
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
                        {{-- <i class="bi bi-shield-lock text-danger" style="font-size: 5rem;"></i> --}}
                        <div id="contactInfo" class="contact-info">
                            <h4 class="mb-4 px-0">{{ session()->has('email') || session()->has('phone') ? __('notifications.token_sent') : __('miscellaneous.forgotten_password_info') }}</h4>
                            <a href="{{ route('register') }}" class="btn btn-outline-success border pt-0 pb-sm-2 pb-0 rounded-pill fs-5">
                                @lang('miscellaneous.cancel') <span class="fs-1 d-inline-block" style="vertical-align: -3px;">&raquo;</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                        <form method="POST" action="{{ session()->has('email') || session()->has('phone') ? route('token.request') : route('password.request') }}">
    @csrf

                            <div class="row">
    @if (session()->has('email') || session()->has('phone'))
                                <div class="col-12">
                                    <input type="text" name="token" class="form-control @error('token') is-invalid @enderror" placeholder="@lang('notifications.token_placeholder')" value="{{ old('token') }}" />
                                </div>
        @error('token')
                                <div class="col-12">
                                    <p class="m-0 text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
                                </div>
        @enderror

                                <div class="col-12">
                                    <input type="submit" value="@lang('miscellaneous.send')" class="btn adrm-btn-green w-100 text-uppercase" />
                                </div>
    @else
        @if (request()->get('check') == 'phone')
                                <div class="col-12">
                                    <input type="text" name="data" id="check_data" class="form-control @error('data') is-invalid @enderror" placeholder="@lang('miscellaneous.phone_number')" value="{{ old('data') }}" />
                                </div>
            @error('data')
                                <div class="col-12">
                                    <p class="m-0 text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
                                </div>
            @enderror
        @else
                                <div class="col-12">
                                    <input type="text" name="data" id="check_data" class="form-control @error('data') is-invalid @enderror" placeholder="@lang('miscellaneous.email')" value="{{ old('data') }}" />
                                </div>
            @error('data')
                                <div class="col-12">
                                    <p class="m-0 text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
                                </div>
            @enderror
        @endif

                                <div class="col-12">
                                    <input type="submit" value="@lang('miscellaneous.send')" class="btn adrm-btn-green w-100 my-3 text-uppercase" />
        @if (request()->get('check') == 'phone')
                                    <span style="display: inline-block; margin-left: 20px;"><a href="{{ route('password.request') }}">@lang('auth.verify-email')</a> <i class="bi bi-chevron-double-right"></i></span>
        @else
                                    <span style="display: inline-block; margin-left: 20px;"><a href="{{ route('password.request', ['check' => 'phone']) }}">@lang('auth.verify-phone')</a> <i class="bi bi-chevron-double-right"></i></span>
        @endif
                                </div>
    @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.untree_co-section -->

@endsection
