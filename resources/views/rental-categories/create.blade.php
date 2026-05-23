@extends('layouts.app')

@section('content')

<form action="{{ route('rental-categories.store') }}"
    method="POST">

    @csrf

    @include('rental-categories.partials.form', [
        'title' => 'Create Category',
        'buttonText' => 'Save',
        'category' => null
    ])

</form>

@endsection