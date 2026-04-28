<div class="row gy-4">
    <div class="col-md-6">
        <label for="title" class="form-label fw-semibold">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $banner->title ?? '') }}"
            class="form-control form-control-sm @error('title') is-invalid @enderror" placeholder="Enter title">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="subtitle" class="form-label fw-semibold">Subtitle</label>
        <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}"
            class="form-control form-control-sm @error('subtitle') is-invalid @enderror" placeholder="Enter subtitle">
        @error('subtitle')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="btn_text" class="form-label fw-semibold">Button Text</label>
        <input type="text" id="btn_text" name="btn_text" value="{{ old('btn_text', $banner->btn_text ?? '') }}"
            class="form-control form-control-sm @error('btn_text') is-invalid @enderror"
            placeholder="Enter button text">
        @error('btn_text')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="btn_link" class="form-label fw-semibold">Button Link</label>
        <input type="text" id="btn_link" name="btn_link" value="{{ old('btn_link', $banner->btn_link ?? '') }}"
            class="form-control form-control-sm @error('btn_link') is-invalid @enderror"
            placeholder="Enter button link">
        @error('btn_link')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="banner_type" class="form-label fw-semibold">Banner Type <span class="text-danger">*</span></label>
        <select id="banner_type" name="banner_type"
            class="form-control form-control-sm @error('banner_type') is-invalid @enderror" data-banner-type>
            <option value="image"
                {{ old('banner_type', $banner->banner_type ?? 'image') === 'image' ? 'selected' : '' }}>
                Image
            </option>
            <option value="video" {{ old('banner_type', $banner->banner_type ?? '') === 'video' ? 'selected' : '' }}>
                Video
            </option>
        </select>
        @error('banner_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select id="status" name="status"
            class="form-control form-control-sm @error('status') is-invalid @enderror">
            <option value="1"
                {{ old('status', isset($banner) ? (string) $banner->status : '1') === '1' ? 'selected' : '' }}>
                Active
            </option>
            <option value="0"
                {{ old('status', isset($banner) ? (string) $banner->status : '1') === '0' ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="file" class="form-label fw-semibold">File @if (empty($banner))
                <span class="text-danger">*</span>
            @endif
        </label>
        @isset($banner)
            <div class="banner-current-file mb-12">
                @if ($banner->banner_type === 'video')
                    <video src="{{ asset('storage/' . $banner->file) }}" controls muted></video>
                @else
                    <img src="{{ asset('storage/' . $banner->file) }}" alt="{{ $banner->title ?: 'Banner' }}">
                @endif
                {{-- <span class="text-secondary-light small">{{ $banner->file }}</span> --}}
            </div>
        @endisset
        <div class="banner-file-upload @error('file') is-invalid @enderror">
            <input type="file" id="file" name="file" class="banner-file-upload__input"
                accept="image/png,image/jpeg,image/webp,video/mp4,video/webm,video/quicktime,video/ogg" data-file-input>
            <label for="file" class="banner-file-upload__label">
                <span class="banner-file-upload__icon">
                    <i class="ri-upload-cloud-2-line"></i>
                </span>
                <span class="banner-file-upload__text" data-file-name>Choose file</span>
            </label>
        </div>
        <div class="text-secondary-light text-xxs mt-2" data-file-help>
            Images: JPG, PNG, WEBP up to 4 MB. Videos: MP4, WEBM, MOV, OGG up to 50 MB.
        </div>
        <div class="invalid-feedback d-block d-none" data-file-client-error></div>
        @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
