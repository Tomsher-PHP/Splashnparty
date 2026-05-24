@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-4">Create Testimonial</h6>
            <p class="mb-0 text-secondary-light">Add a new client testimonial with rating, name, and comment.</p>
        </div>
        <a href="{{ route('testimonials.index') }}"
            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Back to Testimonials
        </a>
    </div>

    <form action="{{ route('testimonials.store') }}" method="POST">
        @csrf

        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('testimonials.partials.form')
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                        <i class="ri-save-line"></i>
                        Save Testimonial
                    </button>
                    <a href="{{ route('testimonials.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection
