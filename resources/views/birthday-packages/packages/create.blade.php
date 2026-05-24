@extends('layouts.app')

@section('content')

<form method="POST"
    action="{{ route('birthday-packages.store') }}"
    enctype="multipart/form-data">

    @csrf

    @include('birthday-packages.packages.partials.form')

</form>

@endsection