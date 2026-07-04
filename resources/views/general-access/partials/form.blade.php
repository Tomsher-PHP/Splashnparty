<div class="row">
    <div class="col-md-6 mb-3">
        <label>Title</label>
        <input type="text"
                name="title"
                class="form-control"
                value="{{ old('title', $generalAccess->title ?? '') }}"
                required>
    </div>

    <div class="col-md-3 mb-3">
        <label>Weekday Price</label>
        <input type="number"
                step="0.01"
                name="weekday_price"
                value="{{ old('weekday_price', $generalAccess->weekday_price ?? '') }}"
                class="form-control">
    </div>

    <div class="col-md-3 mb-3">
        <label>Weekend Price</label>
        <input type="number"
                step="0.01"
                name="weekend_price"
                value="{{ old('weekend_price', $generalAccess->weekend_price ?? '') }}"
                class="form-control">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
            Branch
        </label>
        <select name="branch_id"
            class="form-select form-select-sm"
            required>
            <option value="">
                Select Branch
            </option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}"
                    {{ old('branch_id', $generalAccess->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                    {{ $branch->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 mb-3">
        <label>Sort Order</label>
        <input type="number"
                name="sort_order"
                value="{{ old('sort_order', $generalAccess->sort_order ?? 0) }}"
                class="form-control">
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label fw-semibold">
            Status
        </label>
        <select name="status"
            class="form-select form-select-sm">
            <option value="1"
                {{ old('status', $generalAccess->status ?? 1) == 1 ? 'selected' : '' }}>
                Active
            </option>
            <option value="0"
                {{ old('status', $generalAccess->status ?? 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
    </div>

</div>
