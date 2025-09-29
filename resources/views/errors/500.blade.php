@extends('layouts.app', ['page_title'  => __('notifications.' . $exception->getStatusCode() . '_title')])

@section('app-content')

        <div class="hero page-inner overlay" style="background-image: url('images/hero_bg_1.jpg')">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-9 text-center mt-5">
                        <h1 class="heading" data-aos="fade-up">{{ __('notifications.' . $exception->getStatusCode() . '_title') }}</h1>

                        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                            <ol class="breadcrumb text-center justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">{{ __('notifications.' . $exception->getStatusCode() . '_title') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="container">
                <div class="row mb-5 align-items-center">
						<div class="col-md-12">
							<div class="no-content-comment text-center">
								<h2 class="display-1 fw-bold adrm-text-green">{{ $exception->getStatusCode() }}</h2>
								<h3 class="text-black">{!! __('notifications.' . $exception->getStatusCode() . '_description') !!}</h3>
							</div><!-- End .no-content-comment -->
						</div><!-- End .col-md-12 -->
					</div><!-- End .row -->
				</div><!-- End .container -->
            </div>
        </div>

@endsection
