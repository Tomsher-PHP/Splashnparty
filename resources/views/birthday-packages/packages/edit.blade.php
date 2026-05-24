@extends('layouts.app')

@section('content')

<form method="POST"
    action="{{ route('birthday-packages.update', $birthday_package->id) }}"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('birthday-packages.packages.partials.form', [
        'package' => $birthday_package
    ])

</form>

@endsection