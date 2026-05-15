@section('script')
    <script>
        document.addEventListener('change', function(event) {
            if (!event.target.matches('[data-logo-input]')) {
                return;
            }

            var input = event.target;
            var file = input.files && input.files.length ? input.files[0] : null;
            var fileName = file ? file.name : 'Choose logo';
            var maxBytes = 4 * 1024 * 1024;
            var errorElement = document.querySelector('[data-logo-client-error]');
            var uploadWrap = input.closest('.client-logo-file-upload');
            var fileNameElement = uploadWrap ? uploadWrap.querySelector('[data-logo-name]') : null;
            var selectedWrap = document.querySelector('[data-logo-selected-wrap]');
            var selectedName = document.querySelector('[data-logo-selected-name]');
            var preview = document.querySelector('[data-logo-preview]');

            if (fileNameElement) {
                fileNameElement.textContent = fileName;
            }

            if (selectedWrap && selectedName) {
                selectedName.textContent = file ? fileName : '';
                selectedWrap.classList.toggle('d-none', !file);
            }

            if (preview) {
                preview.classList.add('d-none');
                preview.removeAttribute('src');
            }

            if (errorElement) {
                errorElement.classList.add('d-none');
                errorElement.textContent = '';
            }

            if (file && file.size > maxBytes) {
                input.value = '';

                if (fileNameElement) {
                    fileNameElement.textContent = 'Choose logo';
                }

                if (selectedWrap && selectedName) {
                    selectedName.textContent = '';
                    selectedWrap.classList.add('d-none');
                }

                if (errorElement) {
                    errorElement.textContent = 'Selected logo must be 4 MB or smaller.';
                    errorElement.classList.remove('d-none');
                }

                return;
            }

            var imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
            var extension = fileName.split('.').pop().toLowerCase();
            var isImage = file && (file.type.indexOf('image/') === 0 || imageExtensions.indexOf(extension) !== -1);

            if (isImage && preview) {
                var reader = new FileReader();
                reader.onload = function(loadEvent) {
                    preview.src = loadEvent.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection

@section('style')
    <style>
        .client-logo-current-file {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .client-logo-current-file img {
            width: 112px;
            height: 64px;
            border-radius: 8px;
            object-fit: contain;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            flex-shrink: 0;
            padding: 8px;
        }

        .client-logo-file-upload {
            width: 100%;
        }

        .client-logo-file-upload__input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .client-logo-file-upload__label {
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

        .client-logo-file-upload__icon {
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

        .client-logo-file-upload__text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .client-logo-file-upload.is-invalid .client-logo-file-upload__label {
            border-color: var(--danger-main) !important;
        }

        .client-logo-selected-file {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            color: var(--text-secondary-light);
            font-size: 13px;
        }

        .client-logo-selected-file img {
            width: 56px;
            height: 36px;
            border-radius: 6px;
            object-fit: contain;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            padding: 4px;
            flex-shrink: 0;
        }

        .client-logo-selected-file__name {
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
