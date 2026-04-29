<div class="row gy-4">
    <div class="col-md-6">
        <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title', $clientLogo->title ?? '') }}"
            class="form-control form-control-sm @error('title') is-invalid @enderror" placeholder="Enter title">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="link" class="form-label fw-semibold">Link</label>
        <input type="text" id="link" name="link" value="{{ old('link', $clientLogo->link ?? '') }}"
            class="form-control form-control-sm @error('link') is-invalid @enderror" placeholder="Enter link">
        @error('link')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="sort_order" class="form-label fw-semibold">Sort Order <span class="text-danger">*</span></label>
        <input type="number" id="sort_order" name="sort_order"
            value="{{ old('sort_order', $clientLogo->sort_order ?? 0) }}" min="0"
            class="form-control form-control-sm @error('sort_order') is-invalid @enderror" placeholder="Enter sort order">
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select id="status" name="status"
            class="form-control form-control-sm @error('status') is-invalid @enderror">
            <option value="1"
                {{ old('status', isset($clientLogo) ? (string) $clientLogo->status : '1') === '1' ? 'selected' : '' }}>
                Active
            </option>
            <option value="0"
                {{ old('status', isset($clientLogo) ? (string) $clientLogo->status : '1') === '0' ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="logo" class="form-label fw-semibold">Logo @if (empty($clientLogo))
                <span class="text-danger">*</span>
            @endif
        </label>
        @isset($clientLogo)
            <div class="client-logo-current-file mb-12">
                <img src="{{ asset('storage/' . $clientLogo->logo) }}" alt="{{ $clientLogo->title }}">
            </div>
        @endisset
        <div class="client-logo-file-upload @error('logo') is-invalid @enderror">
            <input type="file" id="logo" name="logo" class="client-logo-file-upload__input"
                accept="image/png,image/jpeg,image/webp,image/svg+xml" data-logo-input>
            <label for="logo" class="client-logo-file-upload__label">
                <span class="client-logo-file-upload__icon">
                    <i class="ri-upload-cloud-2-line"></i>
                </span>
                <span class="client-logo-file-upload__text" data-logo-name>Choose logo</span>
            </label>
        </div>
        <div class="client-logo-selected-file d-none mt-12" data-logo-selected-wrap>
            <img src="" alt="Selected logo preview" class="d-none" data-logo-preview>
            <span class="client-logo-selected-file__name" data-logo-selected-name></span>
        </div>
        <div class="text-secondary-light text-xxs mt-2">JPG, PNG, WEBP, or SVG up to 4 MB.</div>
        <div class="invalid-feedback d-block d-none" data-logo-client-error></div>
        @error('logo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
