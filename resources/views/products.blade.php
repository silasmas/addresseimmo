@extends('layouts.app', ['page_title' => !empty($entity_title) ? $entity_title : 'Produits'])

@section('app-content')

    @if (!empty($selected_product))
        @include('partials.products.datas')
    @else
        @if (!empty($entity))
            @include('partials.products.' . $entity)
        @else
            @include('partials.products.home')
        @endif
    @endif

@endsection
