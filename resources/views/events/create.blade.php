@extends('layouts.app')

@section('content')

<form method="POST"
    action="{{ route('events.store') }}"
    enctype="multipart/form-data">

    @csrf

    @include('events.partials.form')

</form>

@endsection