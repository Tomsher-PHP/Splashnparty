@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-4">Edit Header Menu Item</h6>
        <p class="mb-0 text-secondary-light">
            Update the menu label, link, or parent relationship.
        </p>
    </div>

    <a href="{{ route('header-menus.index') }}"
        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="ri-arrow-left-line"></i>
        Back to Header Menus
    </a>
</div>

<form action="{{ route('header-menus.update', $headerMenu) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">
            @include('header-menus.partials.form')
        </div>

        <div class="card-footer d-flex justify-content-end align-items-center gap-2">
            <a href="{{ route('header-menus.index') }}"
                class="btn btn-sm btn-outline-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                <i class="ri-save-line"></i>
                Update Menu Item
            </button>
        </div>
    </div>
</form>
@endsection
