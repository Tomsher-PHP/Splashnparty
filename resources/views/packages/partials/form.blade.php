<div class="row g-3">
    {{-- TITLE --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Title
        </label>

        <input type="text"
            name="title"
            class="form-control form-control-sm"
            value="{{ old('title', $isEdit ? $package->title : '') }}"
            required>
    </div>

    {{-- IMAGE --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Image
        </label>

        <input type="file"
            name="image"
            class="form-control form-control-sm">

        @if($isEdit && !empty($package->image))
            <div class="mt-2">
                <img src="{{ asset($package->image) }}"
                    width="120"
                    class="rounded border">
            </div>
        @endif
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

    {{-- CHILD COUNT FOR FREE ADULT --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Child Count for Free Adult Ticket
        </label>

        <input type="number"
            name="child_count_for_free_adult"
            min="0"
            class="form-control form-control-sm"
            value="{{ old('child_count_for_free_adult', $isEdit ? $package->child_count_for_free_adult : 0) }}">
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

    {{-- DESCRIPTION --}}
    <div class="col-md-12">
        <label class="form-label fw-semibold">
            Description
        </label>
        <div class="quill-editor-wrapper">
            <div class="quill-editor" data-input="description">
                {!! old('description', $isEdit ? $package->description : '') !!}
            </div>
            <textarea name="description" id="description" class="d-none">{{ old('description', $isEdit ? $package->description : '') }}</textarea>
        </div>
    </div>

</div>

@section('script')
<script>
    $(document).ready(function() {
        // Initialize Quill Editors for wysiwyg fields
        $('.quill-editor').each(function() {
            const editor = this;
            if (editor.dataset.quillInit) return;
            
            const inputId = $(editor).data('input');
            const textarea = document.getElementById(inputId);
            
            const quill = new Quill(editor, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ font: [] }, { header: [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ color: [] }, { background: [] }],
                        
                        ['blockquote', 'code-block'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        [{ indent: '-1' }, { indent: '+1' }],
                        [{ align: [] }],
                        ['clean']
                    ]
                }
            });

            quill.on('text-change', function() {
                if (textarea) {
                    textarea.value = quill.root.innerHTML;
                }
            });
            
            editor.dataset.quillInit = "1";
        });
    });
</script>
@endsection