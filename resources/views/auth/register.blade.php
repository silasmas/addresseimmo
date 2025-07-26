@extends('layouts.app', ['page_title' => __('miscellaneous.register_title1')])

@section('app-content')

        <div class="hero page-inner overlay" style="background-image: url('images/hero_bg_3.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">@lang('miscellaneous.register_title1')</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('miscellaneous.menu.home')</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">@lang('miscellaneous.register_title1')</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12" data-aos="fade-up" data-aos-delay="200">
                        <form method="POST" action="{{ route('register') }}">
    @csrf
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 mx-auto">
                                    <!-- Avatar -->
                                    <div id="profileImageWrapper" style="margin-bottom: 20px;">
                                        <div style="display: flex; justify-content: center; align-items: center;">
                                            <img src="{{ asset('assets/img/user.png') }}" alt="Avatar" width="200" class="other-user-image" style="border-radius: 5px;">
                                            <label role="button" for="image_profile" class="btn btn-sm btn-secondary ms-2 pt-2" title="@lang('miscellaneous.change_image')">
                                                <i class="bi bi-pencil-fill fs-4"></i>
                                                <input type="file" name="image_profile" id="image_profile" style="display: none;">
                                            </label>
                                        </div>
                                        <input type="hidden" name="image_64" id="image_64">
                                    </div>

                                    <!-- First name -->
                                    <div class="mt-2">
                                        <input type="text" name="firstname" required id="firstname" class="form-control @error('firstname') is-invalid @enderror" placeholder="@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.firstname')">
                                    </div>

    @error('firstname')
                                    <p class="text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
    @enderror

                                    <!-- Last name -->
                                    <div class="mt-2">
                                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.lastname')">
                                    </div>

                                    <!-- Surname -->
                                    <div class="mt-2">
                                        <input type="text" name="surname" id="surname" class="form-control" placeholder="@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.surname')">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6 mx-auto">
                                    <!-- Birthdate -->
                                    <div>
                                        <input type="text" name="birthdate" id="birthdate" class="form-control" placeholder="@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.birth_date.label')">
                                    </div>

                                    <!-- Gender -->
                                    <div class="mt-2 text-center">
                                        <label class="form-label fw-bold">@lang('miscellaneous.gender_title')</label>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="male" value="M">
                                                <label class="form-check-label" for="male">@lang('miscellaneous.gender1')</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="female" value="F">
                                                <label class="form-check-label" for="female">@lang('miscellaneous.gender2')</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- E-mail -->
                                    <div class="mt-2">
                                        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.email')">
                                    </div>

    @error('email')
                                    <p class="text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
    @enderror

                                    <!-- Phone -->
                                    <div class="mt-2">
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.phone_number')">
                                    </div>

    @error('phone')
                                    <p class="text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
    @enderror

                                    <!-- Address 1 -->
                                    <div class="mt-2">
                                        <textarea id="address_1" class="form-control" name="address_1" placeholder="@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.address.title')"></textarea>
                                    </div>

                                    <!-- Address 2 -->
                                    <div class="mt-2">
                                        <textarea id="address_2" class="form-control" name="address_2" placeholder="@lang('miscellaneous.address.line2')"></textarea>
                                    </div>

                                </div>

                                <div class="col-lg-4 col-sm-6 mx-auto">
                                    <!-- City -->
                                    <div class="mt-2">
                                        <input type="text" class="form-control" placeholder="@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.address.city')">
                                    </div>

                                    <!-- Country -->
                                    <div class="mt-2">
                                        <select name="country" id="country" class="form-control">
                                            <option class="small" disabled selected>@lang('miscellaneous.ones_you_masculine') @lang('miscellaneous.country')</option>
    @forelse ($countries as $country)
                                            <option>{{ $country['name'] }}</option>
    @empty
    @endforelse
                                        </select>
                                    </div>

                                    <!-- About me -->
                                    <div class="mt-3">
                                        <label for="about_me" class="form-label fw-bold">@lang('miscellaneous.about_user.label')</label>
                                        <textarea id="about_me" class="form-control" name="about_me" placeholder="@lang('miscellaneous.about_user.placeholder')"></textarea>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-12 position-relative mt-4">
                                        <input type="password" name="password" id="register_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="@lang('miscellaneous.password.label')" />
                                        <button id="showNewPassword" class="btn bg-transparent position-absolute" style="top: 3px; right: 16px; z-index: 999; padding: 5px;" onclick="event.preventDefault(); passwordVisible(this, 'register_password');">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                    </div>
    @error('new_password')
                                    <p class="text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
    @enderror

                                    <div class="col-12 position-relative mt-2">
                                        <input type="password" name="password_confirmation" id="register_confirm_password" class="form-control @error('confirm_new_password') is-invalid @enderror" placeholder="@lang('auth.confirm-password')" />
                                        <button id="showConfirmNewPassword" class="btn bg-transparent position-absolute" style="top: 3px; right: 16px; z-index: 999; padding: 5px;" onclick="event.preventDefault(); passwordVisible(this, 'register_confirm_password');">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                    </div>
    @error('password_confirmation')
                                    <p class="text-danger text-center">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </p>
    @enderror
                                </div>

                                <div class="col-12 mt-4 mx-auto text-center">
                                    <input type="submit" value="@lang('miscellaneous.register')" class="btn adrm-btn-green mb-3 text-uppercase" style="min-width: 250px;" />
                                    <p class="m-0"><a href="{{ route('login') }}">@lang('miscellaneous.go_login')</a> <i class="bi bi-chevron-double-right" style="vertical-align: -1px;"></i></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.untree_co-section -->

@endsection
