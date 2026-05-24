<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? $generalSettings?->meta_title ?? $generalSettings?->site_name ?? env('APP_NAME') }}</title>
    @if ($generalSettings?->meta_description)
        <meta name="description" content="{{ $generalSettings->meta_description }}">
    @endif

    <link rel="icon"
        href="{{ $generalSettings?->favicon ? asset('storage/' . $generalSettings->favicon) : asset('assets/images/favicon.ico') }}"
        sizes="16x16">
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">

    <!-- Date picker css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/flatpickr.min.css') }}">

    <!-- Popup css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/magnific-popup.css') }}">
    <!-- Slick Slider css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/slick.css') }}">
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/prism.css') }}">

    <!-- quill css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/quill.snow.css') }}">

    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <style>
        body {
            font-size: 14px !important;
        }

        .custom-confirm-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1080;
            padding: 16px;
        }

        .custom-confirm-modal.is-open {
            display: flex;
        }

        .custom-confirm-modal__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .45);
        }

        .custom-confirm-modal__dialog {
            position: relative;
            width: 100%;
            max-width: 360px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, .22);
            overflow: hidden;
        }

        .custom-confirm-modal__header,
        .custom-confirm-modal__footer {
            padding: 16px 18px;
        }

        .custom-confirm-modal__body {
            padding: 0 18px 18px;
        }

        .custom-confirm-modal__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .custom-confirm-modal__footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .custom-confirm-modal__close {
            border: 0;
            background: transparent;
            color: var(--text-secondary-light);
            font-size: 18px;
            line-height: 1;
        }

        body.custom-confirm-modal-open {
            overflow: hidden;
        }
    </style>

    @yield('style')
</head>

<body>

    <!-- Theme Customization Structure Start -->
    <div class="body-overlay"></div>
    @include('includes.toast')

    <button type="button"
        class="theme-customization__button w-48-px h-48-px bg-primary-600 text-white rounded-circle d-flex justify-content-center align-items-center position-fixed end-0 bottom-0 mb-40 me-40 text-2xxl bg-hover-primary-700">
        <i class="ri-settings-3-line animate-spin"></i>
    </button>
    <div class="theme-customization-sidebar w-100 bg-base h-100vh overflow-y-auto position-fixed end-0 top-0 shadow-lg">
        <div class="d-flex align-items-center gap-3 py-16 px-24 justify-content-between border-bottom">
            <div>
                <h6 class="text-sm dark:text-white">Theme Settings</h6>
                <p class="text-xs mb-0 text-neutral-500 dark:text-neutral-200">Customize and preview instantly</p>
            </div>
            <button data-slot="button"
                class="theme-customization-sidebar__close text-neutral-900 bg-transparent text-hover-primary-600 d-flex text-xl">
                <i class="ri-close-fill"></i>
            </button>
        </div>

        <div class="d-flex flex-column gap-48 p-24 overflow-y-auto flex-grow-1">

            <div class="theme-setting-item">
                <h6 class="fw-medium text-primary-light text-md mb-3">Theme Mode</h6>
                <div class="d-grid grid-cols-3 gap-3 dark-light-mode">
                    <button type="button"
                        class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl active"
                        data-theme="light">
                        <i class="ri-sun-line"></i>
                    </button>
                    <button type="button"
                        class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl"
                        data-theme="dark">
                        <i class="ri-moon-line"></i>
                    </button>
                    <button type="button"
                        class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl"
                        data-theme="system">
                        <i class="ri-computer-line"></i>
                    </button>
                </div>
            </div>

            <div class="theme-setting-item">
                <h6 class="fw-medium text-primary-light text-md mb-3">Page Direction</h6>
                <div class="d-grid grid-cols-2 gap-3">
                    <button type="button"
                        class="theme-setting-item__btn ltr-mode-btn d-flex align-items-center justify-content-center gap-2 h-56-px rounded-3 text-xl">
                        <span><i class="ri-align-item-left-line"></i></span>
                        <span class="h6 text-sm font-medium mb-0">LTR</span>
                    </button>

                    <button type="button"
                        class="theme-setting-item__btn rtl-mode-btn d-flex align-items-center justify-content-center gap-2 h-56-px rounded-3 text-xl">
                        <span class="h6 text-sm font-medium mb-0">RTL</span>
                        <span><i class="ri-align-item-right-line"></i></span>
                    </button>
                </div>
            </div>

            <div class="theme-setting-item">
                <h6 class="fw-medium text-primary-light text-md mb-3">Color Schema</h6>
                <div class="d-grid grid-cols-3 gap-3">
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="blue">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #2563eb;"></span>
                        <span class="fw-medium mt-1" style="color: #2563eb;">Blue</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="red">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #dc2626;"></span>
                        <span class="fw-medium mt-1" style="color: #dc2626;">Red</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="green">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #16a34a;"></span>
                        <span class="fw-medium mt-1" style="color: #16a34a;">Green</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="yellow">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #ff9f29;"></span>
                        <span class="fw-medium mt-1" style="color: #ff9f29;">Yellow</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="cyan">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #00b8f2;"></span>
                        <span class="fw-medium mt-1" style="color: #00b8f2;">Cyan</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="violet">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #7c3aed;"></span>
                        <span class="fw-medium mt-1" style="color: #7c3aed;">Violet</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    
    @include('includes.sidebar')

    <main class="dashboard-main">
        @include('includes.header')

        <div class="dashboard-main-body">
            @yield('content')
        </div>

        @include('includes.footer')
    </main>

    <div class="custom-confirm-modal" id="globalConfirmActionModal" aria-hidden="true">
        <div class="custom-confirm-modal__backdrop" data-confirm-close></div>
        <div class="custom-confirm-modal__dialog" role="dialog" aria-modal="true"
            aria-labelledby="globalConfirmActionModalLabel">
            <div class="custom-confirm-modal__header">
                <h6 class="custom-confirm-modal__title mb-0" id="globalConfirmActionModalLabel">Confirm Action</h6>
                <button type="button" class="custom-confirm-modal__close" data-confirm-close
                    aria-label="Close">x</button>
            </div>
            <div class="custom-confirm-modal__body">
                <p class="mb-0 text-secondary-light" id="globalConfirmActionModalText">Are you sure?</p>
            </div>
            <div class="custom-confirm-modal__footer">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                    id="globalConfirmActionModalCancel">Cancel</button>
                <button type="button" class="btn btn-primary-600 btn-sm"
                    id="globalConfirmActionModalSubmit">Confirm</button>
            </div>
        </div>
    </div>

    <!-- jQuery library js -->
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>


    <!-- Iconify Font js -->
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
    <!-- jQuery UI js -->
    <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>

    <!-- Popup js -->
    <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
    <!-- Slick Slider js -->
    <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
    <!-- prism js -->
    <script src="{{ asset('assets/js/lib/prism.js') }}"></script>

    <!-- quill js -->
    <script src="{{ asset('assets/js/lib/quill.js') }}"></script>

    <!-- sortable js -->
    <script src="{{ asset('assets/js/lib/sortable.js') }}"></script>

    <!-- app js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- main js -->

    <script>
        (function() {
            const modalElement = document.getElementById('globalConfirmActionModal');
            const modalTitle = document.getElementById('globalConfirmActionModalLabel');
            const modalText = document.getElementById('globalConfirmActionModalText');
            const modalSubmit = document.getElementById('globalConfirmActionModalSubmit');
            const modalCancel = document.getElementById('globalConfirmActionModalCancel');
            let pendingConfirmAction = null;

            function showModal() {
                if (!modalElement) {
                    return;
                }

                modalElement.classList.add('is-open');
                modalElement.setAttribute('aria-hidden', 'false');
                document.body.classList.add('custom-confirm-modal-open');
            }

            function hideModal() {
                if (!modalElement) {
                    return;
                }

                modalElement.classList.remove('is-open');
                modalElement.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('custom-confirm-modal-open');
                pendingConfirmAction = null;
            }

            window.openAppConfirm = function(config) {
                if (!modalElement) {
                    return;
                }

                modalTitle.textContent = config.title || 'Confirm Action';
                modalText.textContent = config.message || 'Are you sure?';
                modalSubmit.textContent = config.buttonText || 'Confirm';
                modalSubmit.className = config.buttonClass || 'btn btn-primary-600';
                pendingConfirmAction = config.onConfirm || null;
                showModal();
            };

            modalSubmit?.addEventListener('click', function() {
                if (pendingConfirmAction) {
                    pendingConfirmAction();
                }

                hideModal();
            });

            modalCancel?.addEventListener('click', hideModal);

            modalElement?.querySelectorAll('[data-confirm-close]').forEach(function(element) {
                element.addEventListener('click', hideModal);
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && modalElement?.classList.contains('is-open')) {
                    hideModal();
                }
            });
        })();
    </script>

    

    @yield('script')
</body>

</html>
