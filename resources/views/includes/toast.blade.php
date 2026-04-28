@php
    $toastMessages = [];

    foreach (['success', 'error', 'warning', 'info'] as $type) {
        $message = session($type);

        if (filled($message)) {
            $toastMessages[] = [
                'type' => $type,
                'message' => $message,
            ];
        }
    }

    if ($errors->any()) {
        foreach ($errors->all() as $message) {
            $toastMessages[] = [
                'type' => 'error',
                'message' => $message,
            ];
        }
    }
@endphp

<style>
    .app-toast-stack {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 2000;
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: min(100% - 32px, 360px);
        pointer-events: none;
    }

    .app-toast {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid transparent;
        background: #ffffff;
        box-shadow: 0 14px 40px rgba(15, 23, 42, .16);
        pointer-events: auto;
        transform: translate3d(0, -8px, 0);
        opacity: 0;
        transition: opacity .22s ease, transform .22s ease;
    }

    .app-toast.is-visible {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }

    .app-toast--success {
        border-color: rgba(34, 197, 94, .2);
        background: #f0fdf4;
    }

    .app-toast--error {
        border-color: rgba(239, 68, 68, .2);
        background: #fef2f2;
    }

    .app-toast--warning {
        border-color: rgba(245, 158, 11, .2);
        background: #fffbeb;
    }

    .app-toast--info {
        border-color: rgba(59, 130, 246, .2);
        background: #eff6ff;
    }

    .app-toast__icon {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        flex-shrink: 0;
        font-size: 18px;
    }

    .app-toast--success .app-toast__icon {
        color: #15803d;
        background: rgba(34, 197, 94, .14);
    }

    .app-toast--error .app-toast__icon {
        color: #b91c1c;
        background: rgba(239, 68, 68, .12);
    }

    .app-toast--warning .app-toast__icon {
        color: #b45309;
        background: rgba(245, 158, 11, .14);
    }

    .app-toast--info .app-toast__icon {
        color: #1d4ed8;
        background: rgba(59, 130, 246, .12);
    }

    .app-toast__content {
        flex: 1;
        min-width: 0;
    }

    .app-toast__title {
        font-size: 14px;
        line-height: 1.2;
        font-weight: 700;
        margin-bottom: 4px;
        color: #111827;
    }

    .app-toast__message {
        margin: 0;
        font-size: 13px;
        line-height: 1.5;
        color: #475467;
        word-break: break-word;
    }

    .app-toast__close {
        border: 0;
        background: transparent;
        color: #64748b;
        line-height: 1;
        font-size: 18px;
        padding: 2px;
        flex-shrink: 0;
    }

    @media (max-width: 767px) {
        .app-toast-stack {
            top: 16px;
            right: 16px;
            left: 16px;
            width: auto;
        }
    }
</style>

<div class="app-toast-stack" id="appToastStack" aria-live="polite" aria-atomic="true"></div>

<script>
    (function () {
        const initialMessages = @json($toastMessages);
        const stack = document.getElementById('appToastStack');
        const toastMeta = {
            success: { title: 'Success', icon: 'ri-checkbox-circle-line' },
            error: { title: 'Error', icon: 'ri-error-warning-line' },
            warning: { title: 'Warning', icon: 'ri-alert-line' },
            info: { title: 'Info', icon: 'ri-information-line' }
        };

        if (!stack) {
            return;
        }

        function removeToast(toast) {
            toast.classList.remove('is-visible');

            window.setTimeout(function () {
                toast.remove();
            }, 220);
        }

        window.appToast = function (type, message, options) {
            if (!message) {
                return;
            }

            const settings = options || {};
            const variant = toastMeta[type] ? type : 'info';
            const meta = toastMeta[variant];
            const toast = document.createElement('div');
            toast.className = 'app-toast app-toast--' + variant;
            toast.setAttribute('role', variant === 'error' ? 'alert' : 'status');

            toast.innerHTML = `
                <span class="app-toast__icon"><i class="${meta.icon}"></i></span>
                <div class="app-toast__content">
                    <div class="app-toast__title">${settings.title || meta.title}</div>
                    <p class="app-toast__message"></p>
                </div>
                <button type="button" class="app-toast__close" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            `;

            toast.querySelector('.app-toast__message').textContent = message;
            toast.querySelector('.app-toast__close').addEventListener('click', function () {
                removeToast(toast);
            });

            stack.appendChild(toast);

            window.requestAnimationFrame(function () {
                toast.classList.add('is-visible');
            });

            if (settings.autoclose !== false) {
                window.setTimeout(function () {
                    removeToast(toast);
                }, settings.duration || 4000);
            }
        };

        initialMessages.forEach(function (item, index) {
            window.setTimeout(function () {
                window.appToast(item.type, item.message);
            }, index * 180);
        });
    })();
</script>
