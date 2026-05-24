<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                Branch Details
            </h6>
            <div>
                <a href="{{ route('branches.index') }}"
                    class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Title
                </label>
                <input type="text"
                       name="title"
                       class="form-control form-control-sm"
                       value="{{ old('title', $model->title ?? '') }}">
            </div>

            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Sort Order
                </label>
                <input type="number"
                       name="sort_order"
                       class="form-control form-control-sm"
                       value="{{ old('sort_order', $model->sort_order ?? 0) }}">
            </div>

            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Location Link
                </label>
                <input type="url"
                       name="location_link"
                       class="form-control form-control-sm"
                       value="{{ old('location_link', $model->location_link ?? '') }}">
            </div>

            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Status
                </label>
                <select name="status"
                        class="form-select">
                    <option value="1"
                        {{ old('status', $model->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ old('status', $model->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="col-md-12 mb-20">
                <label class="form-label fw-semibold">
                    Description
                </label>
                <textarea name="description"
                          rows="5"
                          class="form-control">{{ old('description', $model->description ?? '') }}</textarea>
            </div>

            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Branch Image
                </label>
                <input type="file"
                       name="image"
                       class="form-control form-control-sm">
            

                @if(!empty($model?->image))
                    <div class="position-relative d-inline-block branch-image-wrapper thumb-image-wrapper mt-20">
                        <img src="{{ asset($model->image) }}"
                                class="rounded border thumb-image"
                                >
                        <div class="branch-image-overlay thumb-image-overlay remove-branch-image">
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
        
            <div class="col-md-3 mb-20">
                <label class="form-label fw-semibold">
                    Phone
                </label>
                <input type="text"
                    name="phone"
                    class="form-control form-control-sm"
                    value="{{ old('phone', $model->phone ?? '') }}">
            </div>

            <div class="col-md-3 mb-20">
                <label class="form-label fw-semibold">
                    Email
                </label>
                <input type="email"
                    name="email"
                    class="form-control form-control-sm"
                    value="{{ old('email', $model->email ?? '') }}">
            </div>

            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Address
                </label>
                <textarea name="address"
                        rows="4"
                        class="form-control">{{ old('address', $model->address ?? '') }}</textarea>
            </div>

            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Working Hours
                </label>
                <textarea name="working_hours"
                        rows="4"
                        class="form-control">{{ old('working_hours', $model->working_hours ?? '') }}</textarea>
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

<script>
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-branch-image')) {
            if (!confirm('Remove branch image?')) {
                return;
            }

            document.getElementById('remove_image').value = 1;
            e.target.closest('.branch-image-wrapper').remove();
        }
    });
</script>