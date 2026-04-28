@section('script')
    <script>
        document.querySelectorAll('[data-file-input]').forEach(function(input) {
            input.addEventListener('change', function() {
                const fileName = input.files?.[0]?.name || 'Choose file';
                const bannerType = document.querySelector('[data-banner-type]')?.value || 'image';
                const maxBytes = bannerType === 'video' ? 50 * 1024 * 1024 : 4 * 1024 * 1024;
                const maxLabel = bannerType === 'video' ? '50 MB' : '4 MB';
                const errorElement = document.querySelector('[data-file-client-error]');

                input.closest('.banner-file-upload')?.querySelector('[data-file-name]').textContent = fileName;

                if (errorElement) {
                    errorElement.classList.add('d-none');
                    errorElement.textContent = '';
                }

                if (input.files?.[0] && input.files[0].size > maxBytes) {
                    input.value = '';
                    input.closest('.banner-file-upload')?.querySelector('[data-file-name]').textContent = 'Choose file';

                    if (errorElement) {
                        errorElement.textContent = `Selected ${bannerType} must be ${maxLabel} or smaller.`;
                        errorElement.classList.remove('d-none');
                    }
                }
            });
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

        .form-control::placeholder {
            font-size: 13px !important;
            opacity: 0.7 !important;
        }
    </style>
@endsection
