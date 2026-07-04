@extends('layouts.app')

@section('content')

<form action="{{ route('party-extras.update',$partyExtra) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('party-extras.partials.form')

</form>

@endsection