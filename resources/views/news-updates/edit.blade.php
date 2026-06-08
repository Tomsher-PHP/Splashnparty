@extends('layouts.app')
@section('content')

<form action="{{ route('news-updates.update', $newsUpdate) }}"
    method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    Edit News and Updates
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
                $isEdit = true;
            @endphp
            @include('news-updates.partials.form')
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-sm btn-primary">
                Update
            </button>
            <button type="reset"
                    class="btn btn-sm btn-outline-secondary">
                Cancel
            </button>
        </div>
    </div>
</form>
@endsection