@section('script')
    <script>
        document.addEventListener('change', function(event) {
            if (!event.target.matches('[data-file-input]')) {
                return;
            }

            const input = event.target;
            const file = input.files?.[0] || null;
            const fileName = file ? file.name : 'Choose file';
            const bannerType = document.querySelector('[data-banner-type]')?.value || 'image';
            const maxBytes = bannerType === 'video' ? 50 * 1024 * 1024 : 4 * 1024 * 1024;
            const maxLabel = bannerType === 'video' ? '50 MB' : '4 MB';
            const errorElement = document.querySelector('[data-file-client-error]');
            const formGroup = input.closest('.col-12');
            const imagePreview = formGroup?.querySelector('[data-selected-image-preview]');
            const videoPreview = formGroup?.querySelector('[data-selected-video-preview]');
            const selectedWrap = formGroup?.querySelector('[data-selected-file-wrap]');
            const selectedName = formGroup?.querySelector('[data-selected-file-name]');
            const fileNameElement = input.closest('.banner-file-upload')?.querySelector('[data-file-name]');

            if (fileNameElement) {
                fileNameElement.textContent = fileName;
            }

            if (selectedWrap && selectedName) {
                selectedName.textContent = file ? fileName : '';
                selectedWrap.classList.toggle('d-none', !file);
            }

            [imagePreview, videoPreview].forEach(function(preview) {
                if (preview) {
                    preview.classList.add('d-none');
                    preview.removeAttribute('src');
                }
            });

            if (errorElement) {
                errorElement.classList.add('d-none');
                errorElement.textContent = '';
            }

            if (file && file.size > maxBytes) {
                input.value = '';

                if (fileNameElement) {
                    fileNameElement.textContent = 'Choose file';
                }

                if (selectedWrap && selectedName) {
                    selectedName.textContent = '';
                    selectedWrap.classList.add('d-none');
                }

                if (errorElement) {
                    errorElement.textContent = `Selected ${bannerType} must be ${maxLabel} or smaller.`;
                    errorElement.classList.remove('d-none');
                }

                return;
            }

            const imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            const videoExtensions = ['mp4', 'webm', 'mov', 'ogg'];
            const extension = fileName.split('.').pop()?.toLowerCase();
            const isImage = file && (file.type.startsWith('image/') || imageExtensions.includes(extension));
            const isVideo = file && (file.type.startsWith('video/') || videoExtensions.includes(extension));

            if (isImage && imagePreview) {
                imagePreview.src = URL.createObjectURL(file);
                imagePreview.classList.remove('d-none');
            }

            if (isVideo && videoPreview) {
                videoPreview.src = URL.createObjectURL(file);
                videoPreview.classList.remove('d-none');
            }
        });

        document.querySelectorAll('[data-banner-type]').forEach(function(select) {
            const fileInput = document.querySelector('[data-file-input]');

            function updateAccept() {
                if (!fileInput) {
                    return;
                }

                fileInput.accept = select.value === 'video'
                    ? 'video/mp4,video/webm,video/quicktime,video/ogg'
                    : 'image/png,image/jpeg,image/webp';
            }

            select.addEventListener('change', updateAccept);
            updateAccept();
        });
    </script>
@endsection

@section('style')
    <style>
        .banner-current-file {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .banner-current-file img,
        .banner-current-file video {
            width: 96px;
            height: 54px;
            border-radius: 8px;
            object-fit: cover;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            flex-shrink: 0;
        }

        .banner-file-upload {
            width: 100%;
        }

        .banner-file-upload__input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .banner-file-upload__label {
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

        .banner-file-upload__icon {
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

        .banner-file-upload__text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .banner-file-upload.is-invalid .banner-file-upload__label {
            border-color: var(--danger-main) !important;
        }

        .banner-selected-file {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            color: var(--text-secondary-light);
            font-size: 13px;
        }

        .banner-selected-file img,
        .banner-selected-file video {
            width: 64px;
            height: 38px;
            border-radius: 6px;
            object-fit: cover;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            flex-shrink: 0;
        }

        .banner-selected-file__name {
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
