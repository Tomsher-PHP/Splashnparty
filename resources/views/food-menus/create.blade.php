@extends('layouts.app')

@section('content')

<form action="{{ route('food-menus.store') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    @include('food-menus.partials.form')

</form>

@endsection