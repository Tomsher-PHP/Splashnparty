@extends('layouts.app')

@section('content')

<form action="{{ route('cafe-menus.store') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    @include('cafe-menus.partials.form', [
        'cafe_menu' => null,
        'action' => 'Create'
        ])

</form>

@endsection