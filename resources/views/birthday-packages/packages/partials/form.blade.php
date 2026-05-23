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
                    class="form-select">

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
                    class="form-control"
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
                    class="form-control"
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
                    class="form-control"
                    value="{{ old('price', $package->price ?? '') }}">

            </div>

            {{-- IMAGE --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Thumbnail Image
                </label>

                <input type="file"
                    name="image"
                    class="form-control"
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
                    class="form-control">

                @if(!empty($package?->banner_image))

                <div class="mt-2">

                    <img src="{{ asset($package->banner_image) }}"
                        width="160"
                        class="rounded border">

                </div>

                @endif

            </div>

            {{-- HIGHLIGHTED DESCRIPTION --}}
            <div class="col-md-12">

                <label class="form-label fw-semibold">
                    Highlighted Description
                </label>

                <textarea name="highlighted_description"
                    rows="3"
                    class="form-control">{{ old('highlighted_description', $package->highlighted_description ?? '') }}</textarea>

            </div>

            {{-- DESCRIPTION --}}
            <div class="col-md-12">

                <label class="form-label fw-semibold">
                    Description
                </label>

                <textarea name="description"
                    rows="5"
                    class="form-control">{{ old('description', $package->description ?? '') }}</textarea>

            </div>

            {{-- SORT ORDER --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Sort Order
                </label>

                <input type="number"
                    name="sort_order"
                    class="form-control"
                    value="{{ old('sort_order', $package->sort_order ?? 0) }}">

            </div>

            {{-- STATUS --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Status
                </label>

                <select name="status"
                    class="form-select">

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
</script>

@endsection