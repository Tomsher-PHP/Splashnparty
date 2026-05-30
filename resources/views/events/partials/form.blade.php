@php
$isEdit = isset($event);

$details = old(
    'branch_details',
    $isEdit
        ? $event->branchDetails->toArray()
        : [[]]
);
@endphp

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
               {{ $isEdit ? $event->title : 'Add Event' }}
            </h6>
            <div>
                <a href="{{ route('events.index') }}"
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

                <label>
                    Title
                </label>

                <input type="text"
                    name="title"
                    id="title"
                    class="form-control  form-control-sm"
                    value="{{ old('title', $event->title ?? '') }}">

            </div>

            <div class="col-md-6">

                <label>
                    Slug
                </label>

                <input type="text"
                    name="slug"
                    id="slug"
                    class="form-control  form-control-sm"
                    value="{{ old('slug', $event->slug ?? '') }}">

            </div>
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Status
                </label>
                <select name="status"
                        class="form-select form-select-sm">
                    <option value="1"
                        {{ old('status', $event->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ old('status', $event->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Sort Order
                </label>
                <input type="number"
                       name="sort_order"
                       class="form-control form-control-sm"
                       value="{{ old('sort_order', $event->sort_order ?? 0) }}">
            </div>
            <div class="col-md-6">
                <label>
                    Image
                </label>
                <input type="file"
                    name="image"
                    class="form-control form-control-sm">
            </div>

            <div class="col-md-6">
                <label>
                    Banner Image
                </label>

                <input type="file"
                    name="banner_image"
                    class="form-control form-control-sm">
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
            Branch Details
        </div>

        <button type="button"
            class="btn btn-primary btn-sm"
            id="addBranchBtn">
            Add More
        </button>

    </div>

    <div class="card-body">

        <div id="branchDetailsWrapper">

            @foreach($details as $index => $detail)

                <div class="branch-detail-item border rounded p-3 mb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Branch</label>
                            <select name="branch_details[{{ $index }}][branch_id]"
                                class="form-select branch-select"
                                required>
                                <option value="">
                                    Select Branch
                                </option>

                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ ($detail['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Weekday Price</label>
                            <input type="text"
                                name="branch_details[{{ $index }}][weekday_price]"
                                class="form-control form-control-sm"
                                value="{{ $detail['weekday_price'] ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label>Weekend Price</label>
                            <input type="text"
                                name="branch_details[{{ $index }}][weekend_price]"
                                class="form-control form-control-sm"
                                value="{{ $detail['weekend_price'] ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <label>Sort Order</label>
                            <input type="number"
                                name="branch_details[{{ $index }}][sort_order]"
                                class="form-control form-control-sm"
                                value="{{ $detail['sort_order'] ?? 0 }}">
                        </div>

                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="branch_details[{{ $index }}][status]"
                                class="form-select form-select-sm">
                                <option value="1">
                                    Active
                                </option>
                                <option value="0">
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Highlight Description</label>
                            <textarea
                                name="branch_details[{{ $index }}][highlighted_description]"
                                rows="3"
                                class="form-control form-control-sm">{{ $detail['highlighted_description'] ?? '' }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label>Description</label>
                            <textarea
                                name="branch_details[{{ $index }}][description]"
                                rows="3"
                                class="form-control form-control-sm">{{ $detail['description'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-light-danger btn-sm removeBranchBtn badge bg-danger">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-3 text-end">
    <button class="btn btn-primary">
        {{ $isEdit ? 'Update' : 'Save' }}
    </button>
</div>

@section('script')

<script>

    let branchIndex =
        document.querySelectorAll('.branch-detail-item').length;

    const allBranches = @json($branches);

    function getSelectedBranches() {

        let selected = [];

        document.querySelectorAll('.branch-select').forEach(select => {

            if (select.value) {
                selected.push(select.value);
            }

        });

        return selected;
    }

    function generateBranchOptions(selectedValue = '') {

        let selectedBranches = getSelectedBranches();

        let options = `
            <option value="">
                Select Branch
            </option>
        `;

        allBranches.forEach(branch => {

            if (
                !selectedBranches.includes(branch.id.toString()) ||
                branch.id.toString() === selectedValue.toString()
            ) {

                options += `
                    <option value="${branch.id}"
                        ${branch.id == selectedValue ? 'selected' : ''}>
                        ${branch.title}
                    </option>
                `;
            }

        });

        return options;
    }

    function refreshBranchDropdowns() {
        const selectedBranches = getSelectedBranches();
        document.querySelectorAll('.branch-select').forEach(select => {
            let currentValue = select.value;
            let options = `
                <option value="">
                    Select Branch
                </option>
            `;
            allBranches.forEach(branch => {
                // SHOW IF:
                // - NOT SELECTED
                // - OR CURRENT VALUE OF THIS SELECT
                if (
                    !selectedBranches.includes(branch.id.toString()) ||
                    branch.id.toString() === currentValue
                ) {

                    options += `
                        <option value="${branch.id}"
                            ${branch.id.toString() === currentValue ? 'selected' : ''}>
                            ${branch.title}
                        </option>
                    `;
                }

            });

            select.innerHTML = options;
        });

        toggleAddMoreButton();
    }

    function toggleAddMoreButton() {
        const totalBranches = allBranches.length;
        const selectedBranches = getSelectedBranches();
        const addBtn =
            document.getElementById('addBranchBtn');

        // HIDE IF ONLY ONE BRANCH EXISTS
        if (totalBranches <= 1) {
            addBtn.style.display = 'none';
            return;
        }

        // HIDE IF NO MORE BRANCHES LEFT
        if (selectedBranches.length >= totalBranches) {
            addBtn.style.display = 'none';
        } else {
            addBtn.style.display = 'inline-block';
        }
    }

    document.getElementById('addBranchBtn')
        .addEventListener('click', function () {
        let wrapper =
            document.getElementById(
                'branchDetailsWrapper'
            );

        let div = document.createElement('div');
        div.classList.add(
            'branch-detail-item',
            'border',
            'rounded',
            'p-3',
            'mb-3'
        );

        div.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <label>
                        Branch
                    </label>
                    <select
                        name="branch_details[${branchIndex}][branch_id]"
                        class="form-select branch-select"
                        required>
                        ${generateBranchOptions()}
                    </select>

                </div>

                <div class="col-md-3">

                    <label>
                        Weekday Price
                    </label>

                    <input type="text"
                        name="branch_details[${branchIndex}][weekday_price]"
                        class="form-control form-control-sm">

                </div>

                <div class="col-md-3">

                    <label>
                        Weekend Price
                    </label>

                    <input type="text"
                        name="branch_details[${branchIndex}][weekend_price]"
                        class="form-control form-control-sm">

                </div>

                <div class="col-md-6">
                    <label>
                        Sort Order
                    </label>
                    <input type="number"
                        name="branch_details[${branchIndex}][sort_order]"
                        class="form-control form-control-sm"
                        value="0">
                </div>

                <div class="col-md-6">
                    <label>
                        Status
                    </label>
                    <select
                        name="branch_details[${branchIndex}][status]"
                        class="form-select form-select-sm">
                        <option value="1">
                            Active
                        </option>
                        <option value="0">
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>
                        Highlight Description
                    </label>

                    <textarea
                        name="branch_details[${branchIndex}][highlighted_description]"
                        rows="3"
                        class="form-control form-control-sm"></textarea>
                </div>

                <div class="col-md-6">
                    <label>
                        Description
                    </label>
                    <textarea
                        name="branch_details[${branchIndex}][description]"
                        rows="3"
                        class="form-control form-control-sm"></textarea>
                </div>

                <div class="col-md-12 text-end">
                    <button type="button"
                        class="btn btn-light-danger btn-sm removeBranchBtn badge bg-danger">
                        Remove
                    </button>
                </div>
            </div>
        `;

        wrapper.appendChild(div);
        branchIndex++;
        refreshBranchDropdowns();
        toggleAddMoreButton();
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('branch-select')) {
            refreshBranchDropdowns();
            toggleAddMoreButton();
        }
    });

    /*document.addEventListener('click', function (e) {
        if (e.target.closest('.removeBranchBtn')) {
            e.target.closest('.branch-detail-item')
                .remove();
            refreshBranchDropdowns();
            toggleAddMoreButton();
        }
    });*/

    document.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.removeBranchBtn');

        if (removeBtn) {
            const items = document.querySelectorAll('.branch-detail-item');

            // KEEP AT LEAST ONE BLOCK
            if (items.length <= 1) {
                window.openAppConfirm({
                    title: 'Branch Required',
                    message: 'At least one branch detail is required.',
                    buttonText: 'OK',
                    buttonClass: 'btn btn-sm btn-primary'
                });

                return;
            }

            window.openAppConfirm({
                title: 'Remove Branch',
                message: 'Are you sure you want to remove this branch detail?',
                buttonText: 'Yes, Remove',
                buttonClass: 'btn btn-sm btn-danger',
                onConfirm: function () {
                    removeBtn
                        .closest('.branch-detail-item')
                        .remove();
                    refreshBranchDropdowns();
                    toggleAddMoreButton();
                }
            });
        }
    });

    document.querySelector('form')
        .addEventListener('submit', function (e) {
        let values = [];
        let duplicate = false;
        let hasBranch = false;

        document.querySelectorAll('.branch-select')
            .forEach(select => {

            if (select.value) {
                hasBranch = true;
                if (values.includes(select.value)) {
                    duplicate = true;
                } else {
                    values.push(select.value);
                }
            }

        });
        if (!hasBranch) {
            e.preventDefault();

            window.openAppConfirm({
                title: 'Branch Required',
                message: 'Please select at least one branch before submitting.',
                buttonText: 'OK',
                buttonClass: 'btn btn-sm btn-primary'
            });

            return;
        }

        if (duplicate) {
            e.preventDefault();
            window.openAppConfirm({
                title: 'Duplicate Branches',
                message: 'Duplicate branches are not allowed.',
                buttonText: 'OK',
                buttonClass: 'btn btn-sm btn-primary'
            });
        }
    });

    /*document.addEventListener('DOMContentLoaded', function () {
        refreshBranchDropdowns();
        toggleAddMoreButton();
    });*/

    document.addEventListener('DOMContentLoaded', function () {
        // FIX EDIT FORM INITIAL VALUES
        setTimeout(() => {
            refreshBranchDropdowns();
        }, 100);
    });

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