@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Edit Image Gallery</h6>
            <div>
                <a href="{{ route('image-gallery.index') }}"
                    class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('image-gallery.update', $image_gallery) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('gallery.image.partials.form', ['gallery'=> $image_gallery])
            <div class="mt-4" style="text-align: right;">
                <button type="submit"
                        class="btn btn-sm btn-primary-600 d-inline-flex align-items-center">
                    Update Gallery
                </button>
                <button type="reset"
                        class="btn btn-sm btn-outline-secondary">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')

<script>

document.addEventListener('DOMContentLoaded', function () {

    let gallery = document.getElementById('sortable-edit-gallery');

    if (gallery) {

        new Sortable(gallery, {

            animation: 200,

            ghostClass: 'sortable-ghost',

            chosenClass: 'sortable-chosen',

            onEnd: function () {

                let images = [];

                gallery.querySelectorAll('.sortable-item').forEach(function(item) {

                    images.push(item.dataset.image);

                });

                fetch("{{ route('image-gallery.sort') }}", {

                    method: "POST",

                    headers: {

                        "Content-Type": "application/json",

                        "X-CSRF-TOKEN": "{{ csrf_token() }}"

                    },

                    body: JSON.stringify({

                        id: "{{ $image_gallery->id }}",

                        images: images

                    })

                });

            }

        });

    }

});

</script>
// Image delete function
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const sortableGallery = document.getElementById('sortable-edit-gallery');

        const hiddenInput = document.getElementById('existing-images-input');

        if (!sortableGallery || !hiddenInput) {
            return;
        }

        // UPDATE HIDDEN FIELD
        function updateImageOrder() {

            let orderedImages = [];

            sortableGallery.querySelectorAll('.sortable-item').forEach(function(item) {

                orderedImages.push(
                    item.getAttribute('data-image')
                );

            });

            hiddenInput.value = JSON.stringify(orderedImages);

            console.log(hiddenInput.value);

        }

        // SORTABLE
        new Sortable(sortableGallery, {

            animation: 200,

            handle: '.drag-handle',

            onEnd: function () {

                updateImageOrder();

            }

        });

        // REMOVE IMAGE
        document.addEventListener('click', function(e) {

            const removeBtn = e.target.closest('.remove-image-btn');

            if (!removeBtn) return;

            const item = removeBtn.closest('.sortable-item');

            window.openAppConfirm({

                title: 'Remove Image',

                message: 'Are you sure you want to remove this image?',

                buttonText: 'Yes, Remove',

                buttonClass: 'btn btn-sm btn-danger',

                onConfirm: function() {

                    item.remove();

                    updateImageOrder();

                }

            });

        });

        // IMPORTANT
        // UPDATE BEFORE FORM SUBMIT
        document.querySelector('form').addEventListener('submit', function () {

            updateImageOrder();

        });

        // INITIAL LOAD
        updateImageOrder();

    });

</script>
@endsection