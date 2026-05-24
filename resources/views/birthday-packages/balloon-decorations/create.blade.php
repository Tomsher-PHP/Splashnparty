@extends('layouts.app')

@section('content')

<form action="{{ route('balloon-decorations.store') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    @include('birthday-packages.balloon-decorations.partials.form', [
        'balloon_decoration' => null,
        'action' => 'Create'
        ])

</form>

@endsection