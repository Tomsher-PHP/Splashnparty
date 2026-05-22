@extends('layouts.app')

@section('content')

<form action="{{ route('cafe-menu-categories.store') }}"
    method="POST">

    @csrf

    @include('cafe-menu-categories.partials.form', [
        'title' => 'Create Category',
        'buttonText' => 'Save',
        'category' => null
    ])

</form>

@endsection