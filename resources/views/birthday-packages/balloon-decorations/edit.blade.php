@extends('layouts.app')

@section('content')

<form action="{{ route('balloon-decorations.update', $balloon_decoration->id) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('birthday-packages.balloon-decorations.partials.form' , [
        'balloon_decoration' => $balloon_decoration,
        'action' => 'Update'
        ])

</form>

@endsection