<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                {{ $cafe_menu->title ?? 'New Cafe Menu',"Create Menu" }}
            </h6>
            <div>
                <a href="{{ route('cafe-menus.index') }}"
                    class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Branches
                </label>
                <select name="branch_ids[]"
                    class="form-control select2"
                    multiple
                    required>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ in_array($branch->id, old('branch_ids', $cafe_menu->branch_ids ?? [])) ? 'selected' : '' }}>
                            {{ $branch->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Category
                </label>
                <select name="cafe_menu_category_id"
                    class="form-select form-select-sm"
                    required>
                    <option value="">
                        Select Category
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('cafe_menu_category_id', $cafe_menu->cafe_menu_category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Image
                </label>
                <input type="file"
                       name="image"
                       class="form-control form-control-sm">

                @if(!empty($cafe_menu?->image))
                    <div class="position-relative d-inline-block thumb-image-wrapper mt-20">
                        <img src="{{ asset($cafe_menu->image) }}"
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

             

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Title
                </label>
                <input type="text"
                    name="title"
                    class="form-control form-control-sm"
                    value="{{ old('title', $cafe_menu->title ?? '') }}"
                    required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Price
                </label>
                <input type="number"
                    step="0.01"
                    name="price"
                    class="form-control form-control-sm"
                    value="{{ old('price', $cafe_menu->price ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">
                    Status
                </label>
                <select name="status"
                    class="form-select form-select-sm">
                    <option value="1"
                        {{ old('status', $cafe_menu->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ old('status', $cafe_menu->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">
                    Sort Order
                </label>
                <input type="number"
                    name="sort_order"
                    class="form-control form-control-sm"
                    value="{{ old('sort_order', $cafe_menu->sort_order ?? 0) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Menu Type
                </label>
                <select name="menu_type"
                    class="form-select form-select-sm">
                    <option value="adult"
                        {{ old('menu_type', $cafe_menu->menu_type ?? '') == 'adult' ? 'selected' : '' }}>
                        Adult
                    </option>
                    <option value="kid"
                        {{ old('menu_type', $cafe_menu->menu_type ?? '') == 'kid' ? 'selected' : '' }}>
                        Kid
                    </option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Food Type
                </label>
                <select name="food_type" class="form-select form-select-sm">
                    <option value="veg"
                        {{ old('food_type', $cafe_menu->food_type ?? '') == 'veg' ? 'selected' : '' }}>
                        Veg
                    </option>
                    <option value="non_veg"
                        {{ old('food_type', $cafe_menu->food_type ?? '') == 'non_veg' ? 'selected' : '' }}>
                        Non Veg
                    </option>
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label fw-semibold">
                    Description
                </label>
                <textarea name="description"
                    rows="4"
                    class="form-control">{{ old('description', $cafe_menu->description ?? '') }}</textarea>
            </div>
        </div>
        <div class="text-end mt-5">
            <button type="submit"
                class="btn btn-sm btn-primary-600">
                {{ $cafe_menu?->id ? 'Update':"Save" }}
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