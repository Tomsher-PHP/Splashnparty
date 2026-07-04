@extends('layouts.app')
@section('content')
<form action="{{ route('news-updates.store') }}"
    method="POST"
    enctype="multipart/form-data">
    @csrf

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    Add News and Updates
                </h6>
                <div>
                    <a href="{{ route('news-updates.index') }}"
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
            @include('news-updates.partials.form')
        </div>

        <div class="card-footer text-end">
            <button class="btn btn-sm btn-primary">
                Submit
            </button>
            <button type="reset"
                    class="btn btn-sm btn-outline-secondary">
                Cancel
            </button>
        </div>
    </div>
</form>

@endsection