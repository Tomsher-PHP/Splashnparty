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
                <label>Image</label>
                <input type="file"
                    name="image"
                    class="form-control form-control-sm">

                @if($isEdit && !empty($event->image))
                <div class="mb-2">
                    <img src="{{ asset($event->image) }}"
                        class="img-thumbnail"
                        style="max-height:120px">

                    <div class="form-check mt-1">
                        <input type="checkbox"
                            name="remove_event_image"
                            value="1"
                            class="form-check-input">

                        <label class="form-check-label">
                            Remove Image
                        </label>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-6">
                <label>
                    Banner Image
                </label>

                <input type="file"
                    name="banner_image"
                    class="form-control form-control-sm">

                @if($isEdit && !empty($event->banner_image))
                <div class="mb-2">
                    <img src="{{ asset($event->banner_image) }}"
                        class="img-thumbnail"
                        style="max-height:120px">

                    <div class="form-check mt-1">
                        <input type="checkbox"
                            name="remove_banner_image"
                            value="1"
                            class="form-check-input">

                        <label class="form-check-label">
                            Remove Banner
                        </label>
                    </div>
                </div>
                @endif
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

                    {{-- Branch --}}
                    <div class="col-md-4">
                        <label>Branch</label>
                        <select
                            name="branch_details[{{ $index }}][branch_id]"
                            class="form-select branch-select"
                            required>
                            <option value="">Select Branch</option>

                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ ($detail['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Title --}}
                    <div class="col-md-4">
                        <label>Title</label>
                        <input type="text"
                            name="branch_details[{{ $index }}][title]"
                            class="form-control form-control-sm"
                            value="{{ $detail['title'] ?? '' }}">
                    </div>

                    {{-- Image --}}
                    <div class="col-md-4">
                        <label>Image</label>
                        <input type="file"
                            name="branch_details[{{ $index }}][image]"
                            class="form-control form-control-sm">
                        @if(!empty($detail['image']))
                        <div class="mb-2">

                            <img src="{{ asset($detail['image']) }}"
                                class="img-thumbnail"
                                style="max-height:100px">

                            <div class="form-check mt-1">

                                <input type="checkbox"
                                    class="form-check-input"
                                    name="branch_details[{{ $index }}][remove_image]"
                                    value="1">

                                <label class="form-check-label">
                                    Remove Image
                                </label>

                            </div>

                        </div>
                        @endif

                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <label>Description</label>
                        <textarea
                            rows="4"
                            class="form-control"
                            name="branch_details[{{ $index }}][description]">{{ $detail['description'] ?? '' }}</textarea>
                    </div>

                    {{-- FEATURES --}}
                    <div class="col-md-12">

                        <div class="d-flex justify-content-between mb-2">
                            <h6>Features</h6>

                            <button type="button"
                                class="btn btn-primary btn-sm addFeatureBtn">
                                Add Feature
                            </button>
                        </div>

                        <div class="features-wrapper">

                            @foreach(($detail['features'] ?? []) as $featureIndex => $feature)

                            <div class="feature-item border rounded p-2 mb-2">

                                <div class="row g-2 p-6">

                                    <div class="col-md-3">
                                        <label>Icon</label>
                                        <input type="file"
                                            class="form-control form-control-sm"
                                            name="branch_details[{{ $index }}][features][{{ $featureIndex }}][icon]">
                                        @if(!empty($feature['icon']))
                                        <div class="mb-2">

                                            <img src="{{ asset($feature['icon']) }}"
                                                width="60">

                                            <div class="form-check">

                                                <input type="checkbox"
                                                    name="branch_details[{{ $index }}][features][{{ $featureIndex }}][remove_icon]"
                                                    value="1"
                                                    class="form-check-input">

                                                <label class="form-check-label">
                                                    Remove
                                                </label>

                                            </div>

                                        </div>
                                        @endif


                                    </div>

                                    <div class="col-md-4">
                                        <label>Title</label>

                                        <input type="text"
                                            class="form-control form-control-sm"
                                            name="branch_details[{{ $index }}][features][{{ $featureIndex }}][title]"
                                            value="{{ $feature['title'] ?? '' }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Subtitle</label>

                                        <input type="text"
                                            class="form-control form-control-sm"
                                            name="branch_details[{{ $index }}][features][{{ $featureIndex }}][subtitle]"
                                            value="{{ $feature['subtitle'] ?? '' }}">
                                    </div>

                                    <div class="col-md-1 text-end">
                                        <button type="button"
                                            class="btn btn-danger btn-sm removeFeatureBtn">
                                            Remove
                                        </button>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Content</label>

                                        <textarea
                                            rows="3"
                                            class="form-control"
                                            name="branch_details[{{ $index }}][features][{{ $featureIndex }}][content]">{{ $feature['content'] ?? '' }}</textarea>
                                    </div>



                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- MIDDLE BANNER --}}
                    <div class="col-md-12">

                        <label>Middle Banner</label>

                        @if(!empty($detail['middle_banner']))
                        <div class="mb-2">

                            <img src="{{ asset($detail['middle_banner']) }}"
                                class="img-thumbnail"
                                style="max-height:120px">

                            <div class="form-check mt-1">

                                <input type="checkbox"
                                    name="branch_details[{{ $index }}][remove_middle_banner]"
                                    value="1"
                                    class="form-check-input">

                                <label class="form-check-label">
                                    Remove Banner
                                </label>

                            </div>

                        </div>
                        @endif

                        <input type="file"
                            name="branch_details[{{ $index }}][middle_banner]"
                            class="form-control form-control-sm">

                    </div>

                    {{-- GALLERY --}}
                    <div class="col-md-12">

                        <div class="d-flex justify-content-between mb-2">
                            <h6>Gallery</h6>

                            <button type="button"
                                class="btn btn-primary btn-sm addGalleryBtn">
                                Add Gallery
                            </button>
                        </div>

                        <div class="gallery-wrapper">

                            @foreach(($detail['galleries'] ?? []) as $galleryIndex => $gallery)

                            <div class="gallery-item border rounded p-2 mb-2">

                                <div class="row g-2">

                                    <div class="col-md-6">
                                        <label>Title</label>

                                        <input type="text"
                                            class="form-control form-control-sm"
                                            name="branch_details[{{ $index }}][gallery][{{ $galleryIndex }}][title]"
                                            value="{{ $gallery['title'] ?? '' }}">
                                    </div>

                                    <div class="col-md-5">
                                        <input type="file"
                                            class="form-control form-control-sm"
                                            name="branch_details[{{ $index }}][gallery][{{ $galleryIndex }}][image]">
                                        @if(!empty($gallery['image']))
                                        <div class="mb-2">
                                            <img src="{{ asset($gallery['image']) }}"
                                                width="80">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    name="branch_details[{{ $index }}][gallery][{{ $galleryIndex }}][remove_image]"
                                                    value="1"
                                                    class="form-check-input">

                                                <label class="form-check-label">
                                                    Remove
                                                </label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <button type="button"
                                            class="btn btn-danger btn-sm removeGalleryBtn">
                                            X
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Description</label>

                                        <textarea
                                            rows="2"
                                            class="form-control"
                                            name="branch_details[{{ $index }}][gallery][{{ $galleryIndex }}][description]">{{ $gallery['description'] ?? '' }}</textarea>
                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- Sort Order --}}
                    <div class="col-md-6">
                        <label>Sort Order</label>

                        <input type="number"
                            class="form-control form-control-sm"
                            name="branch_details[{{ $index }}][sort_order]"
                            value="{{ $detail['sort_order'] ?? 0 }}">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label>Status</label>

                        <select
                            class="form-select form-select-sm"
                            name="branch_details[{{ $index }}][status]">

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>
                    </div>

                    <div class="col-md-12 text-end">

                        <button type="button"
                            class="btn btn-danger btn-sm removeBranchBtn">
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
        .addEventListener('click', function() {

            let wrapper =
                document.getElementById('branchDetailsWrapper');

            let div = document.createElement('div');

            div.className =
                'branch-detail-item border rounded p-3 mb-3';

            div.innerHTML = `
        <div class="row g-3">

            <div class="col-md-4">
                <label>Branch</label>

                <select
                    name="branch_details[${branchIndex}][branch_id]"
                    class="form-select branch-select">

                    ${generateBranchOptions()}

                </select>
            </div>

            <div class="col-md-4">
                <label>Title</label>

                <input type="text"
                    class="form-control form-control-sm"
                    name="branch_details[${branchIndex}][title]">
            </div>

            <div class="col-md-4">
                <label>Image</label>

                <input type="file"
                    class="form-control form-control-sm"
                    name="branch_details[${branchIndex}][image]">
            </div>

            <div class="col-md-12">
                <label>Description</label>

                <textarea
                    rows="4"
                    class="form-control"
                    name="branch_details[${branchIndex}][description]"></textarea>
            </div>

            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h6>Features</h6>

                    <button type="button"
                        class="btn btn-primary btn-sm addFeatureBtn">
                        Add Feature
                    </button>
                </div>

                <div class="features-wrapper"></div>
            </div>

            <div class="col-md-12">
                <label>Middle Banner</label>

                <input type="file"
                    class="form-control form-control-sm"
                    name="branch_details[${branchIndex}][middle_banner]">
            </div>

            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h6>Gallery</h6>

                    <button type="button"
                        class="btn btn-primary btn-sm addGalleryBtn">
                        Add Gallery
                    </button>
                </div>

                <div class="gallery-wrapper"></div>
            </div>

            <div class="col-md-6">
                <label>Sort Order</label>

                <input type="number"
                    value="0"
                    class="form-control form-control-sm"
                    name="branch_details[${branchIndex}][sort_order]">
            </div>

            <div class="col-md-6">
                <label>Status</label>

                <select
                    class="form-select form-select-sm"
                    name="branch_details[${branchIndex}][status]">

                    <option value="1">
                        Active
                    </option>

                    <option value="0">
                        Inactive
                    </option>

                </select>
            </div>

            <div class="col-md-12 text-end">
                <button type="button"
                    class="btn btn-danger btn-sm removeBranchBtn">
                    Remove
                </button>
            </div>

        </div>
    `;

            wrapper.appendChild(div);

            branchIndex++;

            refreshBranchDropdowns();
        });

    document.addEventListener('change', function(e) {
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

    document.addEventListener('click', function(e) {
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
                onConfirm: function() {
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
        .addEventListener('submit', function(e) {
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

    document.addEventListener('DOMContentLoaded', function() {
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

    document.addEventListener('click', function(e) {

        if (e.target.closest('.addFeatureBtn')) {

            let branchItem =
                e.target.closest('.branch-detail-item');

            let wrapper =
                branchItem.querySelector('.features-wrapper');

            let branchIndex =
                Array.from(
                    document.querySelectorAll('.branch-detail-item')
                ).indexOf(branchItem);

            let featureIndex =
                wrapper.querySelectorAll('.feature-item').length;

            wrapper.insertAdjacentHTML('beforeend', `
                <div class="feature-item border rounded p-2 mb-2">

                    <div class="row g-2 p-6">

                        <div class="col-md-3">
                            <input type="file"
                                class="form-control form-control-sm"
                                name="branch_details[${branchIndex}][features][${featureIndex}][icon]">
                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                placeholder="Title"
                                class="form-control form-control-sm"
                                name="branch_details[${branchIndex}][features][${featureIndex}][title]">
                        </div>

                        <div class="col-md-4">
                            <input type="text"
                                placeholder="Subtitle"
                                class="form-control form-control-sm"
                                name="branch_details[${branchIndex}][features][${featureIndex}][subtitle]">
                        </div>

                        <div class="col-md-1">
                            <button type="button"
                                class="btn btn-danger btn-sm removeFeatureBtn">
                                Remove
                            </button>
                        </div>

                        <div class="col-md-12">
                            <textarea
                                rows="2"
                                class="form-control"
                                placeholder="Content"
                                name="branch_details[${branchIndex}][features][${featureIndex}][content]"></textarea>
                        </div>

                    </div>

                </div>
            `);
        }
    });

    document.addEventListener('click', function(e) {

        if (e.target.closest('.addGalleryBtn')) {

            let branchItem =
                e.target.closest('.branch-detail-item');

            let wrapper =
                branchItem.querySelector('.gallery-wrapper');

            let branchIndex =
                Array.from(
                    document.querySelectorAll('.branch-detail-item')
                ).indexOf(branchItem);

            let galleryIndex =
                wrapper.querySelectorAll('.gallery-item').length;

            wrapper.insertAdjacentHTML('beforeend', `
            <div class="gallery-item border rounded p-2 mb-2">

                <div class="row g-2 p-6">

                    <div class="col-md-6">
                        <input type="text"
                            placeholder="Title"
                            class="form-control form-control-sm"
                            name="branch_details[${branchIndex}][gallery][${galleryIndex}][title]">
                    </div>
                    <div class="col-md-5">
                        <input type="file"
                            class="form-control form-control-sm"
                            name="branch_details[${branchIndex}][gallery][${galleryIndex}][image]">
                    </div>

                    <div class="col-md-1">
                        <button type="button"
                            class="btn btn-danger btn-sm removeGalleryBtn">
                            X
                        </button>
                    </div>

                    <div class="col-md-12">
                        <textarea
                            rows="2"
                            class="form-control"
                            placeholder="Description"
                            name="branch_details[${branchIndex}][gallery][${galleryIndex}][description]"></textarea>
                    </div>

                    

                </div>

            </div>
        `);
        }
    });

    document.addEventListener('click', function(e) {

        if (e.target.closest('.removeFeatureBtn')) {
            e.target.closest('.feature-item').remove();
        }

        if (e.target.closest('.removeGalleryBtn')) {
            e.target.closest('.gallery-item').remove();
        }

    });
</script>

@endsection