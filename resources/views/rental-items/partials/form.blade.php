<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                {{ $rental_item->title ?? 'New Item',"Create Item" }}
            </h6>
            <div>
                <a href="{{ route('rental-items.index') }}"
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
                    Category
                </label>
                <select name="rental_category_id"
                    class="form-select"
                    required>
                    <option value="">
                        Select Category
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('rental_category_id', $rental_item->rental_category_id ?? '') == $category->id ? 'selected' : '' }}>
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

                @if(!empty($rental_item?->image))
                    <div class="position-relative d-inline-block thumb-image-wrapper mt-20">
                        <img src="{{ asset($rental_item->image) }}"
                                class="rounded border thumb-image"
                                >
                        <div class="thumb-image-overlay remove-item-image">
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
                    class="form-control"
                    value="{{ old('title', $rental_item->title ?? '') }}"
                    required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Price
                </label>
                <input type="text" 
                    name="price"
                    class="form-control"
                    value="{{ old('price', $rental_item->price ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Status
                </label>
                <select name="status"
                    class="form-select">
                    <option value="1"
                        {{ old('status', $rental_item->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ old('status', $rental_item->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Sort Order
                </label>
                <input type="number"
                    name="sort_order"
                    class="form-control"
                    value="{{ old('sort_order', $rental_item->sort_order ?? 0) }}">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label fw-semibold">
                    Description
                </label>
                <textarea name="description"
                    rows="4"
                    class="form-control">{{ old('description', $rental_item->description ?? '') }}</textarea>
            </div>
        </div>
        <div class="text-end mt-5">
            <button type="submit"
                class="btn btn-sm btn-primary-600">
                {{ $rental_item?->id ? 'Update':"Save" }}
            </button>
            <button type="reset"
                class="btn btn-sm btn-outline-secondary">
                Cancel
            </button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item-image')) {
            if (!confirm('Remove image?')) {
                return;
            }

            document.getElementById('remove_image').value = 1;
            e.target.closest('.thumb-image-wrapper').remove();
        }
    });
</script>