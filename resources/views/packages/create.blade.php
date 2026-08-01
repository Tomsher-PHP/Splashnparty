@extends('layouts.app')
@section('content')
<form action="{{ route('packages.store') }}"
    method="POST"
    enctype="multipart/form-data">
    @csrf

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    Add Package Details
                </h6>
                <div>
                    <a href="{{ route('packages.index') }}"
                        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <i class="ri-arrow-left-line"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @php
                $isEdit = false;
            @endphp
            @include('packages.partials.form')
        </div>

        <div class="card-footer text-end">
            <button class="btn btn-sm btn-primary">
                Submit
            </button>
            <a href="{{ route('packages.index') }}"
               class="btn btn-sm btn-outline-secondary">
                Cancel
            </a>
        </div>
    </div>
</form>

@endsection