<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            Branch Details
        </h5>
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

            <div class="col-md-12 mb-20">
                <label class="form-label fw-semibold">
                    Branch Image
                </label>
                <input type="file"
                       name="image"
                       class="form-control form-control-sm">
            </div>

            @if(!empty($model?->image))
                <div class="col-md-12 mb-20">
                    <div class="position-relative d-inline-block branch-image-wrapper">
                        <img src="{{ asset($model->image) }}"
                             class="rounded border"
                             style="width:220px;height:160px;object-fit:cover;opacity:.7;">
                        <div class="branch-image-overlay remove-branch-image">
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
                </div>
            @endif
        

            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Phone
                </label>
                <input type="text"
                    name="phone"
                    class="form-control form-control-sm"
                    value="{{ old('phone', $model->phone ?? '') }}">
            </div>

            <div class="col-md-6 mb-20">
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
                    class="btn btn-primary-600">
                {{ $buttonText }}
            </button>
        </div>
    </div>
</div>

<style>
.branch-image-wrapper {
    overflow: hidden;
}

.branch-image-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: .2s ease;
    background: rgba(0,0,0,.35);
}

.branch-image-wrapper:hover .branch-image-overlay {
    opacity: 1;
}

</style>

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