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
                            {{ isset($foodMenu) && in_array($branch->id, $foodMenu->branch_ids ?? []) ? 'selected' : '' }}>
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

                    <option value="adult">
                        Adult
                    </option>

                    <option value="kid">
                        Kid
                    </option>

                </select>

            </div>

            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    Food Type
                </label>

                <select name="food_type"
                    class="form-select form-select-sm">

                    <option value="veg">
                        Veg
                    </option>

                    <option value="non-veg">
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

                    <option value="1">
                        Active
                    </option>

                    <option value="0">
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

        <button type="submit"
            class="btn btn-primary">

            {{ isset($foodMenu) ? 'Update' : 'Save' }}

        </button>

    </div>

</div>