@extends('layouts.app', ['page_title' => !empty($entity_title) ? $entity_title : __('miscellaneous.menu.public.products.products')])

@section('app-content')

    @if (!empty($entity))
        @if (!empty($selected_product))
            @include('partials.products.datas')
        @else
            @include('partials.products.' . $entity)
        @endif
    @else
        @include('partials.products.home')
    @endif

@endsection
