@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-4">Edit Staff</h6>
            <p class="mb-0 text-secondary-light">Update staff details and role assignment.</p>
        </div>
        <a href="{{ route('staffs.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Back to Staffs
        </a>
    </div>

    <form action="{{ route('staffs.update', $staff) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row gy-4 align-items-end">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $staff->name) }}"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror"
                                    placeholder="Enter staff name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email', $staff->email) }}"
                                    class="form-control form-control-sm @error('email') is-invalid @enderror"
                                    placeholder="Enter email address">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $staff->phone) }}"
                                    class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                    placeholder="Enter phone number">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="role_id" class="form-label fw-semibold">Role <span
                                        class="text-danger">*</span></label>
                                <select id="role_id" name="role_id"
                                    class="form-control form-control-sm @error('role_id') is-invalid @enderror">
                                    <option value="">Select role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ (string) old('role_id', $staffRoleId) === (string) $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" id="password" name="password" autocomplete="new-password"
                                    class="form-control form-control-sm @error('password') is-invalid @enderror"
                                    placeholder="Leave blank to keep current password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="image" class="form-label fw-semibold">Image</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="staff-form-avatar">
                                        @if ($staff->image)
                                            <img src="{{ asset('storage/' . $staff->image) }}" alt="{{ $staff->name }}">
                                        @else
                                            <span>{{ strtoupper(substr($staff->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="staff-file-upload @error('image') is-invalid @enderror">
                                            <input type="file" id="image" name="image" class="staff-file-upload__input"
                                                accept="image/png,image/jpeg,image/webp" data-file-input>
                                            <label for="image" class="staff-file-upload__label">
                                                <span class="staff-file-upload__icon">
                                                    <i class="ri-image-add-line"></i>
                                                </span>
                                                <span class="staff-file-upload__text" data-file-name>Choose image</span>
                                            </label>
                                        </div>
                                        <div class="staff-selected-file d-none mt-12" data-selected-file-wrap>
                                            <img src="" alt="Selected image preview" class="d-none"
                                                data-selected-image-preview>
                                            <span class="staff-selected-file__name" data-selected-file-name></span>
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                        <i class="ri-save-line"></i>
                        Update Staff
                    </button>
                    <a href="{{ route('staffs.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
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
            const fileName = file ? file.name : 'Choose image';
            const fieldWrap = input.closest('.flex-grow-1');
            const selectedWrap = fieldWrap?.querySelector('[data-selected-file-wrap]');
            const selectedName = fieldWrap?.querySelector('[data-selected-file-name]');
            const imagePreview = fieldWrap?.querySelector('[data-selected-image-preview]');
            const fileNameElement = input.closest('.staff-file-upload')?.querySelector('[data-file-name]');

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

            const imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];
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
        .staff-form-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--primary-50);
            color: var(--primary-600);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .staff-form-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .staff-file-upload {
            width: 100%;
        }

        .staff-file-upload__input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .staff-file-upload__label {
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

        .staff-file-upload__icon {
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

        .staff-file-upload__text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .staff-file-upload.is-invalid .staff-file-upload__label {
            border-color: var(--danger-main) !important;
        }

        .staff-selected-file {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            color: var(--text-secondary-light);
            font-size: 13px;
        }

        .staff-selected-file img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            flex-shrink: 0;
        }

        .staff-selected-file__name {
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
