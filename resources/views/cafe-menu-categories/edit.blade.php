@extends('layouts.app')

@section('content')

<form action="{{ route('cafe-menu-categories.update', $cafe_menu_category->id) }}"
    method="POST">

    @csrf
    @method('PUT')

    @php
        $category = $cafe_menu_category;
    @endphp

    @include('cafe-menu-categories.partials.form', [
        'title' => 'Edit Category',
        'buttonText' => 'Update',
        'category' => $category
    ])

</form>

@endsection