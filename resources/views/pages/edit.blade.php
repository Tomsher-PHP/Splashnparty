@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-4">Edit Page Content: {{ $page->title }}</h6>
            <p class="mb-0 text-secondary-light">Manage sections, text, files, and repeatable details for this page.</p>
        </div>
        <div>
            <a href="{{ route('pages.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="ri-arrow-left-line"></i> Back to Pages
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading fw-semibold mb-8 text-sm"><i class="ri-error-warning-line"></i> Please correct the errors below:</h6>
            <ul class="mb-0 ps-20 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">
            @foreach ($schema['sections'] as $sectionIndex => $section)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-12">
                        <div class="card-header bg-base py-16 px-24 border-bottom border-neutral-100">
                            <h6 class="text-md fw-semibold text-dark mb-0">{{ $section['title'] }}</h6>
                            @if (!empty($section['description']))
                                <p class="text-xs text-secondary-light mb-0 mt-4">{{ $section['description'] }}</p>
                            @endif
                        </div>
                        <div class="card-body p-24">
                            <div class="row gy-4">
                                @foreach ($section['fields'] as $field)
                                    @php
                                        $fieldName = $field['name'];
                                        $fieldType = $field['type'];
                                        $fieldValue = old($fieldName, $page->content[$fieldName] ?? '');
                                        $colClass = in_array($fieldType, ['repeater', 'wysiwyg', 'gallery']) ? 'col-12' : 'col-md-6';
                                    @endphp

                                    <div class="{{ $colClass }}">
                                        @if ($fieldType !== 'repeater')
                                            <label for="{{ $fieldName }}" class="form-label fw-semibold text-secondary-light mb-8">
                                                {{ $field['label'] }}
                                                @if (in_array('required', $field['rules'] ?? []))
                                                    <span class="text-danger-main">*</span>
                                                @endif
                                            </label>
                                        @endif

                                        @if ($fieldType === 'text')
                                            <input type="text" id="{{ $fieldName }}" name="{{ $fieldName }}" 
                                                value="{{ $fieldValue }}" 
                                                class="form-control form-control-sm @error($fieldName) is-invalid @enderror"
                                                placeholder="{{ $field['placeholder'] ?? '' }}">

                                        @elseif ($fieldType === 'textarea')
                                            <textarea id="{{ $fieldName }}" name="{{ $fieldName }}" rows="4"
                                                class="form-control form-control-sm @error($fieldName) is-invalid @enderror"
                                                placeholder="{{ $field['placeholder'] ?? '' }}">{{ $fieldValue }}</textarea>

                                        @elseif ($fieldType === 'image')
                                            @if (!empty($page->content[$fieldName]))
                                                <div class="settings-current-media mb-12">
                                                    <img src="{{ asset('storage/' . $page->content[$fieldName]) }}" alt="{{ $field['label'] }}">
                                                    <div class="min-w-0">
                                                        <span class="text-secondary-light small d-block">Current Asset:</span>
                                                        <a href="{{ asset('storage/' . $page->content[$fieldName]) }}" target="_blank" class="text-primary-600 text-xs fw-medium">
                                                            View Full Image <i class="ri-external-link-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="settings-file-upload @error($fieldName) is-invalid @enderror">
                                                <input type="file" id="{{ $fieldName }}" name="{{ $fieldName }}"
                                                    class="settings-file-upload__input"
                                                    accept="image/*" data-file-input>
                                                <label for="{{ $fieldName }}" class="settings-file-upload__label">
                                                    <span class="settings-file-upload__icon">
                                                        <i class="ri-upload-cloud-2-line"></i>
                                                    </span>
                                                    <span class="settings-file-upload__text" data-file-name>Choose new image</span>
                                                </label>
                                            </div>
                                            <div class="settings-selected-file d-none mt-12" data-selected-file-wrap>
                                                <img src="" alt="Selected file preview" class="d-none" data-selected-image-preview>
                                                <span class="settings-selected-file__name" data-selected-file-name></span>
                                            </div>

                                        @elseif ($fieldType === 'multiselect')
                                            <div class="multiselect-widget @error($fieldName) is-invalid @enderror">
                                                <div class="row g-3">
                                                    @foreach ($field['options'] ?? [] as $option)
                                                        @php
                                                            $isSelected = in_array($option['value'], (array)$fieldValue);
                                                        @endphp
                                                        <div class="col-xl-3 col-md-4 col-sm-6">
                                                            <div class="card h-100 border rounded-8 overflow-hidden shadow-none position-relative cursor-pointer select-option-card {{ $isSelected ? 'border-primary-600 bg-primary-50' : 'border-neutral-200' }}" data-value="{{ $option['value'] }}">
                                                                <div class="position-absolute top-12 start-12" style="z-index: 10;">
                                                                    <div class="form-check checkbox-primary">
                                                                        <input class="form-check-input select-option-checkbox" type="checkbox" name="{{ $fieldName }}[]" value="{{ $option['value'] }}" id="check_{{ $fieldName }}_{{ $option['value'] }}" {{ $isSelected ? 'checked' : '' }}>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="media-container bg-neutral-100 d-flex align-items-center justify-content-center overflow-hidden" style="height: 120px;">
                                                                    @if (!empty($option['image']))
                                                                        @if (($option['type'] ?? 'image') === 'video')
                                                                            <video src="{{ $option['image'] }}" class="w-100 h-100" style="object-fit: cover;" muted></video>
                                                                        @else
                                                                            <img src="{{ $option['image'] }}" alt="{{ $option['label'] }}" class="w-100 h-100" style="object-fit: cover;">
                                                                        @endif
                                                                    @else
                                                                        <span class="text-neutral-400 text-xxs">No Media</span>
                                                                    @endif
                                                                </div>
                                                                <div class="p-12 border-top border-neutral-100">
                                                                    <h6 class="text-xs fw-semibold text-dark mb-0 text-truncate">{{ $option['label'] }}</h6>
                                                                    <span class="text-xxs text-neutral-400 text-capitalize">{{ $option['type'] ?? 'image' }} Banner</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                        @elseif ($fieldType === 'wysiwyg')
                                            <div class="quill-editor-wrapper @error($fieldName) is-invalid @enderror">
                                                <div class="quill-editor" data-input="{{ $fieldName }}">
                                                    {!! $fieldValue !!}
                                                </div>
                                                <textarea name="{{ $fieldName }}" id="{{ $fieldName }}" class="d-none">{{ $fieldValue }}</textarea>
                                            </div>

                                        @elseif ($fieldType === 'gallery')
                                            <div class="gallery-widget @error($fieldName) is-invalid @enderror">
                                                <div class="gallery-uploader-box mb-20">
                                                    <input type="file" id="picker-{{ $fieldName }}" class="gallery-picker-input d-none" multiple accept="image/*">
                                                    <div class="gallery-uploader-trigger d-flex flex-column align-items-center justify-content-center p-32 border border-dashed border-neutral-300 rounded-8 bg-neutral-50 cursor-pointer text-center">
                                                        <span class="gallery-uploader-icon mb-12 text-primary-600 bg-primary-50 rounded-circle d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                                                            <i class="ri-upload-cloud-2-line text-2xl"></i>
                                                        </span>
                                                        <h6 class="text-sm fw-semibold text-dark mb-4">Click to select multiple images at once</h6>
                                                        <p class="text-xs text-secondary-light mb-0">Images will be added as sortable thumbnails below</p>
                                                    </div>
                                                </div>

                                                <div class="gallery-sortable-grid row g-3" id="gallery-grid-{{ $fieldName }}" data-gallery-name="{{ $fieldName }}">
                                                    @php
                                                        $galleryImages = old($fieldName, $page->content[$fieldName] ?? []);
                                                    @endphp
                                                    
                                                    @foreach ($galleryImages as $index => $imageVal)
                                                        @php
                                                            $imagePath = is_array($imageVal) ? ($imageVal['image'] ?? ($imageVal['icon'] ?? '')) : $imageVal;
                                                        @endphp
                                                        @if (!empty($imagePath))
                                                            <div class="col-xl-2 col-md-3 col-sm-4 gallery-card-col" data-index="{{ $index }}">
                                                                <div class="card gallery-thumb-card border border-neutral-200 shadow-none position-relative overflow-hidden rounded-8">
                                                                    <div class="gallery-thumb-container d-flex align-items-center justify-content-center bg-neutral-50" style="height: 110px;">
                                                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="Gallery Image" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                                                                    </div>
                                                                    
                                                                    <input type="hidden" name="{{ $fieldName }}[{{ $index }}][type]" value="existing">
                                                                    <input type="hidden" name="{{ $fieldName }}[{{ $index }}][value]" value="{{ $imagePath }}">
                                                                
                                                                    <div class="gallery-thumb-actions d-flex align-items-center justify-content-end p-8 bg-neutral-100 border-top border-neutral-200">
                                                                        <button type="button" class="btn btn-xs btn-outline-danger btn-icon gallery-remove-btn" title="Delete Image">
                                                                            <i class="ri-delete-bin-line"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                        @elseif ($fieldType === 'repeater')
                                            <div class="repeater-widget">
                                                <div class="d-flex align-items-center justify-content-between mb-16 border-bottom border-neutral-100 pb-12">
                                                    <span class="fw-semibold text-secondary-light">{{ $field['label'] }}</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <button type="button" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2 repeater-add-btn" 
                                                            data-repeater-name="{{ $fieldName }}">
                                                            <i class="ri-add-line"></i> Add Item
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="repeater-container" id="container-{{ $fieldName }}" data-repeater-name="{{ $fieldName }}">
                                                    @php
                                                        $rows = old($fieldName, $page->content[$fieldName] ?? []);
                                                    @endphp
                                                    
                                                    @forelse ($rows as $index => $row)
                                                        <div class="repeater-row card border border-neutral-200 mb-16 shadow-none" data-index="{{ $index }}">
                                                            <div class="card-header py-12 px-16 bg-neutral-50 d-flex justify-content-between align-items-center">
                                                                <span class="fw-semibold text-secondary-light">
                                                                    Item #<span class="row-number">{{ $index + 1 }}</span>
                                                                </span>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <button type="button" class="btn btn-xs btn-outline-danger btn-icon repeater-remove-btn" title="Remove Item">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                    <span class="repeater-drag-handle cursor-move" title="Drag to Reorder">
                                                                        <i class="ri-drag-move-2-fill text-neutral-400 text-md"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="card-body p-16">
                                                                <input type="hidden" name="{{ $fieldName }}[{{ $index }}][row_id]" value="{{ $index }}">
                                                                <div class="row gy-3">
                                                                    @foreach ($field['fields'] as $subField)
                                                                        @php
                                                                            $subName = $subField['name'];
                                                                            $subType = $subField['type'];
                                                                            $subValue = $row[$subName] ?? '';
                                                                            if ($subType === 'image' && empty($subValue) && !empty($row[$subName . '_existing'])) {
                                                                                $subValue = $row[$subName . '_existing'];
                                                                            }
                                                                            $subCol = in_array($subType, ['textarea', 'repeater']) ? 'col-12' : 'col-md-6';
                                                                        @endphp

                                                                        <div class="{{ $subCol }}">
                                                                            @if ($subType !== 'repeater')
                                                                                <label class="form-label text-xs fw-semibold text-secondary-light">
                                                                                    {{ $subField['label'] }}
                                                                                    @if (in_array('required', $subField['rules'] ?? []))
                                                                                        <span class="text-danger-main">*</span>
                                                                                    @endif
                                                                                </label>
                                                                            @endif

                                                                            @if ($subType === 'text')
                                                                                <input type="text" name="{{ $fieldName }}[{{ $index }}][{{ $subName }}]" 
                                                                                    value="{{ $subValue }}" 
                                                                                    class="form-control form-control-sm"
                                                                                    placeholder="{{ $subField['placeholder'] ?? '' }}">

                                                                            @elseif ($subType === 'textarea')
                                                                                <textarea name="{{ $fieldName }}[{{ $index }}][{{ $subName }}]" rows="4"
                                                                                    class="form-control form-control-sm"
                                                                                    placeholder="{{ $subField['placeholder'] ?? '' }}">{{ $subValue }}</textarea>

                                                                            @elseif ($subType === 'image')
                                                                                @if (!empty($subValue))
                                                                                    <div class="settings-current-media mb-8">
                                                                                        <img src="{{ asset('storage/' . $subValue) }}" alt="{{ $subField['label'] }}" data-existing-preview>
                                                                                        <input type="hidden" name="{{ $fieldName }}[{{ $index }}][{{ $subName }}_existing]" value="{{ $subValue }}">
                                                                                        <div class="min-w-0">
                                                                                            <span class="text-secondary-light text-xs d-block">Existing Asset</span>
                                                                                            <span class="text-xs text-neutral-500 font-monospace text-truncate d-block" style="max-width: 250px;">{{ basename($subValue) }}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif

                                                                                <div class="settings-file-upload">
                                                                                    <input type="file" id="file_{{ $fieldName }}_{{ $index }}_{{ $subName }}" 
                                                                                        name="{{ $fieldName }}[{{ $index }}][{{ $subName }}]"
                                                                                        class="settings-file-upload__input"
                                                                                        accept="image/*" data-file-input>
                                                                                    <label for="file_{{ $fieldName }}_{{ $index }}_{{ $subName }}" class="settings-file-upload__label py-4 px-12" style="min-height:36px;">
                                                                                        <span class="settings-file-upload__icon" style="width:22px; height:22px; font-size:12px;">
                                                                                            <i class="ri-upload-cloud-2-line"></i>
                                                                                        </span>
                                                                                        <span class="settings-file-upload__text text-xs" data-file-name>Choose image</span>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="settings-selected-file d-none mt-8" data-selected-file-wrap>
                                                                                    <img src="" alt="Selected file preview" class="d-none" data-selected-image-preview>
                                                                                    <span class="settings-selected-file__name text-xs" data-selected-file-name></span>
                                                                                </div>
                                                                            @elseif ($subType === 'repeater')
                                                                                @php
                                                                                    $nestedRows = $row[$subName] ?? [];
                                                                                    $isSingleField = count($subField['fields']) === 1;
                                                                                @endphp
                                                                                <div class="nested-repeater-widget {{ $isSingleField ? 'p-0 border-0 bg-transparent' : 'p-16 border border-neutral-200 rounded-8 bg-neutral-50' }} mt-12">
                                                                                    <div class="d-flex align-items-center justify-content-between mb-12 {{ $isSingleField ? '' : 'border-bottom border-neutral-200 pb-8' }}">
                                                                                        <span class="fw-semibold text-secondary-light text-xs">{{ $subField['label'] }}</span>
                                                                                        <button type="button" class="btn btn-xs btn-primary-600 d-inline-flex align-items-center gap-1 nested-repeater-add-btn" 
                                                                                            data-parent-repeater="{{ $fieldName }}"
                                                                                            data-parent-index="{{ $index }}"
                                                                                            data-nested-repeater="{{ $subName }}">
                                                                                            <i class="ri-add-line"></i> Add {{ $isSingleField ? 'Point' : 'Item' }}
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="nested-repeater-container d-flex flex-column gap-8" 
                                                                                        id="nested-container-{{ $fieldName }}-{{ $index }}-{{ $subName }}"
                                                                                        data-parent-repeater="{{ $fieldName }}"
                                                                                        data-parent-index="{{ $index }}"
                                                                                        data-nested-repeater="{{ $subName }}">
                                                                                        @foreach ($nestedRows as $nestedIndex => $nestedRow)
                                                                                            @if ($isSingleField)
                                                                                                @php
                                                                                                    $nestedSubField = $subField['fields'][0];
                                                                                                    $nestedSubName = $nestedSubField['name'];
                                                                                                    $nestedValue = $nestedRow[$nestedSubName] ?? '';
                                                                                                @endphp
                                                                                                <div class="nested-repeater-row d-flex align-items-center gap-2 mb-8" data-index="{{ $nestedIndex }}">
                                                                                                    <div class="flex-grow-1">
                                                                                                        <input type="text" 
                                                                                                            name="{{ $fieldName }}[{{ $index }}][{{ $subName }}][{{ $nestedIndex }}][{{ $nestedSubName }}]"
                                                                                                            value="{{ $nestedValue }}" 
                                                                                                            class="form-control form-control-sm"
                                                                                                            placeholder="{{ $nestedSubField['placeholder'] ?? '' }}">
                                                                                                    </div>
                                                                                                    <button type="button" class="btn btn-xs btn-outline-danger btn-icon nested-repeater-remove-btn" title="Remove Point">
                                                                                                        <i class="ri-delete-bin-line"></i>
                                                                                                    </button>
                                                                                                </div>
                                                                                            @else
                                                                                                <div class="nested-repeater-row card p-12 border border-neutral-200 mb-8 shadow-none bg-base" data-index="{{ $nestedIndex }}">
                                                                                                    <div class="d-flex justify-content-between align-items-center mb-8 pb-8 border-bottom border-neutral-100">
                                                                                                        <span class="text-xs fw-semibold text-secondary-light">Item #<span class="nested-row-number">{{ $nestedIndex + 1 }}</span></span>
                                                                                                        <button type="button" class="btn btn-xs btn-outline-danger btn-icon nested-repeater-remove-btn" title="Remove Item">
                                                                                                            <i class="ri-delete-bin-line"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="row gy-2">
                                                                                                        @foreach ($subField['fields'] as $nestedSubField)
                                                                                                            @php
                                                                                                                $nestedSubName = $nestedSubField['name'];
                                                                                                                $nestedSubType = $nestedSubField['type'];
                                                                                                                $nestedValue = $nestedRow[$nestedSubName] ?? '';
                                                                                                                if ($nestedSubType === 'image' && empty($nestedValue) && !empty($nestedRow[$nestedSubName . '_existing'])) {
                                                                                                                    $nestedValue = $nestedRow[$nestedSubName . '_existing'];
                                                                                                                }
                                                                                                                $nestedCol = 'col-md-4';
                                                                                                            @endphp
                                                                                                            <div class="{{ $nestedCol }}">
                                                                                                                <label class="form-label text-xxs text-secondary-light">
                                                                                                                    {{ $nestedSubField['label'] }}
                                                                                                                    @if (in_array('required', $nestedSubField['rules'] ?? []))
                                                                                                                        <span class="text-danger-main">*</span>
                                                                                                                    @endif
                                                                                                                </label>

                                                                                                                @if ($nestedSubType === 'text')
                                                                                                                    <input type="text" 
                                                                                                                        name="{{ $fieldName }}[{{ $index }}][{{ $subName }}][{{ $nestedIndex }}][{{ $nestedSubName }}]"
                                                                                                                        value="{{ $nestedValue }}" 
                                                                                                                        class="form-control form-control-sm"
                                                                                                                        placeholder="{{ $nestedSubField['placeholder'] ?? '' }}">

                                                                                                                @elseif ($nestedSubType === 'image')
                                                                                                                    @if (!empty($nestedValue))
                                                                                                                        <div class="settings-current-media mb-8">
                                                                                                                            <img src="{{ asset('storage/' . $nestedValue) }}" alt="{{ $nestedSubField['label'] }}" data-existing-preview>
                                                                                                                            <input type="hidden" name="{{ $fieldName }}[{{ $index }}][{{ $subName }}][{{ $nestedIndex }}][{{ $nestedSubName }}_existing]" value="{{ $nestedValue }}">
                                                                                                                            <div class="min-w-0">
                                                                                                                                <span class="text-secondary-light text-xxs d-block">Existing</span>
                                                                                                                                <span class="text-xxs text-neutral-500 font-monospace text-truncate d-block" style="max-width: 100px;">{{ basename($nestedValue) }}</span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    @endif

                                                                                                                    <div class="settings-file-upload">
                                                                                                                        <input type="file" id="file_{{ $fieldName }}_{{ $index }}_{{ $subName }}_{{ $nestedIndex }}_{{ $nestedSubName }}" 
                                                                                                                            name="{{ $fieldName }}[{{ $index }}][{{ $subName }}][{{ $nestedIndex }}][{{ $nestedSubName }}]"
                                                                                                                            class="settings-file-upload__input"
                                                                                                                            accept="image/*" data-file-input>
                                                                                                                        <label for="file_{{ $fieldName }}_{{ $index }}_{{ $subName }}_{{ $nestedIndex }}_{{ $nestedSubName }}" class="settings-file-upload__label py-4 px-12" style="min-height:30px;">
                                                                                                                            <span class="settings-file-upload__icon" style="width:20px; height:20px; font-size:10px;">
                                                                                                                                <i class="ri-upload-cloud-2-line"></i>
                                                                                                                            </span>
                                                                                                                            <span class="settings-file-upload__text text-xxs" data-file-name>Choose image</span>
                                                                                                                        </label>
                                                                                                                    </div>
                                                                                                                    <div class="settings-selected-file d-none mt-8" data-selected-file-wrap>
                                                                                                                        <img src="" alt="Selected file preview" class="d-none" data-selected-image-preview style="width: 40px; height: 25px;">
                                                                                                                        <span class="settings-selected-file__name text-xxs" data-selected-file-name></span>
                                                                                                                    </div>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="repeater-empty text-center p-32 border border-dashed border-neutral-300 rounded-8 bg-neutral-50 mb-16">
                                                            <p class="text-neutral-500 mb-0 text-sm">No items added yet. Click "Add Item" to add some entries.</p>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endif

                                        @error($fieldName)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-12 mt-32">
                <div class="card border-0 shadow-sm rounded-12 p-20 bg-base">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                            <i class="ri-save-line"></i> Save Page Content
                        </button>
                        <a href="{{ route('pages.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Templates for Dynamic Repeaters --}}
    @foreach ($schema['sections'] as $section)
        @foreach ($section['fields'] as $field)
            @if ($field['type'] === 'repeater')
                <template id="template-{{ $field['name'] }}">
                    <div class="repeater-row card border border-neutral-200 mb-16 shadow-none" data-index="__INDEX__">
                        <div class="card-header py-12 px-16 bg-neutral-50 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-secondary-light">
                                Item #<span class="row-number">__NUMBER__</span>
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-xs btn-outline-danger btn-icon repeater-remove-btn" title="Remove Item">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                <span class="repeater-drag-handle cursor-move" title="Drag to Reorder">
                                    <i class="ri-drag-move-2-fill text-neutral-400 text-md"></i>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-16">
                            <input type="hidden" name="{{ $field['name'] }}[__INDEX__][row_id]" value="__INDEX__">
                            <div class="row gy-3">
                                @foreach ($field['fields'] as $subField)
                                    @php
                                        $subName = $subField['name'];
                                        $subType = $subField['type'];
                                        $subCol = in_array($subType, ['textarea', 'repeater']) ? 'col-12' : 'col-md-6';
                                    @endphp

                                    <div class="{{ $subCol }}">
                                        @if ($subType !== 'repeater')
                                            <label class="form-label text-xs fw-semibold text-secondary-light">
                                                {{ $subField['label'] }}
                                                @if (in_array('required', $subField['rules'] ?? []))
                                                    <span class="text-danger-main">*</span>
                                                @endif
                                            </label>
                                        @endif

                                        @if ($subType === 'text')
                                            <input type="text" name="{{ $field['name'] }}[__INDEX__][{{ $subName }}]" 
                                                value="" 
                                                class="form-control form-control-sm"
                                                placeholder="{{ $subField['placeholder'] ?? '' }}">

                                        @elseif ($subType === 'textarea')
                                            <textarea name="{{ $field['name'] }}[__INDEX__][{{ $subName }}]" rows="4"
                                                class="form-control form-control-sm"
                                                placeholder="{{ $subField['placeholder'] ?? '' }}"></textarea>

                                        @elseif ($subType === 'image')
                                            <div class="settings-file-upload">
                                                <input type="file" id="file_{{ $field['name'] }}___INDEX___{{ $subName }}" 
                                                    name="{{ $field['name'] }}[__INDEX__][{{ $subName }}]"
                                                    class="settings-file-upload__input"
                                                    accept="image/*" data-file-input>
                                                <label for="file_{{ $field['name'] }}___INDEX___{{ $subName }}" class="settings-file-upload__label py-4 px-12" style="min-height:36px;">
                                                    <span class="settings-file-upload__icon" style="width:22px; height:22px; font-size:12px;">
                                                        <i class="ri-upload-cloud-2-line"></i>
                                                    </span>
                                                    <span class="settings-file-upload__text text-xs" data-file-name>Choose image</span>
                                                </label>
                                            </div>
                                            <div class="settings-selected-file d-none mt-8" data-selected-file-wrap>
                                                <img src="" alt="Selected file preview" class="d-none" data-selected-image-preview>
                                                <span class="settings-selected-file__name text-xs" data-selected-file-name></span>
                                            </div>

                                        @elseif ($subType === 'repeater')
                                            @php
                                                $isSingleField = count($subField['fields']) === 1;
                                            @endphp
                                            <div class="nested-repeater-widget {{ $isSingleField ? 'p-0 border-0 bg-transparent' : 'p-16 border border-neutral-200 rounded-8 bg-neutral-50' }} mt-12">
                                                <div class="d-flex align-items-center justify-content-between mb-12 {{ $isSingleField ? '' : 'border-bottom border-neutral-200 pb-8' }}">
                                                    <span class="fw-semibold text-secondary-light text-xs">{{ $subField['label'] }}</span>
                                                    <button type="button" class="btn btn-xs btn-primary-600 d-inline-flex align-items-center gap-1 nested-repeater-add-btn" 
                                                        data-parent-repeater="{{ $field['name'] }}"
                                                        data-parent-index="__INDEX__"
                                                        data-nested-repeater="{{ $subName }}">
                                                        <i class="ri-add-line"></i> Add {{ $isSingleField ? 'Point' : 'Item' }}
                                                    </button>
                                                </div>

                                                <div class="nested-repeater-container d-flex flex-column gap-8" 
                                                    id="nested-container-{{ $field['name'] }}-__INDEX__-{{ $subName }}"
                                                    data-parent-repeater="{{ $field['name'] }}"
                                                    data-parent-index="__INDEX__"
                                                    data-nested-repeater="{{ $subName }}">
                                                    {{-- Empty by default when creating a new parent row --}}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </template>

                @foreach ($field['fields'] as $subField)
                    @if ($subField['type'] === 'repeater')
                        @php
                            $isSingleField = count($subField['fields']) === 1;
                        @endphp
                        <template id="nested-template-{{ $field['name'] }}-{{ $subField['name'] }}">
                            @if ($isSingleField)
                                @php
                                    $nestedSubField = $subField['fields'][0];
                                    $nestedSubName = $nestedSubField['name'];
                                @endphp
                                <div class="nested-repeater-row d-flex align-items-center gap-2 mb-8" data-index="__NESTED_INDEX__">
                                    <div class="flex-grow-1">
                                        <input type="text" 
                                            name="{{ $field['name'] }}[__PARENT_INDEX__][{{ $subField['name'] }}][__NESTED_INDEX__][{{ $nestedSubName }}]"
                                            value="" 
                                            class="form-control form-control-sm"
                                            placeholder="{{ $nestedSubField['placeholder'] ?? '' }}">
                                    </div>
                                    <button type="button" class="btn btn-xs btn-outline-danger btn-icon nested-repeater-remove-btn" title="Remove Point">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            @else
                                <div class="nested-repeater-row card p-12 border border-neutral-200 mb-8 shadow-none bg-base" data-index="__NESTED_INDEX__">
                                    <div class="d-flex justify-content-between align-items-center mb-8 pb-8 border-bottom border-neutral-100">
                                        <span class="text-xs fw-semibold text-secondary-light">Item #<span class="nested-row-number">__NESTED_NUMBER__</span></span>
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-icon nested-repeater-remove-btn" title="Remove Item">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                    <div class="row gy-2">
                                        @foreach ($subField['fields'] as $nestedSubField)
                                            @php
                                                $nestedSubName = $nestedSubField['name'];
                                                $nestedSubType = $nestedSubField['type'];
                                                $nestedCol = 'col-md-4';
                                            @endphp
                                            <div class="{{ $nestedCol }}">
                                                <label class="form-label text-xxs text-secondary-light">
                                                    {{ $nestedSubField['label'] }}
                                                    @if (in_array('required', $nestedSubField['rules'] ?? []))
                                                        <span class="text-danger-main">*</span>
                                                    @endif
                                                </label>

                                                @if ($nestedSubType === 'text')
                                                    <input type="text" 
                                                        name="{{ $field['name'] }}[__PARENT_INDEX__][{{ $subField['name'] }}][__NESTED_INDEX__][{{ $nestedSubName }}]"
                                                        value="" 
                                                        class="form-control form-control-sm"
                                                        placeholder="{{ $nestedSubField['placeholder'] ?? '' }}">

                                                @elseif ($nestedSubType === 'image')
                                                    <div class="settings-file-upload">
                                                        <input type="file" id="file_{{ $field['name'] }}___PARENT_INDEX____{{ $subField['name'] }}___NESTED_INDEX____{{ $nestedSubName }}" 
                                                            name="{{ $field['name'] }}[__PARENT_INDEX__][{{ $subField['name'] }}][__NESTED_INDEX__][{{ $nestedSubName }}]"
                                                            class="settings-file-upload__input"
                                                            accept="image/*" data-file-input>
                                                        <label for="file_{{ $field['name'] }}___PARENT_INDEX____{{ $subField['name'] }}___NESTED_INDEX____{{ $nestedSubName }}" class="settings-file-upload__label py-4 px-12" style="min-height:30px;">
                                                            <span class="settings-file-upload__icon" style="width:20px; height:20px; font-size:10px;">
                                                                <i class="ri-upload-cloud-2-line"></i>
                                                            </span>
                                                            <span class="settings-file-upload__text text-xxs" data-file-name>Choose image</span>
                                                        </label>
                                                    </div>
                                                    <div class="settings-selected-file d-none mt-8" data-selected-file-wrap>
                                                        <img src="" alt="Selected file preview" class="d-none" data-selected-image-preview style="width: 40px; height: 25px;">
                                                        <span class="settings-selected-file__name text-xxs" data-selected-file-name></span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </template>
                    @endif
                @endforeach
            @endif
        @endforeach
    @endforeach
@endsection

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
                            [{ header: [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            [{ color: [] }, { background: [] }],
                            ['link'],
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

            // Handle select option card clicks
            $(document).on('click', '.select-option-card', function(e) {
                if ($(e.target).is('input[type="checkbox"]')) {
                    const checkbox = $(this).find('.select-option-checkbox');
                    $(this).toggleClass('border-primary-600 bg-primary-50', checkbox.is(':checked'));
                    $(this).toggleClass('border-neutral-200', !checkbox.is(':checked'));
                    return;
                }
                const checkbox = $(this).find('.select-option-checkbox');
                const isChecked = checkbox.is(':checked');
                checkbox.prop('checked', !isChecked).trigger('change');
                $(this).toggleClass('border-primary-600 bg-primary-50', !isChecked);
                $(this).toggleClass('border-neutral-200', isChecked);
            });

            // Setup dynamic file uploads preview (handles both parent and nested levels)
            $(document).on('change', '[data-file-input]', function() {
                const input = this;
                const file = input.files?.[0] || null;
                const fileName = file ? file.name : 'Choose file';
                const fieldWrap = $(input).closest('.col-md-6, .col-12, .col-md-4');
                const selectedWrap = fieldWrap.find('[data-selected-file-wrap]');
                const selectedName = fieldWrap.find('[data-selected-file-name]');
                const imagePreview = fieldWrap.find('[data-selected-image-preview]');
                const fileNameElement = $(input).closest('.settings-file-upload').find('[data-file-name]');
                const existingPreview = fieldWrap.find('[data-existing-preview], .settings-current-media');

                if (fileNameElement.length) {
                    fileNameElement.text(fileName);
                }

                if (selectedWrap.length && selectedName.length) {
                    selectedName.text(file ? fileName : '');
                    selectedWrap.toggleClass('d-none', !file);
                }

                if (imagePreview.length) {
                    imagePreview.addClass('d-none').removeAttr('src');
                }

                if (file && existingPreview.length) {
                    existingPreview.addClass('d-none');
                } else if (!file && existingPreview.length) {
                    existingPreview.removeClass('d-none');
                }

                const imageExtensions = ['ico', 'jpg', 'jpeg', 'png', 'webp', 'svg'];
                const extension = fileName.split('.').pop()?.toLowerCase();
                const isImage = file && (file.type.startsWith('image/') || imageExtensions.includes(extension));

                if (isImage && imagePreview.length) {
                    imagePreview.attr('src', URL.createObjectURL(file)).removeClass('d-none');
                }
            });

            // Handle Repeater Item Deletion
            $(document).on('click', '.repeater-remove-btn', function() {
                const row = $(this).closest('.repeater-row');
                const container = row.closest('.repeater-container');

                window.openAppConfirm({
                    title: 'Remove Item',
                    message: 'Are you sure you want to remove this item? You will need to save the page to persist this change.',
                    buttonText: 'Yes, Remove',
                    buttonClass: 'btn btn-sm btn-danger',
                    onConfirm: function() {
                        row.fadeOut(300, function() {
                            row.remove();
                            reindexRepeater(container);
                            checkRepeaterEmptyState(container);
                        });
                    }
                });
            });

            // Handle Repeater Item Adding
            $('.repeater-add-btn').on('click', function() {
                const repeaterName = $(this).data('repeater-name');
                const container = $('#container-' + repeaterName);
                const template = $('#template-' + repeaterName).html();

                // Get new index
                const newIndex = container.find('.repeater-row').length;

                // Replace placeholders in template
                let html = template.replace(/__INDEX__/g, newIndex);
                html = html.replace(/__NUMBER__/g, newIndex + 1);

                // Hide empty state if visible
                container.find('.repeater-empty').hide();

                // Append new row
                const $html = $(html);
                container.append($html);
                $html.hide().fadeIn(300);

                reindexRepeater(container);
                checkRepeaterEmptyState(container);
            });

            // Initialize sorting for repeaters using jQuery UI Sortable
            $('.repeater-container').each(function() {
                const container = $(this);
                container.sortable({
                    handle: '.repeater-drag-handle',
                    update: function(event, ui) {
                        reindexRepeater(container);
                    },
                    placeholder: "ui-state-highlight mb-16 rounded-8"
                });
            });

            // Function to reindex parent repeater rows
            function reindexRepeater(container) {
                const repeaterName = container.data('repeater-name');
                container.find('.repeater-row').each(function(index) {
                    const row = $(this);
                    row.attr('data-index', index);
                    row.find('.row-number').text(index + 1);

                    // Reindex all inputs, textareas, files
                    row.find('input, textarea, select').each(function() {
                        const input = $(this);
                        const name = input.attr('name');
                        if (name) {
                            // Replace repeaterName[OLD_INDEX][subField] with repeaterName[NEW_INDEX][subField]
                            const oldIndexPattern = new RegExp(repeaterName + '\\[\\d+\\]', 'g');
                            const newName = name.replace(oldIndexPattern, repeaterName + '[' + index + ']');
                            input.attr('name', newName);
                        }

                        const id = input.attr('id');
                        if (id) {
                            // Replace file_repeaterName_OLD_INDEX_subField
                            const oldIdPattern = new RegExp('file_' + repeaterName + '_\\d+_', 'g');
                            const newId = id.replace(oldIdPattern, 'file_' + repeaterName + '_' + index + '_');
                            input.attr('id', newId);

                            // Update corresponding label for attribute
                            const label = row.find('label[for="' + id + '"]');
                            if (label.length) {
                                label.attr('for', newId);
                            }
                        }
                    });

                    // Trigger reindexing of any nested repeaters inside this row
                    row.find('.nested-repeater-container').each(function() {
                        reindexNestedRepeater($(this));
                    });
                });
            }

            // Check and toggle empty state for repeaters
            function checkRepeaterEmptyState(container) {
                const rowsCount = container.find('.repeater-row').length;
                let emptyDiv = container.find('.repeater-empty');

                if (rowsCount === 0) {
                    if (emptyDiv.length === 0) {
                        container.append(`
                            <div class="repeater-empty text-center p-32 border border-dashed border-neutral-300 rounded-8 bg-neutral-50 mb-16">
                                <p class="text-neutral-500 mb-0 text-sm">No items added yet. Click "Add Item" to add some entries.</p>
                            </div>
                        `);
                    } else {
                        emptyDiv.show();
                    }
                } else if (emptyDiv.length) {
                    emptyDiv.hide();
                }
            }

            // Handle Nested Repeater Item Adding
            $(document).on('click', '.nested-repeater-add-btn', function() {
                const btn = $(this);
                const parentRepeater = btn.data('parent-repeater');
                const parentIndex = btn.closest('.repeater-row').attr('data-index') || btn.data('parent-index');
                const nestedRepeater = btn.data('nested-repeater');
                
                const container = $('#nested-container-' + parentRepeater + '-' + parentIndex + '-' + nestedRepeater);
                const template = $('#nested-template-' + parentRepeater + '-' + nestedRepeater).html();

                if (!container.length || !template) return;

                // Get new nested index
                const newIndex = container.find('.nested-repeater-row').length;

                // Replace placeholders
                let html = template.replace(/__PARENT_INDEX__/g, parentIndex);
                html = html.replace(/__NESTED_INDEX__/g, newIndex);
                html = html.replace(/__NESTED_NUMBER__/g, newIndex + 1);

                const $html = $(html);
                container.append($html);
                $html.hide().fadeIn(300);

                reindexNestedRepeater(container);
            });

            // Handle Nested Repeater Item Deletion
            $(document).on('click', '.nested-repeater-remove-btn', function() {
                const row = $(this).closest('.nested-repeater-row');
                const container = row.closest('.nested-repeater-container');

                window.openAppConfirm({
                    title: 'Remove Item',
                    message: 'Are you sure you want to remove this item? You will need to save the page to persist this change.',
                    buttonText: 'Yes, Remove',
                    buttonClass: 'btn btn-sm btn-danger',
                    onConfirm: function() {
                        row.fadeOut(300, function() {
                            row.remove();
                            reindexNestedRepeater(container);
                        });
                    }
                });
            });

            // Function to reindex nested repeater rows
            function reindexNestedRepeater(container) {
                const parentRepeater = container.data('parent-repeater');
                const parentIndex = container.closest('.repeater-row').attr('data-index') || container.data('parent-index');
                const nestedRepeater = container.data('nested-repeater');

                container.find('.nested-repeater-row').each(function(nestedIndex) {
                    const row = $(this);
                    row.attr('data-index', nestedIndex);
                    row.find('.nested-row-number').text(nestedIndex + 1);

                    // Reindex all inputs, textareas, files inside the nested row
                    row.find('input, textarea, select').each(function() {
                        const input = $(this);
                        const name = input.attr('name');
                        if (name) {
                            // Target format: parentRepeater[parentIndex][nestedRepeater][nestedIndex][nestedSubField]
                            const pattern = new RegExp(parentRepeater + '\\[\\d+\\]\\[' + nestedRepeater + '\\]\\[\\d+\\]', 'g');
                            const newReplacement = parentRepeater + '[' + parentIndex + '][' + nestedRepeater + '][' + nestedIndex + ']';
                            const newName = name.replace(pattern, newReplacement);
                            input.attr('name', newName);
                        }

                        const id = input.attr('id');
                        if (id) {
                            // Target format: file_parentRepeater_parentIndex_nestedRepeater_nestedIndex_nestedSubField
                            const idPattern = new RegExp('file_' + parentRepeater + '_\\d+_' + nestedRepeater + '_\\d+_', 'g');
                            const newIdReplacement = 'file_' + parentRepeater + '_' + parentIndex + '_' + nestedRepeater + '_' + nestedIndex + '_';
                            const newId = id.replace(idPattern, newIdReplacement);
                            input.attr('id', newId);

                            // Update label link
                            const label = row.find('label[for="' + id + '"]');
                            if (label.length) {
                                label.attr('for', newId);
                            }
                        }
                    });
                });
            }

            // Handle Gallery Trigger click
            $(document).on('click', '.gallery-uploader-trigger', function() {
                $(this).siblings('.gallery-picker-input').click();
            });

            // Handle Gallery File Selection
            $(document).on('change', '.gallery-picker-input', function() {
                const input = this;
                const files = input.files;
                if (!files || files.length === 0) return;

                const galleryName = input.id.replace('picker-', '');
                const grid = $('#gallery-grid-' + galleryName);

                Array.from(files).forEach(function(file) {
                    const newIndex = grid.find('.gallery-card-col').length;
                    const objectUrl = URL.createObjectURL(file);

                    const cardHtml = `
                        <div class="col-xl-2 col-md-3 col-sm-4 gallery-card-col" data-index="${newIndex}">
                            <div class="card gallery-thumb-card border border-neutral-200 shadow-none position-relative overflow-hidden rounded-8">
                                <div class="gallery-thumb-container d-flex align-items-center justify-content-center bg-neutral-50" style="height: 110px;">
                                    <img src="${objectUrl}" alt="Gallery Image" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                                </div>
                                
                                <input type="hidden" name="${galleryName}[${newIndex}][type]" value="upload">
                                <input type="file" name="${galleryName}[${newIndex}][file]" class="d-none">
                                
                                <div class="gallery-thumb-actions d-flex align-items-center justify-content-end p-8 bg-neutral-100 border-top border-neutral-200">
                                    <button type="button" class="btn btn-xs btn-outline-danger btn-icon gallery-remove-btn" title="Delete Image">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    const $card = $(cardHtml);
                    grid.append($card);

                    // Set standard file programmatically using DataTransfer API
                    const fileInput = $card.find('input[type="file"]');
                    if (fileInput.length) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput[0].files = dataTransfer.files;
                    }
                });

                // Clear input
                input.value = '';

                // Re-index gallery
                reindexGallery(grid);
            });

            // Handle Gallery Image Deletion
            $(document).on('click', '.gallery-remove-btn', function() {
                const card = $(this).closest('.gallery-card-col');
                const grid = card.closest('.gallery-sortable-grid');

                window.openAppConfirm({
                    title: 'Remove Image',
                    message: 'Are you sure you want to remove this image? You will need to save the page to persist this change.',
                    buttonText: 'Yes, Remove',
                    buttonClass: 'btn btn-sm btn-danger',
                    onConfirm: function() {
                        card.fadeOut(300, function() {
                            card.remove();
                            reindexGallery(grid);
                        });
                    }
                });
            });

            // Initialize sorting for Galleries (entire card is draggable except the delete button)
            $('.gallery-sortable-grid').each(function() {
                const grid = $(this);
                grid.sortable({
                    cancel: '.gallery-remove-btn',
                    update: function(event, ui) {
                        reindexGallery(grid);
                    },
                    placeholder: "col-xl-2 col-md-3 col-sm-4 mb-16 rounded-8 bg-neutral-100 border dashed border-neutral-300"
                });
            });

            // Function to re-index gallery inputs sequentially
            function reindexGallery(grid) {
                const galleryName = grid.data('gallery-name');
                grid.find('.gallery-card-col').each(function(index) {
                    const card = $(this);
                    card.attr('data-index', index);

                    card.find('input').each(function() {
                        const input = $(this);
                        const name = input.attr('name');
                        if (name) {
                            const oldIndexPattern = new RegExp(galleryName + '\\[\\d+\\]', 'g');
                            const newName = name.replace(oldIndexPattern, galleryName + '[' + index + ']');
                            input.attr('name', newName);
                        }
                    });
                });
            }
        });
    </script>
@endsection

@section('style')
    <style>
        textarea.form-control,
        textarea.form-control-sm {
            height: auto !important;
        }

        .quill-editor-wrapper {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--input-form-light);
            background: #fff;
            transition: border-color 0.2s ease;
        }
        
        .quill-editor-wrapper:focus-within {
            border-color: var(--primary-600) !important;
        }

        .quill-editor-wrapper .ql-toolbar.ql-snow {
            border: none !important;
            border-bottom: 1px solid var(--input-form-light) !important;
            background: #f8fafc;
            padding: 8px 12px;
        }

        .quill-editor-wrapper .ql-container.ql-snow {
            border: none !important;
            font-family: inherit;
            font-size: 14px;
            height: 300px !important;
            min-height: 250px !important;
        }

        .quill-editor-wrapper .ql-editor {
            height: 300px !important;
            min-height: 250px !important;
            padding: 16px;
        }

        .settings-current-media {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            border-radius: 8px;
            padding: 8px 12px;
        }

        .settings-current-media img {
            width: 80px;
            height: 50px;
            border-radius: 6px;
            object-fit: contain;
            background: #fff;
            border: 1px solid var(--input-form-light);
            flex-shrink: 0;
            padding: 4px;
        }

        .settings-file-upload {
            width: 100%;
            position: relative;
        }

        .settings-file-upload__input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .settings-file-upload__label {
            min-height: 42px;
            width: 100%;
            border: 1px solid var(--input-form-light);
            border-radius: 8px;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 12px;
            margin-bottom: 0;
            cursor: pointer;
            color: var(--text-secondary-light);
            transition: border-color 0.2s ease;
        }
        
        .settings-file-upload__label:hover {
            border-color: var(--primary-600);
        }

        .settings-file-upload__icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: var(--primary-50);
            color: var(--primary-600);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .settings-file-upload__text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
        }

        .settings-selected-file {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            color: var(--text-secondary-light);
            font-size: 13px;
            background: #f0fdf4;
            border: 1px dashed #bbf7d0;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .settings-selected-file img {
            width: 56px;
            height: 36px;
            border-radius: 6px;
            object-fit: contain;
            background: #fff;
            border: 1px solid #bbf7d0;
            padding: 2px;
            flex-shrink: 0;
        }

        .settings-selected-file__name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .cursor-move {
            cursor: move;
        }

        .ui-state-highlight {
            border: 2px dashed var(--primary-200);
            background: var(--primary-50);
            min-height: 150px;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .repeater-widget {
            background: #fff;
        }

        .repeater-row {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            border-radius: 8px !important;
        }

        .repeater-row:hover {
            border-color: var(--primary-300) !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04) !important;
        }

        .gallery-uploader-trigger {
            border-style: dashed !important;
            transition: border-color 0.2s ease, background-color 0.2s ease;
        }

        .gallery-uploader-trigger:hover {
            border-color: var(--primary-600) !important;
            background-color: var(--primary-50) !important;
        }

        .gallery-thumb-card {
            cursor: grab;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            border-radius: 8px !important;
        }

        .gallery-thumb-card:hover {
            border-color: var(--primary-300) !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04) !important;
        }

        .gallery-thumb-card:active {
            cursor: grabbing;
        }

        .select-option-card {
            transition: all 0.2s ease;
        }
        .select-option-card:hover {
            border-color: var(--primary-600) !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04) !important;
        }
        .nested-repeater-row {
            transition: all 0.2s ease;
        }
    </style>
@endsection
