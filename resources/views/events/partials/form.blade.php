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

            <div class="col-md-6">
                <label>
                    Heading
                </label>
                <input type="text"
                    name="heading"
                    class="form-control form-control-sm"
                    value="{{ old('heading', $event->heading ?? '') }}">
            </div>

            <div class="col-md-12">
                <label>
                    Description
                </label>
                <textarea
                    name="description"
                    rows="3"
                    class="form-control">{{ old('description', $event->description ?? '') }}</textarea>
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
            <div class="branch-detail-item border rounded p-3 mb-3 bg-light bg-opacity-25">

                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-neutral-200 dark:border-neutral-700">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-600 branch-index-badge">#{{ $index + 1 }}</span>
                        <strong class="branch-header-title text-neutral-800 dark:text-neutral-200">
                            @php
                                $selectedBranch = $branches->firstWhere('id', $detail['branch_id'] ?? null);
                            @endphp
                            {{ $selectedBranch ? 'Branch Detail: ' . $selectedBranch->title : 'Branch Detail: (Unassigned)' }}
                        </strong>
                    </div>
                </div>

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
                        <input type="hidden"
                            name="branch_details[{{ $index }}][old_image]"
                            value="{{ $detail['image'] }}">
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
                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <div class="quill-editor-wrapper">
                            <div class="quill-editor"
                                data-input="description_{{ $index }}">
                                {!! $detail['description'] ?? '' !!}
                            </div>
                        </div>
                        <textarea
                            name="branch_details[{{ $index }}][description]"
                            id="description_{{ $index }}"
                            class="d-none">{{ $detail['description'] ?? '' }}</textarea>
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

                        <div class="row g-2 mb-3 mt-2">
                            <div class="col-md-6">
                                <label>Features Section Title</label>
                                <input type="text"
                                    name="branch_details[{{ $index }}][features_title]"
                                    class="form-control form-control-sm"
                                    value="{{ $detail['features_title'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label>Features Section Description</label>
                                <textarea
                                    name="branch_details[{{ $index }}][features_description]"
                                    rows="2"
                                    class="form-control ">{{ $detail['features_description'] ?? '' }}</textarea>
                            </div>
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
                                        <input type="hidden"
                                            name="branch_details[{{ $index }}][features][{{ $featureIndex }}][old_icon]"
                                            value="{{ $feature['icon'] }}">
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
                        <input type="hidden"
                            name="branch_details[{{ $index }}][old_middle_banner]"
                            value="{{ $detail['middle_banner'] }}">
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

                        <div class="mt-2">
                            <label>Middle Banner Link</label>
                            <input type="text"
                                name="branch_details[{{ $index }}][middle_banner_link]"
                                class="form-control form-control-sm"
                                value="{{ $detail['middle_banner_link'] ?? '' }}">
                        </div>

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

                        <div class="row g-2 mb-3 mt-2">
                            <div class="col-md-6">
                                <label>Gallery Section Title</label>
                                <input type="text"
                                    name="branch_details[{{ $index }}][gallery_title]"
                                    class="form-control form-control-sm"
                                    value="{{ $detail['gallery_title'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label>Gallery Section Description</label>
                                <textarea
                                    name="branch_details[{{ $index }}][gallery_description]"
                                    rows="2"
                                    class="form-control ">{{ $detail['gallery_description'] ?? '' }}</textarea>
                            </div>
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
                                        <input type="hidden"
                                            name="branch_details[{{ $index }}][gallery][{{ $galleryIndex }}][old_image]"
                                            value="{{ $gallery['image'] }}">
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

                            <option value="1" {{ ($detail['status'] ?? 1) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0" {{ ($detail['status'] ?? 1) == 0 ? 'selected' : '' }}>
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

@include('components.seo-fields', ['model' => $event ?? null])

{{-- SCHEMA MARKUP --}}
<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white py-3">
        <div class="card-title mb-0">Schema Markup</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <label class="form-label fw-semibold">
                    Schema Markup
                </label>
                <textarea name="schema"
                    rows="10"
                    class="form-control"
                    placeholder="Enter schema markup (e.g. JSON format)">{{ old('schema', $event->schema ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- FAQ SELECTION --}}
@php
    $selectedFaqIds = old('faq_selection.faq_ids', $event->faq_selection['faq_ids'] ?? []);
    $selectedQuestions = old('faq_selection.questions', $event->faq_selection['questions'] ?? []);
@endphp
<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white py-3">
        <div class="card-title mb-0">FAQ Section Settings</div>
    </div>
    <div class="card-body">
        <p class="text-muted mb-20 text-xs">Manage the FAQ categories and select specific questions to display on this event page.</p>
        
        <div class="row mb-20">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">FAQ Section Title</label>
                <input type="text" name="faq_title" class="form-control form-control-sm" placeholder="Enter FAQ section title (e.g. Frequently Asked Questions)" value="{{ old('faq_title', $event->faq_title ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">FAQ Section Description</label>
                <textarea name="faq_description" rows="2" class="form-control" placeholder="Enter FAQ section description">{{ old('faq_description', $event->faq_description ?? '') }}</textarea>
            </div>
        </div>

        <div class="faq-selection-widget">
            <!-- Category Selector Control -->
            <div class="faq-selector-controls d-flex align-items-center gap-12 mb-20 p-16 border rounded-8 bg-neutral-50">
                <div class="flex-grow-1">
                    <select class="form-select form-select-sm" id="faq-category-selector">
                        <option value="">-- Choose an FAQ Category to Add --</option>
                        @foreach ($allFaqs as $faq)
                            @php
                                $isAlreadyAdded = in_array($faq->id, $selectedFaqIds);
                            @endphp
                            <option value="{{ $faq->id }}" {{ $isAlreadyAdded ? 'disabled class=d-none' : '' }}>
                                {{ $faq->category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-sm btn-primary-600 d-flex align-items-center gap-8 py-8" id="btn-add-faq-category" style="white-space: nowrap;">
                    <i class="ri-add-line"></i> Add Category
                </button>
            </div>

            <!-- Active Categories Container -->
            <div id="active-faq-categories-container" class="row g-3">
                <!-- Empty State when nothing is added -->
                <div class="faq-empty-state col-12 text-center py-32 border border-dashed rounded-8 text-neutral-400 bg-white {{ count($selectedFaqIds) > 0 ? 'd-none' : '' }}">
                    <i class="ri-question-line text-2xl d-block mb-8 text-neutral-400"></i>
                    <span class="text-xs">No FAQ categories added to this event page yet. Choose a category from the selector above to add it.</span>
                </div>

                @foreach ($allFaqs as $faq)
                    @if (in_array($faq->id, $selectedFaqIds))
                        <div class="col-md-6 faq-category-card-wrapper" data-category-id="{{ $faq->id }}">
                            <div class="card border rounded-8 overflow-hidden shadow-none mb-12">
                                <!-- Category Header -->
                                <div class="card-header bg-neutral-50 p-16 d-flex align-items-center justify-content-between cursor-pointer faq-card-accordion-header" data-target="#faq-card-body-{{ $faq->id }}">
                                    <div class="d-flex align-items-center gap-12">
                                        <input type="hidden" name="faq_selection[faq_ids][]" value="{{ $faq->id }}">
                                        <h6 class="text-sm fw-semibold text-secondary-light mb-0">{{ $faq->category }}</h6>
                                        <span class="badge bg-neutral-200 text-neutral-600 rounded-pill text-xxs px-8 py-4">
                                            {{ count(collect($faq->details)->filter(fn($q) => ($q['status'] ?? 1) == 1)) }} Questions
                                        </span>
                                        <span class="selected-count-badge badge bg-primary-100 text-primary-600 rounded-pill text-xxs px-8 py-4 d-none">
                                            0 Selected
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-12">
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-icon remove-faq-category-btn" data-category-id="{{ $faq->id }}" title="Remove Category">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        <i class="ri-arrow-down-s-line text-lg toggle-icon accordion-arrow" style="transition: transform 0.2s;"></i>
                                    </div>
                                </div>

                                <!-- Questions List -->
                                <div id="faq-card-body-{{ $faq->id }}" class="card-body p-16 bg-white d-none">
                                    <div class="d-flex align-items-center justify-content-between mb-12 pb-8 border-bottom border-neutral-100">
                                        <span class="text-xxs text-neutral-400">Questions List</span>
                                        <button type="button" class="btn btn-link text-primary-600 text-xxs fw-semibold p-0 select-all-questions-btn" style="text-decoration: none;">Select All</button>
                                    </div>
                                    @php
                                        $categoryQuestions = collect($faq->details)->filter(fn($q) => ($q['status'] ?? 1) == 1)->all();
                                        $selectedCatQuestions = $selectedQuestions[$faq->id] ?? [];
                                    @endphp
                                    @if (empty($categoryQuestions))
                                        <span class="text-neutral-400 text-xs">No active questions in this category.</span>
                                    @else
                                        <div class="row g-3">
                                            @foreach ($categoryQuestions as $qIdx => $qItem)
                                                @php
                                                    $qText = $qItem['question'] ?? '';
                                                    $isQuestionSelected = in_array($qText, $selectedCatQuestions);
                                                @endphp
                                                <div class="col-12">
                                                    <div class="p-16 border rounded-6 bg-neutral-50-hover transition-all faq-question-card {{ $isQuestionSelected ? 'border-primary-600 bg-primary-50' : 'border-neutral-200' }}" style="cursor: pointer;">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input faq-question-checkbox" type="checkbox" name="faq_selection[questions][{{ $faq->id }}][]" value="{{ $qText }}" id="q_{{ $faq->id }}_{{ $qIdx }}" {{ $isQuestionSelected ? 'checked' : '' }}>
                                                            <label class="form-check-label ms-2 d-block cursor-pointer" for="q_{{ $faq->id }}_{{ $qIdx }}">
                                                                <span class="fw-semibold text-xs text-neutral-800 d-block mb-4">{{ $qText }}</span>
                                                                <span class="text-xxs text-neutral-500 d-block text-truncate" title="{{ strip_tags($qItem['answer'] ?? '') }}">{{ strip_tags($qItem['answer'] ?? '') }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Hidden templates for dynamically instantiating cards -->
            @foreach ($allFaqs as $faq)
                <template id="faq-cat-template-{{ $faq->id }}">
                    <div class="col-md-6 faq-category-card-wrapper" data-category-id="{{ $faq->id }}">
                        <div class="card border rounded-8 overflow-hidden shadow-none mb-12">
                            <!-- Category Header -->
                            <div class="card-header bg-neutral-50 p-16 d-flex align-items-center justify-content-between cursor-pointer faq-card-accordion-header" data-target="#faq-card-body-{{ $faq->id }}">
                                <div class="d-flex align-items-center gap-12">
                                    <input type="hidden" name="faq_selection[faq_ids][]" value="{{ $faq->id }}">
                                    <h6 class="text-sm fw-semibold text-secondary-light mb-0">{{ $faq->category }}</h6>
                                    <span class="badge bg-neutral-200 text-neutral-600 rounded-pill text-xxs px-8 py-4">
                                        {{ count(collect($faq->details)->filter(fn($q) => ($q['status'] ?? 1) == 1)) }} Questions
                                    </span>
                                    <span class="selected-count-badge badge bg-primary-100 text-primary-600 rounded-pill text-xxs px-8 py-4 d-none">
                                        0 Selected
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-12">
                                    <button type="button" class="btn btn-xs btn-outline-danger btn-icon remove-faq-category-btn" data-category-id="{{ $faq->id }}" title="Remove Category">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <i class="ri-arrow-down-s-line text-lg toggle-icon accordion-arrow" style="transition: transform 0.2s;"></i>
                                </div>
                            </div>

                            <!-- Questions List -->
                            <div id="faq-card-body-{{ $faq->id }}" class="card-body p-16 bg-white d-none">
                                <div class="d-flex align-items-center justify-content-between mb-12 pb-8 border-bottom border-neutral-100">
                                    <span class="text-xxs text-neutral-400">Questions List</span>
                                    <button type="button" class="btn btn-link text-primary-600 text-xxs fw-semibold p-0 select-all-questions-btn" style="text-decoration: none;">Select All</button>
                                </div>
                                @php
                                    $categoryQuestions = collect($faq->details)->filter(fn($q) => ($q['status'] ?? 1) == 1)->all();
                                @endphp
                                @if (empty($categoryQuestions))
                                    <span class="text-neutral-400 text-xs">No active questions in this category.</span>
                                @else
                                    <div class="row g-3">
                                        @foreach ($categoryQuestions as $qIdx => $qItem)
                                            @php
                                                $qText = $qItem['question'] ?? '';
                                            @endphp
                                            <div class="col-12">
                                                <div class="p-16 border rounded-6 bg-neutral-50-hover transition-all faq-question-card border-neutral-200" style="cursor: pointer;">
                                                    <div class="form-check mb-0">
                                                        <input class="form-check-input faq-question-checkbox" type="checkbox" name="faq_selection[questions][{{ $faq->id }}][]" value="{{ $qText }}" id="q_{{ $faq->id }}_{{ $qIdx }}">
                                                        <label class="form-check-label ms-2 d-block cursor-pointer" for="q_{{ $faq->id }}_{{ $qIdx }}">
                                                            <span class="fw-semibold text-xs text-neutral-800 d-block mb-4">{{ $qText }}</span>
                                                            <span class="text-xxs text-neutral-500 d-block text-truncate" title="{{ strip_tags($qItem['answer'] ?? '') }}">{{ strip_tags($qItem['answer'] ?? '') }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </template>
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
                'branch-detail-item border rounded p-3 mb-3 bg-light bg-opacity-25';

            div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-neutral-200 dark:border-neutral-700">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-600 branch-index-badge">#${branchIndex + 1}</span>
                <strong class="branch-header-title text-neutral-800 dark:text-neutral-200">Branch Detail: (Unassigned)</strong>
            </div>
        </div>
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

            <div class="col-md-12 mb-3">
                <label>Description</label>
                <div class="quill-editor-wrapper">
                    <div class="quill-editor"
                        data-input="description_${branchIndex}">
                    </div>
                </div>
                <textarea
                    name="branch_details[${branchIndex}][description]"
                    id="description_${branchIndex}"
                    class="d-none"></textarea>
            </div>

            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h6>Features</h6>

                    <button type="button"
                        class="btn btn-primary btn-sm addFeatureBtn">
                        Add Feature
                    </button>
                </div>

                <div class="row g-2 mb-3 mt-2">
                    <div class="col-md-6">
                        <label>Features Section Title</label>
                        <input type="text"
                            class="form-control form-control-sm"
                            name="branch_details[${branchIndex}][features_title]">
                    </div>
                    <div class="col-md-6">
                        <label>Features Section Description</label>
                        <textarea
                            class="form-control"
                            rows="2"
                            name="branch_details[${branchIndex}][features_description]"></textarea>
                    </div>
                </div>

                <div class="features-wrapper"></div>
            </div>

            <div class="col-md-12">
                <label>Middle Banner</label>

                <input type="file"
                    class="form-control form-control-sm"
                    name="branch_details[${branchIndex}][middle_banner]">
            </div>

            <div class="col-md-12 mt-2">
                <label>Middle Banner Link</label>
                <input type="text"
                    class="form-control form-control-sm"
                    name="branch_details[${branchIndex}][middle_banner_link]">
            </div>

            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h6>Gallery</h6>

                    <button type="button"
                        class="btn btn-primary btn-sm addGalleryBtn">
                        Add Gallery
                    </button>
                </div>

                <div class="row g-2 mb-3 mt-2">
                    <div class="col-md-6">
                        <label>Gallery Section Title</label>
                        <input type="text"
                            class="form-control form-control-sm"
                            name="branch_details[${branchIndex}][gallery_title]">
                    </div>
                    <div class="col-md-6">
                        <label>Gallery Section Description</label>
                        <textarea
                            class="form-control"
                            rows="2"
                            name="branch_details[${branchIndex}][gallery_description]"></textarea>
                    </div>
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
            initQuillEditors(div);
            updateBranchIndices();

            branchIndex++;

            refreshBranchDropdowns();
        });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('branch-select')) {
            refreshBranchDropdowns();
            toggleAddMoreButton();

            // Update branch title in header
            const branchItem = e.target.closest('.branch-detail-item');
            if (branchItem) {
                const headerTitle = branchItem.querySelector('.branch-header-title');
                if (headerTitle) {
                    const selectedText = e.target.options[e.target.selectedIndex].text;
                    headerTitle.innerText = e.target.value ? `Branch Detail: ${selectedText}` : 'Branch Detail: (Unassigned)';
                }
            }
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
                    updateBranchIndices();
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
            updateBranchIndices();
        }, 100);
    });

    function updateBranchIndices() {
        document.querySelectorAll('#branchDetailsWrapper .branch-detail-item').forEach((item, index) => {
            let badge = item.querySelector('.branch-index-badge');
            if (badge) {
                badge.innerText = `#${index + 1}`;
            }
        });
    }

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

    // quill editor starts
    document.addEventListener('DOMContentLoaded', function () {
        initQuillEditors();
    });

    function initQuillEditors(context = document) {
        context.querySelectorAll('.quill-editor').forEach(editor => {
            if (editor.dataset.quillInit) {
                return;
            }

            const inputId = editor.dataset.input;
            const textarea = document.getElementById(inputId);
            if (!textarea) return;

            const quill = new Quill(editor, {
                theme: 'snow',
                placeholder: 'Write description here...',
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

            quill.on('text-change', function () {
                textarea.value = quill.root.innerHTML;
            });
            editor.dataset.quillInit = "1";
        });
    }

    // FAQ selection controls
    $(document).ready(function() {
        // Handle FAQ Category addition
        $(document).on('click', '#btn-add-faq-category', function() {
            const select = $('#faq-category-selector');
            const categoryId = select.val();
            if (!categoryId) {
                if (window.appToast) {
                    window.appToast('error', 'Please select an FAQ category to add.');
                } else {
                    alert('Please select an FAQ category to add.');
                }
                return;
            }

            // Get template and append
            const template = $('#faq-cat-template-' + categoryId);
            if (template.length) {
                const html = template.html();
                const container = $('#active-faq-categories-container');
                
                // Hide empty state if present
                container.find('.faq-empty-state').addClass('d-none');
                
                const $card = $(html);
                container.append($card);
                $card.hide().fadeIn(300);

                // Auto-expand the newly added card
                const body = $card.find('.card-body');
                const arrow = $card.find('.accordion-arrow');
                body.removeClass('d-none').hide().slideDown(200);
                arrow.css('transform', 'rotate(180deg)');

                // Disable and hide category option in selector
                const option = select.find('option[value="' + categoryId + '"]');
                option.prop('disabled', true).addClass('d-none');
                select.val(''); // Reset select
            }
        });

        // Handle FAQ Category removal
        $(document).on('click', '.remove-faq-category-btn', function() {
            const button = $(this);
            const categoryId = button.data('category-id');
            const card = button.closest('.faq-category-card-wrapper');
            
            window.openAppConfirm({
                title: 'Remove FAQ Category',
                message: 'Are you sure you want to remove this FAQ category and all its selected questions from this page?',
                buttonText: 'Yes, Remove',
                buttonClass: 'btn btn-sm btn-danger',
                onConfirm: function() {
                    card.fadeOut(300, function() {
                        card.remove();

                        // Show empty state if no categories left
                        const container = $('#active-faq-categories-container');
                        if (container.find('.faq-category-card-wrapper').length === 0) {
                            container.find('.faq-empty-state').removeClass('d-none');
                        }

                        // Re-enable and show category option in selector
                        const select = $('#faq-category-selector');
                        const option = select.find('option[value="' + categoryId + '"]');
                        option.prop('disabled', false).removeClass('d-none');
                    });
                }
            });
        });

        function updateFaqCardHeaderState(card) {
            const checkboxes = card.find('.faq-question-checkbox');
            const checked = checkboxes.filter(':checked');
            const selectAllBtn = card.find('.select-all-questions-btn');
            const badge = card.find('.selected-count-badge');

            // Update Select All button text
            if (checkboxes.length > 0 && checked.length === checkboxes.length) {
                selectAllBtn.text('Deselect All');
            } else {
                selectAllBtn.text('Select All');
            }

            // Update Selected badge in card header
            if (badge.length) {
                if (checked.length > 0) {
                    badge.text(checked.length + ' Selected').removeClass('d-none');
                } else {
                    badge.addClass('d-none');
                }
            }
        }

        // Initialize header states and accordion state for pre-loaded FAQ cards
        $('.faq-category-card-wrapper').each(function() {
            updateFaqCardHeaderState($(this));
            
            const body = $(this).find('.card-body');
            const arrow = $(this).find('.accordion-arrow');
            if (body.hasClass('d-none')) {
                arrow.css('transform', 'rotate(0deg)');
            } else {
                arrow.css('transform', 'rotate(180deg)');
            }
        });

        // Accordion toggle click handler
        $(document).on('click', '.faq-card-accordion-header', function(e) {
            // If click originated from the delete button, do nothing
            if ($(e.target).closest('.remove-faq-category-btn').length) {
                return;
            }
            const targetSelector = $(this).data('target');
            const target = $(targetSelector);
            const arrow = $(this).find('.accordion-arrow');
            
            if (target.hasClass('d-none')) {
                target.removeClass('d-none').hide().slideDown(200);
                arrow.css('transform', 'rotate(180deg)');
            } else {
                target.slideUp(200, function() {
                    target.addClass('d-none');
                });
                arrow.css('transform', 'rotate(0deg)');
            }
        });

        // Select All / Deselect All click handler
        $(document).on('click', '.select-all-questions-btn', function(e) {
            e.stopPropagation();
            const button = $(this);
            const card = button.closest('.faq-category-card-wrapper');
            const checkboxes = card.find('.faq-question-checkbox');
            const unchecked = checkboxes.filter(':not(:checked)');
            
            if (unchecked.length > 0) {
                checkboxes.prop('checked', true).trigger('change');
            } else {
                checkboxes.prop('checked', false).trigger('change');
            }
        });

        // Handle FAQ question card click to toggle checkbox
        $(document).on('click', '.faq-question-card', function(e) {
            // If clicked on the checkbox or label directly, let the default event handle it
            if ($(e.target).is('input[type="checkbox"]') || $(e.target).closest('label').length) {
                return;
            }
            const checkbox = $(this).find('.faq-question-checkbox');
            if (!checkbox.prop('disabled')) {
                const isChecked = checkbox.is(':checked');
                checkbox.prop('checked', !isChecked).trigger('change');
            }
        });

        // Handle FAQ question checkbox change to update styling and header state
        $(document).on('change', '.faq-question-checkbox', function() {
            const container = $(this).closest('.faq-question-card');
            const isChecked = $(this).is(':checked');
            if (isChecked) {
                container.addClass('border-primary-600 bg-primary-50').removeClass('border-neutral-200');
            } else {
                container.addClass('border-neutral-200').removeClass('border-primary-600 bg-primary-50');
            }
            
            const card = $(this).closest('.faq-category-card-wrapper');
            updateFaqCardHeaderState(card);
        });
    });
</script>

@endsection