<div class="row g-3">
    <div class="col-md-12">
        <label class="form-label fw-semibold">Title</label>
        <input
            type="text"
            name="title"
            class="form-control form-control-sm"
            value="{{ old('title', $newsUpdate->title ?? '') }}">
    </div>

    <div class="col-md-12">
        <label class="form-label fw-semibold">Description</label>
        <div class="quill-editor-wrapper">
            <div class="quill-editor"
                data-input="description_editor">
                {!! old('description', $newsUpdate->description ?? '') !!}
            </div>
        </div>
        <textarea name="description"
            id="description_editor"
            class="d-none">{{ old('description', $newsUpdate->description ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Image</label>
        <input
            type="file"
            name="image"
            class="form-control form-control-sm">

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
        <label class="form-label fw-semibold">Publish Date</label>
        <input
            type="date"
            name="publish_date"
            class="form-control form-control-sm"
            value="{{ old('publish_date', $newsUpdate->publish_date ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>

        <select name="status" class="form-control form-control-sm">

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

<!-- SEO COMPONENT -->
@include('components.seo-fields', [
'model' => $newsUpdate ?? null
])

@section('style')
<style>
    .quill-editor-wrapper {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--input-form-light);
        background: #fff;
        transition: border-color 0.2s ease;
    }
    
    .quill-editor-wrapper:focus-within {
        border-color: var(--primary-600) !important;
    }

    .quill-editor-wrapper .ql-toolbar.ql-snow {
        border: none !important;
        border-bottom: 1px solid var(--input-form-light) !important;
        background: #f8fafc;
        padding: 8px 12px;
    }

    .quill-editor-wrapper .ql-container.ql-snow {
        border: none !important;
        font-family: inherit;
        font-size: 14px;
        height: 300px !important;
        min-height: 250px !important;
    }

    .quill-editor-wrapper .ql-editor {
        height: 300px !important;
        min-height: 250px !important;
        padding: 16px;
    }
</style>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // QUILL
    const quill = new Quill('.quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ font: [] }, { header: [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ indent: '-1' }, { indent: '+1' }],
                [{ align: [] }],
                ['clean']
            ]
        }
    });

    quill.on('text-change', function () {
        document.getElementById('description_editor').value = quill.root.innerHTML;
    });

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
});
</script>
@endsection