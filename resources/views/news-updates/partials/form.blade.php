<div class="row g-3">
    <div class="col-md-12">
        <label>Title</label>
        <input
            type="text"
            name="title"
            class="form-control"
            value="{{ old('title', $newsUpdate->title ?? '') }}">
    </div>

    <div class="col-md-12">
        <label>Description</label>
        <textarea
            name="description"
            rows="8"
            class="form-control">{{ old('description', $newsUpdate->description ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label>Image</label>
        <input
            type="file"
            name="image"
            class="form-control">

        @if($isEdit && $newsUpdate->image)

        <div class="mt-3">

            <div class="position-relative d-inline-block">

                <img src="{{ asset($newsUpdate->image) }}"
                    width="140"
                    class="rounded border thumb-image">

                <div class="image-overlay remove-image">

                    <button type="button"
                        class="btn btn-danger rounded-circle">

                        <i class="ri-delete-bin-line"></i>

                    </button>

                </div>

            </div>

        </div>

        <input type="hidden"
            name="remove_image"
            id="remove_image"
            value="0">

        @endif
    </div>

    <div class="col-md-6">
        <label>Publish Date</label>
        <input
            type="date"
            name="publish_date"
            class="form-control"
            value="{{ old('publish_date', $newsUpdate->publish_date ?? '') }}">
    </div>

    <div class="col-md-6">
        <label>Status</label>

        <select name="status" class="form-control">

            <option value="1"
                {{ old('status', $newsUpdate->status ?? 1) == 1 ? 'selected' : '' }}>
                Active
            </option>

            <option value="0"
                {{ old('status', $newsUpdate->status ?? 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>
    </div>
</div>

<hr>

<!-- SEO  COMPONENT -->
@include('components.seo-fields', [
'model' => $newsUpdate ?? null
])

@section('script')
<script>
 // REMOVE IMAGE
document.addEventListener('click', function(e){
    if(e.target.closest('.remove-image')){
        if(!confirm('Remove image?')){
            return;
        }
        document.getElementById('remove_image').value = 1;
        e.target.closest('.position-relative').remove();
    }
});
</script>
@endsection