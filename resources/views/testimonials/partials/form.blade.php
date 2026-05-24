<div class="row gy-4">
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">Author Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $testimonial->name ?? '') }}"
            class="form-control form-control-sm @error('name') is-invalid @enderror" placeholder="Enter author's name">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title', $testimonial->title ?? '') }}"
            class="form-control form-control-sm @error('title') is-invalid @enderror" placeholder="Write a title for the testimonial">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="star_rating" class="form-label fw-semibold">Star Rating <span class="text-danger">*</span></label>
        <select id="star_rating" name="star_rating"
            class="form-control form-control-sm @error('star_rating') is-invalid @enderror">
            <option value="">Select Rating</option>
            @for ($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}"
                    {{ old('star_rating', isset($testimonial) ? (string) $testimonial->star_rating : '') === (string) $i ? 'selected' : '' }}>
                    {{ $i }} {{ Str::plural('Star', $i) }}
                </option>
            @endfor
        </select>
        @error('star_rating')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="sort_order" class="form-label fw-semibold">Sort Order <span class="text-danger">*</span></label>
        <input type="number" id="sort_order" name="sort_order"
            value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" min="0"
            class="form-control form-control-sm @error('sort_order') is-invalid @enderror" placeholder="Enter sort order">
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select id="status" name="status"
            class="form-control form-control-sm @error('status') is-invalid @enderror">
            <option value="1"
                {{ old('status', isset($testimonial) ? (string) (int) $testimonial->status : '1') === '1' ? 'selected' : '' }}>
                Active
            </option>
            <option value="0"
                {{ old('status', isset($testimonial) ? (string) (int) $testimonial->status : '1') === '0' ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label fw-semibold">Testimonial Description <span class="text-danger">*</span></label>
        <textarea id="description" name="description" rows="4"
            class="form-control @error('description') is-invalid @enderror" placeholder="Write testimonial details...">{{ old('description', $testimonial->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
