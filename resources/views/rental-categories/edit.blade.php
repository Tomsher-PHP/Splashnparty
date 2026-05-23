@extends('layouts.app')

@section('content')

<form action="{{ route('rental-categories.update', $rental_category->id) }}"
    method="POST">

    @csrf
    @method('PUT')

    @php
        $category = $rental_category;
    @endphp

    @include('rental-categories.partials.form', [
        'title' => 'Edit Category',
        'buttonText' => 'Update',
        'category' => $category
    ])

</form>

@endsection