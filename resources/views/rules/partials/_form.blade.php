<div class="row g-3">
    <div class="col-md-12">
        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
        <input
            type="text"
            name="title"
            class="form-control form-control-sm @error('title') is-invalid @enderror"
            value="{{ old('title', $rule->title ?? '') }}"
            required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- CONTENT GROUP (QUILL EDITOR) -->
    <div class="col-md-12" id="content_group">
        <label class="form-label fw-semibold">Content</label>
        <div class="quill-editor-wrapper">
            <div class="quill-editor" data-input="content_editor">
                {!! old('content', $rule->content ?? '') !!}
            </div>
        </div>
        <textarea name="content" id="content_editor" class="d-none">{{ old('content', $rule->content ?? '') }}</textarea>
        @error('content')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- IMAGE GROUP -->
    <div class="col-md-12" id="image_group">
        <label class="form-label fw-semibold">Image File</label>
        <input
            type="file"
            name="image"
            accept="image/png,image/jpeg,image/webp,image/svg+xml"
            class="form-control form-control-sm @error('image') is-invalid @enderror">
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if(isset($isEdit) && $isEdit && $rule->image)
        <div class="mt-3">
            <div class="position-relative d-inline-block">
                <img src="{{ asset($rule->image) }}" width="180" class="rounded border thumb-image">
                <div class="image-overlay remove-image" style="position: absolute; top: 5px; right: 5px;">
                    <button type="button" class="btn btn-danger btn-sm rounded-circle">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
        <input type="hidden" name="remove_image" id="remove_image" value="0">
        @endif
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control form-control-sm @error('status') is-invalid @enderror" required>
            <option value="1" {{ old('status', $rule->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $rule->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Show in Email <span class="text-danger">*</span></label>
        <select name="show_in_email" class="form-control form-control-sm @error('show_in_email') is-invalid @enderror" required>
            <option value="1" {{ old('show_in_email', $rule->show_in_email ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('show_in_email', $rule->show_in_email ?? 0) == 0 ? 'selected' : '' }}>No</option>
        </select>
        @error('show_in_email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Sort Order</label>
        <input
            type="number"
            name="sort_order"
            min="0"
            class="form-control form-control-sm @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $rule->sort_order ?? 0) }}">
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Quill Editor
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
        document.getElementById('content_editor').value = quill.root.innerHTML;
    });

    // Handle remove image action (in Edit view)
    const removeBtn = document.querySelector('.remove-image');
    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove the current image?')) {
                document.getElementById('remove_image').value = 1;
                removeBtn.closest('.position-relative').remove();
            }
        });
    }
});
</script>
@endsection
