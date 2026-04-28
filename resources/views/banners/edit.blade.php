@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-4">Edit Banner</h6>
            <p class="mb-0 text-secondary-light">Update banner content, media, and status.</p>
        </div>
        <a href="{{ route('banners.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Back to Banners
        </a>
    </div>

    <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('banners.partials.form', ['banner' => $banner])
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                        <i class="ri-save-line"></i>
                        Update Banner
                    </button>
                    <a href="{{ route('banners.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@include('banners.partials.scripts')
