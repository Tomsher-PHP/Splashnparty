<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white py-3">
        <div class="card-title mb-0">SEO Section</div>
    </div>

    <div class="card-body">
        <div class="row">
            {{-- META TITLE --}}
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Meta Title
                </label>
                <input type="text"
                    name="meta_title"
                    class="form-control form-control-sm"
                    placeholder="Meta Title"
                    value="{{ old('meta_title', $model->meta_title ?? '') }}">
            </div>

            {{-- META DESCRIPTION --}}
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Meta Description
                </label>
                <textarea name="meta_description"
                    rows="2"
                    class="form-control"
                    placeholder="Meta Description">{{ old('meta_description', $model->meta_description ?? '') }}</textarea>
            </div>

            {{-- META KEYWORDS --}}
            <div class="col-md-6 mb-20">

                <label class="form-label fw-semibold">
                    Meta Keywords
                </label>
                <input type="text"
                    name="meta_keywords"
                    class="form-control form-control-sm"
                    placeholder="keyword1, keyword2"
                    value="{{ old('meta_keywords', $model->meta_keywords ?? '') }}">
            </div>

            {{-- OG TITLE --}}
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    OG Title
                </label>
                <input type="text"
                    name="og_title"
                    class="form-control form-control-sm"
                    placeholder="OG Title"
                    value="{{ old('og_title', $model->og_title ?? '') }}">
            </div>

            {{-- OG DESCRIPTION --}}
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    OG Description
                </label>
                <textarea name="og_description"
                    rows="2"
                    class="form-control"
                    placeholder="OG Description">{{ old('og_description', $model->og_description ?? '') }}</textarea>
            </div>

            {{-- OG IMAGE --}}
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    OG Image
                </label>
                <input type="file"
                    name="og_image"
                    class="form-control form-control-sm">
                @error('og_image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                @if(!empty($model?->og_image))
                    <div class="mt-3">
                        <div class="og-image-wrapper position-relative d-inline-block">
                            <img src="{{ asset($model->og_image) }}"
                                class="rounded border og-image-preview">
                            <div class="og-image-overlay remove-og-image">
                                <button type="button"
                                        class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden"
                        name="remove_og_image"
                        id="remove_og_image"
                        value="0">
                @endif
            </div>

            {{-- TWITTER TITLE --}}
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Twitter Title
                </label>

                <input type="text"
                    name="twitter_title"
                    class="form-control form-control-sm"
                    placeholder="Twitter Title"
                    value="{{ old('twitter_title', $model->twitter_title ?? '') }}">
            </div>

            {{-- TWITTER DESCRIPTION --}}
            <div class="col-md-6 mb-20">
                <label class="form-label fw-semibold">
                    Twitter Description
                </label>
                <textarea name="twitter_description"
                    rows="2"
                    class="form-control"
                    placeholder="Twitter Description">{{ old('twitter_description', $model->twitter_description ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

<script>

document.addEventListener('click', function(e) {

    if (e.target.closest('.remove-og-image')) {

        if (!confirm('Remove OG image?')) {
            return;
        }

        document.getElementById('remove_og_image').value = 1;

        e.target.closest('.position-relative').remove();
    }

});

</script>