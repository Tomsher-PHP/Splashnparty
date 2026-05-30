<div class="justify-content-center">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($method == 'PUT')
        @method('PUT')
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"> {{ $title }} </h6>
                    <div>
                        <a href="{{ route('video-gallery.index') }}"
                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <i class="ri-arrow-left-line"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-20">
                    {{-- Category --}}
                    <div class="col-md-6 mb-20">
                        <label class="form-label fw-semibold">
                            Category Name
                        </label>
                        <input type="text"
                            name="category_name"
                            class="form-control form-control-sm"
                            placeholder="Enter category name"
                            value="{{ old('category_name', $gallery->category_name ?? '') }}">
                        @error('category_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-20">
                        <label class="form-label fw-semibold">
                            Status
                        </label>
                        <select name="status"
                            class="form-select form-select-sm">
                            <option value="1"
                                {{ old('status', $gallery->status ?? 1) == 1 ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0"
                                {{ old('status', $gallery->status ?? 1) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Videos --}}
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-semibold mb-0">
                            YouTube Videos
                        </label>
                    </div>

                    <div id="video-wrapper" class="row g-3 sortable-video-gallery">
                        @php
                            $youtubeLinks = old('youtube_link');
                            if (!$youtubeLinks) {
                                if (isset($gallery) && $gallery->youtube_link) {
                                    $youtubeLinks = is_array($gallery->youtube_link)
                                        ? $gallery->youtube_link
                                        : json_decode($gallery->youtube_link, true);

                                } else {
                                    $youtubeLinks = [''];
                                }
                            }

                            // FINAL SAFETY
                            $youtubeLinks = is_array($youtubeLinks)
                                ? $youtubeLinks
                                : [''];
                        @endphp

                        @foreach($youtubeLinks as $key => $link)
                        <div class="col-md-6 sortable-item video-item">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="video-title fw-semibold">
                                            <i class="ri-youtube-fill text-danger"></i>
                                            Video {{ $key + 1 }}
                                        </div>
                                        <button type="button"
                                                class="btn btn-sm btn-light text-danger remove-video"
                                                onclick="confirmRemoveVideo(this)">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                    <input type="text"
                                        name="youtube_link[]"
                                        class="form-control form-control-sm"
                                        placeholder="Paste YouTube link"
                                        value="{{ $link }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <small class="text-muted d-block mt-2">
                        Drag videos to reorder
                    </small>
                </div>

                <!-- Add more button -->
                <div class="col-md-12 text-end">
                    <button type="button"
                        class="btn btn-primary btn-sm"
                        id="add-video">
                        <i class="ri-add-line"></i>
                        Add More Video Links
                    </button>
                </div>

                <!-- SEO COMPONENT -->
                @include('components.seo-fields', [
                    'model' => $gallery ?? null
                ])

                    
                {{-- Submit --}}
                <div class="col-md-12 text-end mt-5">
                    <button type="submit"
                        class="btn btn-sm btn-primary-600 d-inline-flex align-items-center">
                        {{ $buttonText }}
                    </button>
                    <button type="reset"
                        class="btn btn-sm btn-outline-secondary">
                        Cancel
                    </button>
                </div>
                
            </div>
        </div>
    </form>
</div>

@section('script')

<script>

    document.addEventListener('DOMContentLoaded', function () {
        toggleRemoveButtons();
        let wrapper = document.getElementById('video-wrapper');

        // SORTABLE
        new Sortable(wrapper, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',

            onEnd: function () {
                updateTitles();
            }
        });

        // ADD VIDEO
        document.getElementById('add-video').addEventListener('click', function () {
            let count = document.querySelectorAll('.video-item').length + 1;
            let div = document.createElement('div');

            div.classList.add(
                'col-md-6',
                'sortable-item',
                'video-item'
            );

            div.innerHTML = `
                <div class="card border shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="video-title fw-semibold">
                                <i class="ri-youtube-fill text-danger"></i>
                                Video ${count}
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-light text-danger remove-video"
                                    onclick="confirmRemoveVideo(this)">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                        <input type="text"
                            name="youtube_link[]"
                            class="form-control form-control-sm"
                            placeholder="Paste YouTube link">
                    </div>
                </div>
            `;

            wrapper.appendChild(div);
            updateTitles();
            toggleRemoveButtons();
        });

        updateTitles();
    });

    // CONFIRM REMOVE
    function confirmRemoveVideo(button) {
        let items = document.querySelectorAll('.video-item');
        if (items.length === 1) {
            return;
        }

        window.openAppConfirm({
            title: 'Remove Video',
            message: 'Are you sure you want to remove this video?',
            buttonText: 'Yes, Remove',
            buttonClass: 'btn btn-sm btn-danger',
            onConfirm: function () {
                button.closest('.video-item').remove();
                updateTitles();
                toggleRemoveButtons();
            }
        });
    }

    // UPDATE VIDEO TITLES
    function updateTitles() {
        let items = document.querySelectorAll('.video-item');
        items.forEach((item, index) => {
            item.querySelector('.video-title').innerHTML = `
                <i class="ri-youtube-fill text-danger"></i>
                Video ${index + 1}
            `;
        });
    }

    // TOGGLE DELETE BUTTONS
    function toggleRemoveButtons() {
        let items = document.querySelectorAll('.video-item');
        document.querySelectorAll('.remove-video').forEach(button => {
            if (items.length === 1) {
                button.classList.add('d-none');
            } else {
                button.classList.remove('d-none');
            }
        });
    }
</script>
@endsection

