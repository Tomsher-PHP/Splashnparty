<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                {{ $title }}
            </h6>
            <div>
                <a href="{{ route('rental-categories.index') }}"
                    class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="row g-3">

                {{-- TITLE --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Title
                    </label>
                    <input type="text"
                        name="title"
                        id="title"
                        class="form-control form-control-sm"
                        value="{{ old('title', $category->title ?? '') }}"
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
                        value="{{ old('slug', $category->slug ?? '') }}"
                        required>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label>Sort Order</label>
                <input type="number"
                    name="sort_order"
                    class="form-control form-control-sm"
                    value="{{ old('sort_order', $category->sort_order ?? 0) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="1"
                        {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>
        </div>
        <div class="text-end mt-5">
            <button type="submit"
                class="btn btn-sm btn-primary-600">
                {{ $buttonText }}
            </button>
            <button type="reset"
                class="btn btn-sm btn-outline-secondary">
                Cancel
            </button>
        </div>
    </div>
</div>

@section('script')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const title = document.getElementById('title');

        const slug = document.getElementById('slug');

        title.addEventListener('keyup', function () {

            let generatedSlug = this.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '') // REMOVE SPECIAL CHARS
                .replace(/\s+/g, '-')         // SPACE TO DASH
                .replace(/-+/g, '-');         // REMOVE MULTIPLE DASHES

            slug.value = generatedSlug;

        });

    });

</script>

@endsection