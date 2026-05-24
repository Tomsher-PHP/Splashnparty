<div class="row g-3">

    {{-- TITLE --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Title
        </label>

        <input type="text"
            name="title"
            class="form-control form-control-sm"
            value="{{ old('title', $isEdit ? $cake->title : '') }}"
            required>

    </div>

    {{-- PRODUCT CODE --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Product Code
        </label>

        <input type="text"
            name="product_code"
            class="form-control form-control-sm"
            value="{{ old('product_code', $isEdit ? $cake->product_code : '') }}"
            required>

    </div>

    {{-- PRICE --}}
    <div class="col-md-4">

        <label class="form-label fw-semibold">
            Price
        </label>

        <input type="number"
            step="0.01"
            name="price"
            class="form-control form-control-sm"
            value="{{ old('price', $isEdit ? $cake->price : '') }}">

    </div>

    {{-- SORT ORDER --}}
    <div class="col-md-4">

        <label class="form-label fw-semibold">
            Sort Order
        </label>

        <input type="number"
            name="sort_order"
            class="form-control form-control-sm"
            value="{{ old('sort_order', $isEdit ? $cake->sort_order : 0) }}">

    </div>

    {{-- STATUS --}}
    <div class="col-md-4">

        <label class="form-label fw-semibold">
            Status
        </label>

        <select name="status"
            class="form-select">

            <option value="1"
                {{ old('status', $isEdit ? $cake->status : 1) == 1 ? 'selected' : '' }}>
                Active
            </option>

            <option value="0"
                {{ old('status', $isEdit ? $cake->status : 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>

    </div>

    {{-- THUMBNAIL IMAGE --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Thumbnail Image
        </label>

        <input type="file"
            name="thumbnail_image"
            class="form-control form-control-sm"
            {{ $isEdit ? '' : 'required' }}>

        @if($isEdit && $cake->thumbnail_image)

        <div class="mt-3">

            <div class="position-relative d-inline-block">

                <img src="{{ asset($cake->thumbnail_image) }}"
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

    {{-- GALLERY IMAGES --}}
    <div class="col-md-6">

        <label class="form-label fw-semibold">
            Gallery Images
        </label>

        <input type="file"
            name="gallery_images[]"
            class="form-control form-control-sm"
            multiple>
        <div class="d-flex gap-2 mt-3">
            {{-- EXISTING GALLERY --}}
            @if($isEdit && !empty($cake->gallery_images))
                @foreach($cake->gallery_images as $image)
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
                    value='@json($cake->gallery_images)'>
            @endif
        </div>
    </div>

    {{-- DESCRIPTION --}}
    <div class="col-md-12">
        <label class="form-label fw-semibold">
            Description
        </label>

        <div class="quill-editor"
            data-input="description_editor"
            style="height:250px;">
            {!! old('description', $isEdit ? $cake->description : '') !!}
        </div>
        <textarea name="description"
            id="description_editor"
            class="d-none">{{ old('description', $isEdit ? $cake->description : '') }}</textarea>
    </div>
</div>

<style>
.image-overlay{
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.45);
    display:flex;
    align-items:center;
    justify-content:center;
    opacity:0;
    transition:.2s;
    border-radius:.5rem;
}

.position-relative:hover .image-overlay{
    opacity:1;
}
</style>

@section('script')

<script>

document.addEventListener('DOMContentLoaded', function () {

    // QUILL
    const quill = new Quill('.quill-editor', {

        theme: 'snow',

        modules: {

            toolbar: [

                [{ header: [1, 2, 3, false] }],

                ['bold', 'italic', 'underline'],

                [{ list: 'ordered' }, { list: 'bullet' }],

                [{ color: [] }, { background: [] }],

                ['link'],

                ['clean']
            ]
        }
    });

    quill.on('text-change', function () {

        document.getElementById(
            'description_editor'
        ).value = quill.root.innerHTML;
    });

    // REMOVE THUMBNAIL
    document.addEventListener('click', function(e){
        if(e.target.closest('.remove-thumbnail-image')){
            if(!confirm('Remove image?')){
                return;
            }
            document.getElementById('remove_thumbnail').value = 1;
            e.target.closest('.position-relative').remove();
        }
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

});

</script>

@endsection