@extends('layouts.app', ['page_title' => __('miscellaneous.menu.account.cart')])

@section('app-content')

			<section id="content">
				<div id="breadcrumb-container">
					<div class="container">
						<ul class="breadcrumb">
							<li><a href="{{ route('home') }}">@lang('miscellaneous.menu.home')</a></li>
							<li class="active">{{ $user->firstname . ' ' . $user->lastname }}</li>
						</ul>
					</div>
				</div>
				<div class="container">
					<div class="row">
                        <div class="col-md-12">
							<header class="content-title">
								<h1 class="title" style="margin-bottom: 5px;">{{ $user->firstname . ' ' . $user->lastname }}</h1>
								<p class="title-desc"><i class="bi bi-mortarboard" style="font-size: 2.5rem; vertical-align: -3px"></i> {{ !empty($user->selected_role) ? $user->selected_role->role_name : 'NO-ROLE' }}</p>
							</header>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->firstname . ' ' . $user->lastname }}" style="border-radius: 2rem;">
                        </div>
						<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                            <table id="personalInfo" class="table" style="border: 0;">
                                <!-- First name -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.firstname')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->firstname) ? $user->firstname : '- - - - - -' }}</td>
                                </tr>

                                <!-- Last name -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.lastname')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td class="text-uppercase">{{ !empty($user->lastname) ? $user->lastname : '- - - - - -' }}</td>
                                </tr>

                                <!-- Surname -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.surname')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td class="text-uppercase">{{ !empty($user->surname) ? $user->surname : '- - - - - -' }}</td>
                                </tr>

                                <!-- Username -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.username')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->username) ? $user->username : '- - - - - -' }}</td>
                                </tr>

                                <!-- Gender -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.gender_title')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->gender) ? ($user->gender == 'F' ? __('miscellaneous.gender2') : __('miscellaneous.gender1')) : '- - - - - -' }}</td>
                                </tr>

                                <!-- Birth date -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.birth_date.label')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->birthdate) ? ucfirst(__('miscellaneous.on_date') . ' ' . explicitDate($user->birthdate))  : '- - - - - -' }}</td>
                                </tr>

                                <!-- E-mail -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.email')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->email) ? $user->email : '- - - - - -' }}</td>
                                </tr>

                                <!-- Phone -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.phone')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->phone) ? $user->phone : '- - - - - -' }}</td>
                                </tr>

                                <!-- P.O. box -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.p_o_box')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->p_o_box) ? $user->p_o_box : '- - - - - -' }}</td>
                                </tr>

                                <!-- City -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.address.city')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->city) ? $user->city : '- - - - - -' }}</td>
                                </tr>

                                <!-- Country -->
                                <tr>
                                    <td><strong>@lang('miscellaneous.country')</strong></td>
                                    <td>@lang('miscellaneous.colon_after_word')</td>
                                    <td>{{ !empty($user->country) ? $user->country : '- - - - - -' }}</td>
                                </tr>
                            </table>
						</div><!-- End .col-md-12 -->
					</div><!-- End .row -->
				</div><!-- End .container -->

			</section><!-- End #content -->

@endsection
