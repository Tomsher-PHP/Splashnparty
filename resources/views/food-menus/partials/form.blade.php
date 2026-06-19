<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
               {{ isset($foodMenu) ? 'Edit' : 'Create' }} Food Menu
            </h6>
            <div>
                <a href="{{ route('food-menus.index') }}"
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
                    Title
                </label>

                <input type="text"
                    name="title"
                    class="form-control form-control-sm"
                    value="{{ old('title', $foodMenu->title ?? '') }}"
                    required>

            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Branch</label>
                <select
                    name="branch_ids[]"
                    class="form-control select2"
                    multiple>

                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ in_array($branch->id, old('branch_ids', isset($foodMenu) ? ($foodMenu->branch_ids ?? []) : [])) ? 'selected' : '' }}>
                            {{ $branch->title }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    Type
                </label>
                <select name="type"
                    class="form-select form-select-sm">
                    <option value="adult"
                        {{ old('type', $foodMenu->type ?? '') == 'adult' ? 'selected' : '' }}>
                        Adult
                    </option>
                    <option value="kid"
                        {{ old('type', $foodMenu->type ?? '') == 'kid' ? 'selected' : '' }}>
                        Kid
                    </option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Category
                </label>
                <select name="food_menu_category_id"
                    class="form-select form-select-sm"
                    required>
                    <option value="">
                        Select Category
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('food_menu_category_id', $foodMenu->food_menu_category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    Food Type
                </label>

                <select name="food_type"
                    class="form-select form-select-sm">

                    <option value="veg"
                        {{ old('food_type', $foodMenu->food_type ?? '') == 'veg' ? 'selected' : '' }}>
                        Veg
                    </option>

                    <option value="non-veg"
                        {{ old('food_type', $foodMenu->food_type ?? '') == 'non-veg' ? 'selected' : '' }}>
                        Non Veg
                    </option>

                </select>

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Price
                </label>

                <input type="text"
                    name="price"
                    class="form-control form-control-sm"
                    value="{{ old('price', $foodMenu->price ?? '') }}">

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Image
                </label>

                <input type="file"
                    name="image"
                    class="form-control form-control-sm">

                @if(!empty($foodMenu?->image))
                    <div class="position-relative d-inline-block thumb-image-wrapper mt-20">
                        <img src="{{ asset($foodMenu->image) }}"
                                class="rounded border thumb-image"
                                >
                        <div class="thumb-image-overlay remove-menu-image">
                            <button type="button"
                                    class="btn btn-danger rounded-circle">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden"
                            name="remove_image"
                            id="remove_image"
                            value="0">
                @endif

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Sort Order
                </label>

                <input type="number"
                    name="sort_order"
                    class="form-control form-control-sm"
                    value="{{ old('sort_order', $foodMenu->sort_order ?? 0) }}">

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Status
                </label>

                <select name="status"
                    class="form-select form-select-sm">

                    <option value="1"
                        {{ old('status', $foodMenu->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('status', $foodMenu->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

            </div>

            <div class="col-md-12">

                <label class="form-label fw-semibold">
                    Description
                </label>
                <textarea name="description"
                    rows="5"
                    class="form-control">{{ old('description', $foodMenu->description ?? '') }}</textarea>

            </div>

        </div>

    </div>

    <div class="card-footer text-end">
        <button type="submit" class="btn btn-sm btn-primary">
            {{ isset($foodMenu) ? 'Update' : 'Save' }}
        </button>
        <button type="reset" class="btn btn-sm btn-outline-secondary">
            Cancel
        </button>
    </div>

</div>

@section('script')
<script>
$(function () {
    $('.select2').select2({
        width: '100%',
        placeholder: 'Select Branches'
    });
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-menu-image')) {
        if (!confirm('Remove image?')) {
            return;
        }

        document.getElementById('remove_image').value = 1;
        e.target.closest('.thumb-image-wrapper').remove();
    }
});
</script>
@endsection