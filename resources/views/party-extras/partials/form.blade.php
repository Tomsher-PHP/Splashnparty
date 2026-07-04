@php
$isEdit = isset($partyExtra);
@endphp

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
               {{ $isEdit ? 'Edit' : 'Create' }} Party Extras
            </h6>
            <div>
                <a href="{{ route('party-extras.index') }}"
                    class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Category
                </label>
                <input type="text"
                    name="category"
                    id="category"
                    class="form-control form-control-sm"
                    value="{{ old('category',$partyExtra->category ?? '') }}"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Title
                </label>
                <input type="text"
                    id="title"
                    name="title"
                    class="form-control form-control-sm"
                    value="{{ old('title',$partyExtra->title ?? '') }}"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Slug</label>
                <input type="text"
                    id="slug"
                    name="slug"
                    class="form-control form-control-sm"
                    value="{{ old('slug',$partyExtra->slug ?? '') }}"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Type</label>
                <select name="type"
                    id="type"
                    class="form-select form-select-sm">
                    <option value="image_gallery"
                        {{ old('type',$partyExtra->type ?? '') == 'image_gallery' ? 'selected' : '' }}>
                        Image Gallery
                    </option>
                    <option value="video_link"
                        {{ old('type',$partyExtra->type ?? '') == 'video_link' ? 'selected' : '' }}>
                        Video Link
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Thumbnail Image</label>
                <input type="file"
                    name="thumbnail_image"
                    class="form-control form-control-sm">
                @if(!empty($partyExtra?->thumbnail_image))
                <div class="mt-3">
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset($partyExtra->thumbnail_image) }}"
                            width="140"
                            class="rounded border thumb-image">
                        <div class="image-overlay remove-thumbnail-image">
                            <button type="button"
                                class="btn btn-danger rounded-circle">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden"
                    name="remove_thumbnail"
                    id="remove_thumbnail"
                    value="0">
                @endif
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Sort Order</label>
                <input type="number"
                    name="sort_order"
                    class="form-control form-control-sm"
                    value="{{ old('sort_order',$partyExtra->sort_order ?? 0) }}">
            </div>

            <div class="col-md-6"
                id="galleryWrapper">
                <label class="form-label fw-semibold">
                    Gallery Images
                </label>
                <input type="file"
                    name="gallery_images[]"
                    multiple
                    class="form-control form-control-sm">

                <div class="d-flex gap-2 mt-3">
                    {{-- EXISTING GALLERY --}}
                    @if($isEdit && !empty($partyExtra->gallery_images))
                        @foreach($partyExtra->gallery_images as $image)
                            <div class="gallery-image-item">
                                <div class="position-relative">
                                    <img src="{{ asset($image) }}"
                                        class="img-fluid rounded border" width="140">
                                    <div class="image-overlay remove-gallery-image"
                                        data-image="{{ $image }}">
                                        <button type="button"
                                            class="btn btn-danger rounded-circle">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <input type="hidden"
                            name="existing_gallery_images"
                            id="existing_gallery_images"
                            value='@json($partyExtra->gallery_images)'>
                    @endif
                </div>
            </div>

            <div class="col-md-6 d-none"
                id="videoWrapper">
                <label class="form-label fw-semibold">
                    Video Link
                </label>
                <input type="url"
                    name="video_link"
                    class="form-control form-control-sm"
                    value="{{ old('video_link',$partyExtra->video_link ?? '') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Status
                </label>
                <select name="status"
                    class="form-select form-select-sm">
                    <option value="1"
                        {{ old('status',$partyExtra->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ old('status',$partyExtra->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>
            </div>
        </div>

        <!-- SEO  COMPONENT -->
        @include('components.seo-fields', [
            'model' => $partyExtra ?? null
        ])
    </div>
    <div class="card-footer text-end">
        <button class="btn btn-sm btn-primary">
            {{ $isEdit ? 'Update' : 'Save' }}
        </button>
        <button type="reset"
                class="btn btn-sm btn-outline-secondary">
            Cancel
        </button>
    </div>
</div>

@section('script')

<script>
    document.getElementById('category').addEventListener('keyup', function() {
        document.getElementById('slug').value = this.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    });

    function toggleTypeFields() {
        let type = document.getElementById('type').value;
        document.getElementById('galleryWrapper')
            .classList.toggle(
                'd-none',
                type !== 'image_gallery'
            );

        document.getElementById('videoWrapper')
            .classList.toggle(
                'd-none',
                type !== 'video_link'
            );
    }

    document.getElementById('type')
        .addEventListener('change', toggleTypeFields);
    toggleTypeFields();

    document.addEventListener('DOMContentLoaded', function () {
        // REMOVE THUMBNAIL
        document.addEventListener('click', function(e){
            if(e.target.closest('.remove-thumbnail-image')){
                window.openAppConfirm({
                    title: 'Remove Image',
                    message: 'Are you sure you want to remove this image?',
                    buttonText: 'Yes, Remove',
                    buttonClass: 'btn btn-sm btn-danger',
                    onConfirm: function() {
                        document.getElementById('remove_thumbnail').value = 1;
                        e.target.closest('.position-relative').remove();
                    }
                });
            }
        });
    });

// REMOVE GALLERY IMAGE
    document.addEventListener('click', function(e){
        let btn = e.target.closest(
            '.remove-gallery-image'
        );
        if(!btn){
            return;
        }
        if(!confirm('Remove image?')){
            return;
        }
        let image = btn.dataset.image;
        let input = document.getElementById(
            'existing_gallery_images'
        );
        let images = JSON.parse(input.value);
        images = images.filter(
            item => item !== image
        );
        input.value = JSON.stringify(images);
        btn.closest('.gallery-image-item')
            .remove();
    });
</script>

@endsection