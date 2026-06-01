@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Add Attraction/Adventure</h6>
    <div>
        <a href="{{ route('attractions.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Back to List
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-24" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('attractions.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-md">Attraction/Adventure Details</h6>
        </div>

        <div class="card-body">
            <div class="row g-3">
                {{-- TITLE --}}
                <div class="col-md-8">
                    <label for="attractionTitle" class="form-label fw-semibold text-secondary-light mb-8">Title <span class="text-danger">*</span></label>
                    <input type="text"
                        name="title"
                        id="attractionTitle"
                        class="form-control form-control-sm"
                        value="{{ old('title') }}"
                        placeholder="Enter title"
                        required>
                </div>

                {{-- TYPE --}}
                <div class="col-md-4">
                    <label for="attractionType" class="form-label fw-semibold text-secondary-light mb-8">Type <span class="text-danger">*</span></label>
                    <select name="type" id="attractionType" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select Type</option>
                        <option value="attraction" {{ old('type') === 'attraction' ? 'selected' : '' }}>Attraction</option>
                        <option value="adventure" {{ old('type') === 'adventure' ? 'selected' : '' }}>Adventure</option>
                    </select>
                </div>

                {{-- BRANCHES --}}
                <div class="col-12 mt-20">
                    <label class="form-label fw-semibold text-secondary-light mb-12">Select Branches <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        @foreach ($branches as $branch)
                            @php
                                $isChecked = is_array(old('branch_ids')) && in_array($branch->id, old('branch_ids'));
                            @endphp
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="card h-100 border rounded-8 shadow-none position-relative cursor-pointer branch-select-card {{ $isChecked ? 'border-primary-600 bg-primary-50' : 'border-neutral-200' }}" style="transition: all 0.2s ease;">
                                    <label class="d-flex align-items-center gap-12 p-16 w-100 h-100 mb-0 cursor-pointer" for="branch_{{ $branch->id }}">
                                        <div class="form-check checkbox-primary m-0">
                                            <input class="form-check-input branch-checkbox-input" type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" id="branch_{{ $branch->id }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                        </div>
                                        <div class="min-w-0">
                                            <h6 class="text-sm fw-semibold text-dark mb-4 text-truncate">{{ $branch->title }}</h6>
                                            <span class="text-xxs text-neutral-400 d-block">Active Venue</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- IMAGE --}}
                <div class="col-md-6 mt-20">
                    <label for="imageInput" class="form-label fw-semibold text-secondary-light mb-8">Image <span class="text-danger">*</span></label>
                    <div class="image-upload-dropzone">
                        <input type="file"
                            name="image"
                            id="imageInput"
                            class="form-control form-control-sm"
                            accept="image/*"
                            required>
                        <small class="text-secondary-light d-block mt-2">
                            Allowed formats: JPG, JPEG, PNG, WEBP. Max size: 2MB.
                        </small>
                    </div>

                    <div class="mt-3 d-none" id="imagePreviewContainer">
                        <div class="position-relative d-inline-block">
                            <img id="imagePreview" src="#" alt="Preview" class="rounded border" style="max-height: 140px; max-width: 200px; object-fit: cover;">
                            <button type="button" id="removePreviewBtn" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-2 shadow" style="padding: 4px 8px;">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- SORT ORDER --}}
                <div class="col-md-3 mt-20">
                    <label for="attractionSort" class="form-label fw-semibold text-secondary-light mb-8">Sort Order</label>
                    <input type="number"
                        name="sort_order"
                        id="attractionSort"
                        class="form-control form-control-sm"
                        value="{{ old('sort_order', 0) }}"
                        min="0">
                </div>

                {{-- STATUS --}}
                <div class="col-md-3 mt-20">
                    <label for="attractionStatus" class="form-label fw-semibold text-secondary-light mb-8">Status <span class="text-danger">*</span></label>
                    <select name="status" id="attractionStatus" class="form-select form-select-sm" required>
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- DESCRIPTION --}}
                <div class="col-md-12 mt-20">
                    <label for="attractionDescription" class="form-label fw-semibold text-secondary-light mb-8">Description</label>
                    <textarea name="description"
                        id="attractionDescription"
                        class="form-control"
                        rows="5"
                        placeholder="Enter description...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-sm btn-primary-600 px-24">
                Submit
            </button>
            <a href="{{ route('attractions.index') }}" class="btn btn-sm btn-outline-secondary px-24 ms-2">
                Cancel
            </a>
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Dynamic Image Preview
        const imageInput = document.getElementById('imageInput');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');
        const removePreviewBtn = document.getElementById('removePreviewBtn');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('d-none');
            }
        });

        removePreviewBtn.addEventListener('click', function () {
            imageInput.value = '';
            previewContainer.classList.add('d-none');
            previewImg.src = '#';
        });

        // Toggle active styling on branch selection cards dynamically
        document.querySelectorAll('.branch-checkbox-input').forEach(checkbox => {
            const card = checkbox.closest('.branch-select-card');
            
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    card.classList.add('border-primary-600', 'bg-primary-50');
                    card.classList.remove('border-neutral-200');
                } else {
                    card.classList.remove('border-primary-600', 'bg-primary-50');
                    card.classList.add('border-neutral-200');
                }
            });
        });
    });
</script>
@endsection

@section('style')
<style>
    .image-upload-dropzone {
        position: relative;
    }
    .branch-select-card {
        transition: all 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .branch-select-card:hover {
        transform: translateY(-2px);
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.03);
    }
</style>
@endsection
