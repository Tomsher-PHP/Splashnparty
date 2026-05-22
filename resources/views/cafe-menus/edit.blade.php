@extends('layouts.app')

@section('content')

<form action="{{ route('cafe-menus.update', $cafe_menu->id) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('cafe-menus.partials.form' , [
        'cafe_menu' => $cafe_menu,
        'action' => 'Update'
        ])

</form>

@endsection