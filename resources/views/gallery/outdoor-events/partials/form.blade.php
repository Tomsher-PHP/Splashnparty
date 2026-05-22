<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                {{ isset($outdoor_event) ? 'Edit Outdoor Event' : 'Create Outdoor Event' }}
            </h6>
            <div>
                <a href="{{ route('outdoor-events.index') }}"
                    class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- UPLOAD --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">
                Upload Images
            </label>
            <input type="file"
                   name="images[]"
                   class="form-control"
                   multiple>
        </div>

        {{-- EXISTING IMAGES --}}
        @if(isset($outdoor_event) && !empty($outdoor_event->images))
            <div class="mb-3">
                <label class="form-label fw-semibold d-block">
                    Existing Images
                </label>

                <div class="row g-3 sortable-outdoor-gallery"
                     id="sortable-outdoor-gallery">
                    @foreach($outdoor_event->images as $image)
                        <div class="col-auto sortable-item"
                             data-image="{{ $image }}">
                            <div class="position-relative outdoor-image-card">
                                {{-- DELETE --}}
                                <button type="button"
                                        class="btn btn-sm btn-danger rounded-circle outdoor-remove-btn"
                                        onclick="removeOutdoorImage(this)">

                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                <img src="{{ asset($image) }}"
                                     class="rounded-3 border outdoor-image">
                            </div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden"
                       name="existing_images"
                       id="existing_images">
                <small class="text-muted d-block mt-2">
                    Drag images to reorder
                </small>
            </div>
        @endif

        
        <!-- SEO COMPONENT -->
        @include('components.seo-fields', [
            'model' => $outdoor_event ?? null
        ])

        <div class="text-end mt-4">
            <button type="submit"
                    class="btn btn-primary-600">
                {{ isset($outdoor_event) ? 'Update' : 'Save' }}
            </button>
            <button type="reset"
                    class="btn btn-sm btn-outline-secondary">
                Cancel
            </button>
        </div>
    </div>
</div>

@section('style')
<style>
.outdoor-image-card {
    position: relative;
}



.outdoor-remove-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.sortable-ghost {
    opacity: .4;
}

.sortable-chosen {
    transform: scale(.97);
}
</style>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let gallery = document.getElementById('sortable-outdoor-gallery');
    if (gallery) {
        new Sortable(gallery, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onSort: updateOutdoorImagesInput
        });
        updateOutdoorImagesInput();
    }
});

function updateOutdoorImagesInput() {
    let images = [];
    document.querySelectorAll('#sortable-outdoor-gallery .sortable-item')
        .forEach(function(item) {
            images.push(item.dataset.image);
        });

    document.getElementById('existing_images').value =
        JSON.stringify(images);
}

function removeOutdoorImage(button) {
    window.openAppConfirm({
        title: 'Remove Image',
        message: 'Are you sure you want to remove this image?',
        buttonText: 'Yes, Remove',
        buttonClass: 'btn btn-sm btn-danger',
        onConfirm: function () {
            button.closest('.sortable-item').remove();
            updateOutdoorImagesInput();
        }
    });
}
</script>
@endsection