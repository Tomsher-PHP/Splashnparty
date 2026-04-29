@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-4">Create Client Logo</h6>
            <p class="mb-0 text-secondary-light">Add a client logo with link, status, and display order.</p>
        </div>
        <a href="{{ route('client-logos.index') }}"
            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Back to Client Logos
        </a>
    </div>

    <form action="{{ route('client-logos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('client-logos.partials.form')
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                        <i class="ri-save-line"></i>
                        Save Client Logo
                    </button>
                    <a href="{{ route('client-logos.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@include('client-logos.partials.scripts')
