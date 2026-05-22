@extends('layouts.app')

@section('content')

<form action="{{ route('branches.update', $branch) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('branches.partials.form', [
        'buttonText' => 'Update',
        'model' => $branch
    ])

</form>

@endsection