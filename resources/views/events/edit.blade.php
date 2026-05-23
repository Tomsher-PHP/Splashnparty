@extends('layouts.app')

@section('content')

<form method="POST"
    action="{{ route('events.update', $event->id) }}"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('events.partials.form')

</form>

@endsection