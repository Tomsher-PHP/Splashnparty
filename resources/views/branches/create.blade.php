@extends('layouts.app')

@section('content')

<form action="{{ route('branches.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    @include('branches.partials.form', [
        'buttonText' => 'Save',
        'model' => null
    ])

</form>

@endsection