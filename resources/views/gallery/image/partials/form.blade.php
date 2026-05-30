<div class="row">
    {{-- Category Name --}}
    <div class="col-md-6 mb-20">
        <label class="form-label fw-semibold">
            Category Name <span class="text-danger">*</span>
        </label>

        <input type="text"
            name="category_name"
            class="form-control form-control-sm"
            value="{{ old('category_name', $gallery->category_name ?? '') }}"
            placeholder="Enter category name">

        @error('category_name')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    
    {{-- Status --}}
    <div class="col-md-6 mb-20">
        <label class="form-label fw-semibold">
            Status
        </label>
        <select name="status"
            class="form-select form-select-sm">
            <option value="1"
                {{ old('status', $gallery->status ?? 1) == 1 ? 'selected' : '' }}>
                Active
            </option>
            <option value="0"
                {{ old('status', $gallery->status ?? 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
    </div>

    {{-- Images --}}
    <div class="col-md-12 mb-20">
        <label class="form-label fw-semibold">
            Upload Images
        </label>
        <input type="file"
            name="images[]"
            class="form-control form-control-sm"
            multiple>
        @error('images')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Existing Images --}}
    @if(!empty($gallery?->images))
    <div class="col-md-12 mb-20">

        <label class="form-label fw-semibold d-block mb-3">
            Existing Images
        </label>

        <div class="row g-3 sortable-edit-gallery"
            id="sortable-edit-gallery">

            @foreach($gallery->images as $key => $image)

            <div class="col-auto sortable-item"
                data-image="{{ $image }}">

                <div class="position-relative border rounded-3 overflow-hidden bg-light gallery-image-card">

                    {{-- IMAGE --}}
                    <img src="{{ asset($image) }}"
                        class="gallery-edit-image"
                        style="height:150px;width:150px; object-fit:cover;">

                    {{-- TOP ACTIONS --}}
                    <div class="position-absolute top-0 start-0 end-0 p-2 d-flex justify-content-between align-items-center">

                        {{-- DRAG HANDLE --}}
                        <span class="bg-dark bg-opacity-75 text-white rounded-circle d-flex align-items-center justify-content-center drag-handle"
                            style="width:32px;height:32px;cursor:grab;">

                            <i class="ri-draggable"></i>

                        </span>

                        {{-- REMOVE BUTTON --}}
                        <button type="button"
                            class="btn btn-sm btn-danger remove-image-btn rounded-circle d-flex align-items-center justify-content-center"
                            style="width:32px;height:32px;"
                            data-image="{{ $image }}">

                            <i class="ri-delete-bin-line"></i>

                        </button>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

        {{-- HIDDEN INPUT --}}
        <input type="hidden"
            name="existing_images"
            id="existing-images-input"
            value='@json($gallery->images ?? [])'>

        <small class="text-muted d-block mt-2">
            Drag images to reorder or remove unwanted images
        </small>

    </div>
    @endif
</div>

<!-- SEO  COMPONENT -->
@include('components.seo-fields', [
    'model' => $gallery ?? null
])

