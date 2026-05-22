@extends('layouts.app')

@section('content')

<div class="justify-content-center">
    <form action="{{ route('outdoor-events.store') }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf
        @include('gallery.outdoor-events.partials.form')
    </form>
</div>

@endsection