@extends('layouts.app', ['page_title' => !empty($entity_title) ? $entity_title : __('miscellaneous.menu.account.title')])

@section('app-content')

    @if (!empty($entity))
        @if (!empty($selected_product))
            @include('partials.account.datas')
        @else
            @include('partials.account.' . $entity)
        @endif
    @else
        @include('partials.account.home')
    @endif

@endsection
