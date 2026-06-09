<div class="row g-3">
    {{-- TITLE --}}
    <div class="col-md-12">
        <label class="form-label fw-semibold">
            Title
        </label>

        <input type="text"
            name="title"
            class="form-control form-control-sm"
            value="{{ old('title', $isEdit ? $package->title : '') }}"
            required>
    </div>

    {{-- BRANCH --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Branch
        </label>

        <select name="branch_id" class="form-select form-select-sm">
            <option value="">Select Branch</option>

            @foreach($branches as $branch)
                <option value="{{ $branch->id }}"
                    {{ old('branch_id', $isEdit ? $package->branch_id : '') == $branch->id ? 'selected' : '' }}>
                    {{ $branch->title }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- FREE ADULT --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Free One Adult With Each Child
        </label>

        <select name="free_adult_with_child"
            class="form-select form-select-sm">

            <option value="1"
                {{ old('free_adult_with_child', $isEdit ? $package->free_adult_with_child : 0) == 1 ? 'selected' : '' }}>
                Yes
            </option>

            <option value="0"
                {{ old('free_adult_with_child', $isEdit ? $package->free_adult_with_child : 0) == 0 ? 'selected' : '' }}>
                No
            </option>

        </select>
    </div>

    {{-- WITH FOOD --}}
    <div class="col-12">
        <strong class="mb-2 mt-3">With Food Prices</strong>
    </div>

    <div class="col-md-3">
        <label class="form-label">Child Weekday</label>
        <input type="number"
            step="0.01"
            name="child_weekday_price_with_food"
            class="form-control form-control-sm"
            value="{{ old('child_weekday_price_with_food', $isEdit ? $package->child_weekday_price_with_food : '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Adult Weekday</label>
        <input type="number"
            step="0.01"
            name="adult_weekday_price_with_food"
            class="form-control form-control-sm"
            value="{{ old('adult_weekday_price_with_food', $isEdit ? $package->adult_weekday_price_with_food : '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Child Weekend</label>
        <input type="number"
            step="0.01"
            name="child_weekend_price_with_food"
            class="form-control form-control-sm"
            value="{{ old('child_weekend_price_with_food', $isEdit ? $package->child_weekend_price_with_food : '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Adult Weekend</label>
        <input type="number"
            step="0.01"
            name="adult_weekend_price_with_food"
            class="form-control form-control-sm"
            value="{{ old('adult_weekend_price_with_food', $isEdit ? $package->adult_weekend_price_with_food : '') }}">
    </div>

    {{-- WITHOUT FOOD --}}
    <div class="col-12">
        <strong class="mb-2 mt-3">Without Food Prices</strong>
    </div>

    <div class="col-md-3">
        <label class="form-label">Child Weekday</label>
        <input type="number"
            step="0.01"
            name="child_weekday_price_without_food"
            class="form-control form-control-sm"
            value="{{ old('child_weekday_price_without_food', $isEdit ? $package->child_weekday_price_without_food : '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Adult Weekday</label>
        <input type="number"
            step="0.01"
            name="adult_weekday_price_without_food"
            class="form-control form-control-sm"
            value="{{ old('adult_weekday_price_without_food', $isEdit ? $package->adult_weekday_price_without_food : '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Child Weekend</label>
        <input type="number"
            step="0.01"
            name="child_weekend_price_without_food"
            class="form-control form-control-sm"
            value="{{ old('child_weekend_price_without_food', $isEdit ? $package->child_weekend_price_without_food : '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Adult Weekend</label>
        <input type="number"
            step="0.01"
            name="adult_weekend_price_without_food"
            class="form-control form-control-sm"
            value="{{ old('adult_weekend_price_without_food', $isEdit ? $package->adult_weekend_price_without_food : '') }}">
    </div>

    

    {{-- START DATE --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Start Date
        </label>

        <input type="date"
            name="start_date"
            class="form-control form-control-sm"
            value="{{ old('start_date', $isEdit && $package->start_date ? $package->start_date->format('Y-m-d') : '') }}">
    </div>

    {{-- END DATE --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            End Date
        </label>

        <input type="date"
            name="end_date"
            class="form-control form-control-sm"
            value="{{ old('end_date', $isEdit && $package->end_date ? $package->end_date->format('Y-m-d') : '') }}">
    </div>

    {{-- DAYS --}}
    @php
        $selectedDays = old(
            'days',
            $isEdit ? ($package->days ?? []) : []
        );

        $days = [
            'Sunday' => 'Sunday',
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
        ];
    @endphp

    <div class="col-md-12">
        <label class="form-label fw-semibold">
            Days
        </label>
        <div class="d-flex flex-wrap gap-2">
            @foreach($days as $key => $label)
                <div class="mb-2">
                    <div class="form-check d-flex gap-1 align-items-center">
                        <input class="form-check-input"
                            type="checkbox"
                            name="days[]"
                            value="{{ $key }}"
                            id="day_{{ strtolower($key) }}"
                            {{ in_array($key, $selectedDays) ? 'checked' : '' }}>

                        <label class="form-check-label mx-4"
                            for="day_{{ strtolower($key) }}">
                            {{ $label }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        <small class="text-muted">
            ( If no days are selected, the package will be considered valid for all days. )
        </small>

    </div>

    {{-- SORT ORDER --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Sort Order
        </label>

        <input type="number"
            name="sort_order"
            class="form-control form-control-sm"
            value="{{ old('sort_order', $isEdit ? $package->sort_order : 0) }}">
    </div>

    {{-- STATUS --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Status
        </label>

        <select name="status"
            class="form-select form-select-sm">

            <option value="1"
                {{ old('status', $isEdit ? $package->status : 1) == 1 ? 'selected' : '' }}>
                Active
            </option>

            <option value="0"
                {{ old('status', $isEdit ? $package->status : 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>
    </div>

</div>