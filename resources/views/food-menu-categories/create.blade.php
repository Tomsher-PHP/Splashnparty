@extends('layouts.app')

@section('content')

<form action="{{ route('food-menu-categories.store') }}"
    method="POST">

    @csrf

    @include('food-menu-categories.partials.form', [
        'title' => 'Create Category',
        'buttonText' => 'Save',
        'category' => null
    ])

</form>

@endsection