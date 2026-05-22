@extends('layouts.app')

@section('content')

<div class="justify-content-center">
    <form action="{{ route('outdoor-events.update', $outdoor_event) }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('gallery.outdoor-events.partials.form')
    </form>
</div>

@endsection