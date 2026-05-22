@extends('layouts.app')

@section('content')

@include('gallery.video.partials.form', [
    'action' => route('video-gallery.store'),
    'method' => 'POST',
    'title' => 'Create Video Gallery',
    'buttonText' => 'Save Gallery',
    'gallery' => null
])

@endsection

