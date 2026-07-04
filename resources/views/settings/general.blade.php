@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-4">General Settings</h6>
            <p class="mb-0 text-secondary-light">Manage common website identity, contact, and social details.</p>
        </div>
    </div>

    <form action="{{ route('general-settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">
            @foreach ($settingGroups as $group)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-md fw-semibold mb-20">{{ $group['title'] }}</h6>
                            <div class="row gy-4">
                                @foreach ($group['fields'] as $field)
                                    @php
                                        $key = $field['key'];
                                        $type = $field['type'] ?? 'text';
                                        $value = old($key, $settingValues[$key] ?? '');
                                        $columnClass = in_array($type, ['textarea', 'file'], true)
                                            ? 'col-md-6'
                                            : 'col-md-6';
                                    @endphp

                                    <div class="{{ $columnClass }}">
                                        <label for="{{ $key }}"
                                            class="form-label fw-semibold">{!! $field['label'] !!}</label>

                                        @if ($type === 'textarea')
                                            <textarea id="{{ $key }}" name="{{ $key }}" rows="{{ $field['rows'] ?? 3 }}"
                                                class="form-control @error($key) is-invalid @enderror"
                                                placeholder="{{ $field['placeholder'] ?? '' }}">{{ $value }}</textarea>
                                        @elseif ($type === 'file')
                                            @if (!empty($settingValues[$key]))
                                                <div class="settings-current-media mb-12">
                                                    <img src="{{ asset('storage/' . $settingValues[$key]) }}"
                                                        alt="{{ $field['label'] }}">
                                                    {{-- <span class="text-secondary-light small">{{ $settingValues[$key] }}</span> --}}
                                                </div>
                                            @endif
                                            <div class="settings-file-upload @error($key) is-invalid @enderror">
                                                <input type="file" id="{{ $key }}" name="{{ $key }}"
                                                    class="settings-file-upload__input"
                                                    accept="{{ $field['accept'] ?? '' }}" data-file-input>
                                                <label for="{{ $key }}" class="settings-file-upload__label">
                                                    <span class="settings-file-upload__icon">
                                                        <i class="ri-upload-cloud-2-line"></i>
                                                    </span>
                                                    <span class="settings-file-upload__text" data-file-name>Choose
                                                        file</span>
                                                </label>
                                            </div>
                                            <div class="settings-selected-file d-none mt-12" data-selected-file-wrap>
                                                <img src="" alt="Selected file preview" class="d-none"
                                                    data-selected-image-preview>
                                                <span class="settings-selected-file__name" data-selected-file-name></span>
                                            </div>
                                        @else
                                            <input type="{{ $type }}" id="{{ $key }}"
                                                name="{{ $key }}" value="{{ $value }}"
                                                class="form-control form-control-sm @error($key) is-invalid @enderror"
                                                placeholder="{{ $field['placeholder'] ?? '' }}">
                                        @endif

                                        @error($key)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    @can('edit_general_settings')
                        <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                            <i class="ri-save-line"></i>
                            Save Settings
                        </button>
                    @endcan
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        document.addEventListener('change', function(event) {
            if (!event.target.matches('[data-file-input]')) {
                return;
            }

            const input = event.target;
            const file = input.files?.[0] || null;
            const fileName = file ? file.name : 'Choose file';
            const fieldWrap = input.closest('.col-md-6');
            const selectedWrap = fieldWrap?.querySelector('[data-selected-file-wrap]');
            const selectedName = fieldWrap?.querySelector('[data-selected-file-name]');
            const imagePreview = fieldWrap?.querySelector('[data-selected-image-preview]');
            const fileNameElement = input.closest('.settings-file-upload')?.querySelector('[data-file-name]');

            if (fileNameElement) {
                fileNameElement.textContent = fileName;
            }

            if (selectedWrap && selectedName) {
                selectedName.textContent = file ? fileName : '';
                selectedWrap.classList.toggle('d-none', !file);
            }

            if (imagePreview) {
                imagePreview.classList.add('d-none');
                imagePreview.removeAttribute('src');
            }

            const imageExtensions = ['ico', 'jpg', 'jpeg', 'png', 'webp', 'svg'];
            const extension = fileName.split('.').pop()?.toLowerCase();
            const isImage = file && (file.type.startsWith('image/') || imageExtensions.includes(extension));

            if (isImage && imagePreview) {
                imagePreview.src = URL.createObjectURL(file);
                imagePreview.classList.remove('d-none');
            }
        });
    </script>
@endsection

@section('style')
    <style>
        .settings-current-media {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .settings-current-media img {
            width: 96px;
            height: 54px;
            border-radius: 8px;
            object-fit: contain;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            flex-shrink: 0;
            padding: 6px;
        }

        .settings-file-upload {
            width: 100%;
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

        .settings-file-upload__text,
        .settings-current-media span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .settings-file-upload.is-invalid .settings-file-upload__label {
            border-color: var(--danger-main) !important;
        }

        .settings-selected-file {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            color: var(--text-secondary-light);
            font-size: 13px;
        }

        .settings-selected-file img {
            width: 56px;
            height: 36px;
            border-radius: 6px;
            object-fit: contain;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            padding: 4px;
            flex-shrink: 0;
        }

        .settings-selected-file__name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .form-control::placeholder {
            font-size: 13px !important;
            opacity: 0.7 !important;
        }
    </style>
@endsection
