@extends('layouts.app')

@section('content')

<form action="{{ route('food-menus.update', $foodMenu->id) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('food-menus.partials.form')

</form>

@endsection