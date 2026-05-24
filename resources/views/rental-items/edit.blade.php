@extends('layouts.app')

@section('content')

<form action="{{ route('rental-items.update', $rental_item->id) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('rental-items.partials.form' , [
        'rental_item' => $rental_item,
        'action' => 'Update'
        ])

</form>

@endsection