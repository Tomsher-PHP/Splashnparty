<div class="row gy-4">
    <div class="col-md-6">
        <label for="title" class="form-label fw-semibold">Label / Title <span class="text-danger">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title', $headerMenu->title ?? '') }}"
            class="form-control form-control-sm @error('title') is-invalid @enderror" placeholder="Enter menu title (e.g. Home, About Us)">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="url" class="form-label fw-semibold">Link / URL</label>
        <input type="text" id="url" name="url" value="{{ old('url', $headerMenu->url ?? '') }}"
            class="form-control form-control-sm @error('url') is-invalid @enderror" placeholder="Enter link (e.g. /, /about-us, or full URL)">
        @error('url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="parent_id" class="form-label fw-semibold">Parent Menu</label>
        <select id="parent_id" name="parent_id" class="form-control form-control-sm @error('parent_id') is-invalid @enderror">
            <option value="">-- Main Menu Item (No Parent) --</option>
            @foreach ($parentMenus as $parent)
                <option value="{{ $parent->id }}"
                    {{ old('parent_id', $headerMenu->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                    {{ $parent->title }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="sort_order" class="form-label fw-semibold">Sort Order <span class="text-danger">*</span></label>
        <input type="number" id="sort_order" name="sort_order"
            value="{{ old('sort_order', $headerMenu->sort_order ?? 0) }}" min="0"
            class="form-control form-control-sm @error('sort_order') is-invalid @enderror" placeholder="Enter sort order">
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="icon" class="form-label fw-semibold">Icon Image</label>
        <input type="file" id="icon" name="icon"
            class="form-control form-control-sm @error('icon') is-invalid @enderror">
        @if(isset($headerMenu) && $headerMenu->icon)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $headerMenu->icon) }}" alt="Current Icon" class="w-32-px h-32-px object-fit-cover rounded border">
                <span class="text-xs text-secondary ms-2">Current Icon</span>
            </div>
        @endif
        @error('icon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select id="status" name="status"
            class="form-control form-control-sm @error('status') is-invalid @enderror">
            <option value="1"
                {{ old('status', isset($headerMenu) ? (string) (int) $headerMenu->status : '1') === '1' ? 'selected' : '' }}>
                Active
            </option>
            <option value="0"
                {{ old('status', isset($headerMenu) ? (string) (int) $headerMenu->status : '1') === '0' ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
