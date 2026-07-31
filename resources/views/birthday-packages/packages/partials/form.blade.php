@php
$isEdit = isset($package);
@endphp

<div class="card">

    <div class="card-header">

        <h5 class="mb-0">

            {{ $isEdit ? 'Edit' : 'Create' }} Birthday Package

        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">

            {{-- BRANCH --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Branch
                </label>

                <select name="branch_id"
                    class="form-select form-select-sm">

                    <option value="">
                        Select Branch
                    </option>

                    @foreach($branches as $branch)

                    <option value="{{ $branch->id }}"
                        {{ old('branch_id', $package->branch_id ?? '') == $branch->id ? 'selected' : '' }}>

                        {{ $branch->title }}

                    </option>

                    @endforeach

                </select>

            </div>

            {{-- TITLE --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Title
                </label>

                <input type="text"
                    name="title"
                    id="title"
                    class="form-control form-control-sm"
                    value="{{ old('title', $package->title ?? '') }}"
                    required>

            </div>

            {{-- SLUG --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Slug
                </label>

                <input type="text"
                    name="slug"
                    id="slug"
                    class="form-control form-control-sm"
                    value="{{ old('slug', $package->slug ?? '') }}"
                    required>

            </div>

            {{-- PRICE --}}
            <div class="col-md-6 d-none">

                <label class="form-label fw-semibold">
                    Price
                </label>

                <input type="text"
                    name="price"
                    class="form-control form-control-sm"
                    value="{{ old('price', $package->price ?? '') }}">

            </div>

            {{-- IMAGE --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Thumbnail Image
                </label>

                <input type="file"
                    name="image"
                    class="form-control form-control-sm"
                    {{ $isEdit ? '' : 'required' }}>

                @if(!empty($package?->image))

                <div class="mt-2">

                    <img src="{{ asset($package->image) }}"
                        width="120"
                        class="rounded border">

                </div>

                @endif

            </div>

            {{-- BANNER IMAGE --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Banner Image
                </label>
                <input type="file"
                    name="banner_image"
                    class="form-control form-control-sm">
                @if(!empty($package?->banner_image))
                <div class="mt-2">
                    <img src="{{ asset($package->banner_image) }}"
                        width="160"
                        class="rounded border">
                </div>
                @endif
            </div>

            {{-- HIDDEN MINIMUM KIDS & DURATION --}}
            <input type="hidden" name="minimum_kids" value="{{ old('minimum_kids', $package->minimum_kids ?? '') }}">
            <input type="hidden" name="duration" value="{{ old('duration', $package->duration ?? '') }}">

             {{-- SORT ORDER --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Sort Order
                </label>
                <input type="number"
                    name="sort_order"
                    class="form-control form-control-sm"
                    value="{{ old('sort_order', $package->sort_order ?? 0) }}">
            </div>
            
            {{-- WEEKDAY RATE --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Weekday Details
                </label>
                <textarea name="weekday_rate"
                    class="form-control"
                    rows="4"
                    placeholder="e.g. Mon-Thurs: 10 kids / AED 120 per child">{{ old('weekday_rate', $package->weekday_rate ?? '') }}</textarea>
            </div>

            {{-- WEEKEND RATE --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Weekend Details
                </label>
                <textarea name="weekend_rate"
                    class="form-control"
                    rows="4"
                    placeholder="e.g. Fri-Sun: 15 kids / AED 150 per child">{{ old('weekend_rate', $package->weekend_rate ?? '') }}</textarea>
            </div>

           

            {{-- STATUS --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Status
                </label>

                <select name="status"
                    class="form-select form-select-sm">
                    <option value="1"
                        {{ old('status', $package->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('status', $package->status ?? 1) == 0 ? 'selected' : '' }}>
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
                        {!! old('description', $package->description ?? '') !!}
                    </div>
                    <textarea name="description" id="description" class="d-none">{{ old('description', $package->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- SEO COMPONENT -->
        @include('components.seo-fields', [
            'model' => $package ?? null
        ])

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
                            rows="20"
                            class="form-control"
                            placeholder="Enter schema markup (e.g. JSON format)">{{ old('schema', $package->schema ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- FAQ SELECTION --}}
        @php
            $selectedFaqIds = old('faq_selection.faq_ids', $package->faq_selection['faq_ids'] ?? []);
            $selectedQuestions = old('faq_selection.questions', $package->faq_selection['questions'] ?? []);
        @endphp
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-header bg-white py-3">
                <div class="card-title mb-0">FAQ Section Settings</div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-20 text-xs">Manage the FAQ categories and select specific questions to display on this birthday package page.</p>
                
                <div class="row mb-20">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">FAQ Section Title</label>
                        <input type="text" name="faq_title" class="form-control form-control-sm" placeholder="Enter FAQ section title (e.g. Frequently Asked Questions)" value="{{ old('faq_title', $package->faq_title ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">FAQ Section Description</label>
                        <textarea name="faq_description" rows="2" class="form-control" placeholder="Enter FAQ section description">{{ old('faq_description', $package->faq_description ?? '') }}</textarea>
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
                            <span class="text-xs">No FAQ categories added to this birthday package page yet. Choose a category from the selector above to add it.</span>
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
    </div>

    <div class="card-footer text-end">
        <button type="submit"
            class="btn btn-primary">
            {{ $isEdit ? 'Update' : 'Save' }}
        </button>
    </div>
</div>

@section('script')

<script>
    document.getElementById('title').addEventListener('keyup', function() {
        let slug = this.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

        document.getElementById('slug').value = slug;
    });

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

        // FAQ selection controls
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