@extends('layouts.app')

@section('content')

<form action="{{ route('rental-items.store') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    @include('rental-items.partials.form', [
        'rental_item' => null,
        'action' => 'Create'
        ])

</form>

@endsection