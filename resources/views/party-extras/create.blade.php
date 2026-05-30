@extends('layouts.app')

@section('content')

<form action="{{ route('party-extras.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    @include('party-extras.partials.form')

</form>

@endsection
    
    







