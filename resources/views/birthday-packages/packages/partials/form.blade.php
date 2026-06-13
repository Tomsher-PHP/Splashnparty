@php
$isEdit = isset($package);
@endphp

<div class="card">

    <div class="card-header">

        <h5 class="mb-0">

            {{ $isEdit ? 'Edit' : 'Create' }} Birthday Package

        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">

            {{-- BRANCH --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Branch
                </label>

                <select name="branch_id"
                    class="form-select form-select-sm">

                    <option value="">
                        Select Branch
                    </option>

                    @foreach($branches as $branch)

                    <option value="{{ $branch->id }}"
                        {{ old('branch_id', $package->branch_id ?? '') == $branch->id ? 'selected' : '' }}>

                        {{ $branch->title }}

                    </option>

                    @endforeach

                </select>

            </div>

            {{-- TITLE --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Title
                </label>

                <input type="text"
                    name="title"
                    id="title"
                    class="form-control form-control-sm"
                    value="{{ old('title', $package->title ?? '') }}"
                    required>

            </div>

            {{-- SLUG --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Slug
                </label>

                <input type="text"
                    name="slug"
                    id="slug"
                    class="form-control form-control-sm"
                    value="{{ old('slug', $package->slug ?? '') }}"
                    required>

            </div>

            {{-- PRICE --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Price
                </label>

                <input type="text"
                    name="price"
                    class="form-control form-control-sm"
                    value="{{ old('price', $package->price ?? '') }}">

            </div>

            {{-- IMAGE --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Thumbnail Image
                </label>

                <input type="file"
                    name="image"
                    class="form-control form-control-sm"
                    {{ $isEdit ? '' : 'required' }}>

                @if(!empty($package?->image))

                <div class="mt-2">

                    <img src="{{ asset($package->image) }}"
                        width="120"
                        class="rounded border">

                </div>

                @endif

            </div>

            {{-- BANNER IMAGE --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Banner Image
                </label>
                <input type="file"
                    name="banner_image"
                    class="form-control form-control-sm">
                @if(!empty($package?->banner_image))
                <div class="mt-2">
                    <img src="{{ asset($package->banner_image) }}"
                        width="160"
                        class="rounded border">
                </div>
                @endif
            </div>

            {{-- MINIMUM KIDS --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Minimum Kids
                </label>
                <input type="text"
                    name="minimum_kids"
                    class="form-control form-control-sm"
                    placeholder="Mon-Thurs: 10 kids / Fri-Sun: 15 kids"
                    value="{{ old('minimum_kids', $package->minimum_kids ?? '') }}">
            </div>

            {{-- DURATION --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Duration
                </label>
                <input type="text"
                    name="duration"
                    class="form-control form-control-sm"
                    placeholder="1 hour 45 minutes"
                    value="{{ old('duration', $package->duration ?? '') }}">
            </div>

            {{-- WEEKDAY RATE --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Weekday Rate
                </label>
                <input type="text"
                    name="weekday_rate"
                    class="form-control form-control-sm"
                    value="{{ old('weekday_rate', $package->weekday_rate ?? '') }}">
            </div>

            {{-- WEEKEND RATE --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Weekend Rate
                </label>
                <input type="text"
                    name="weekend_rate"
                    class="form-control form-control-sm"
                    value="{{ old('weekend_rate', $package->weekend_rate ?? '') }}">
            </div>

            {{-- SORT ORDER --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Sort Order
                </label>
                <input type="number"
                    name="sort_order"
                    class="form-control form-control-sm"
                    value="{{ old('sort_order', $package->sort_order ?? 0) }}">
            </div>

            {{-- STATUS --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Status
                </label>

                <select name="status"
                    class="form-select form-select-sm">
                    <option value="1"
                        {{ old('status', $package->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('status', $package->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            {{-- DESCRIPTION --}}
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    Description
                </label>
                <div class="quill-editor-wrapper">
                    <div class="quill-editor" data-input="description">
                        {!! old('description', $package->description ?? '') !!}
                    </div>
                    <textarea name="description" id="description" class="d-none">{{ old('description', $package->description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer text-end">
        <button type="submit"
            class="btn btn-primary">
            {{ $isEdit ? 'Update' : 'Save' }}
        </button>
    </div>
</div>

@section('script')

<script>
    document.getElementById('title').addEventListener('keyup', function() {
        let slug = this.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

        document.getElementById('slug').value = slug;
    });

    $(document).ready(function() {
        // Initialize Quill Editors for wysiwyg fields
        $('.quill-editor').each(function() {
            const editor = this;
            if (editor.dataset.quillInit) return;
            
            const inputId = $(editor).data('input');
            const textarea = document.getElementById(inputId);
            
            const quill = new Quill(editor, {
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

            quill.on('text-change', function() {
                if (textarea) {
                    textarea.value = quill.root.innerHTML;
                }
            });
            
            editor.dataset.quillInit = "1";
        });
    });
</script>

@endsection