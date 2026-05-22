@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Create Image Gallery</h6>
            <div>
                <a href="{{ route('image-gallery.index') }}"
                    class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">

        <form action="{{ route('image-gallery.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            @include('gallery.image.partials.form')

            <div class="mt-4" style="text-align: right;">
                <button type="submit"
                        class="btn btn-sm btn-primary-600 d-inline-flex align-items-center">
                    Save Gallery
                </button>
                <button type="reset"
                        class="btn btn-sm btn-outline-secondary">
                    Cancel
                </button>
            </div>

        </form>

    </div>

</div>

@endsection