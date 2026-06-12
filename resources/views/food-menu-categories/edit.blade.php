@extends('layouts.app')

@section('content')

<form action="{{ route('food-menu-categories.update', $food_menu_category->id) }}"
    method="POST">

    @csrf
    @method('PUT')

    @php
        $category = $food_menu_category;
    @endphp

    @include('food-menu-categories.partials.form', [
        'title' => 'Edit Category',
        'buttonText' => 'Update',
        'category' => $category
    ])

</form>

@endsection