@extends(auth()->check() ? 'layouts.app' : 'layouts.default')

@section('content')
<div class="{{ auth()->check() ? '' : 'd-flex align-items-center justify-content-center min-vh-100 bg-neutral-50' }}">
    <div class="d-flex flex-column align-items-center justify-content-center py-48 text-center p-24">
        <div class="mb-24">
            <iconify-icon icon="solar:danger-broken" class="text-danger" style="font-size: 120px;"></iconify-icon>
        </div>
        <h1 class="display-3 fw-bold text-neutral-800 mb-12">404</h1>
        <h5 class="fw-semibold text-neutral-600 mb-16">Page Not Found</h5>
        <p class="text-secondary-light max-w-480-px mb-24 mx-auto">
            We couldn't find the page you're looking for. It might have been moved, deleted, or the URL might be incorrect.
        </p>
        <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="btn btn-primary-600">
            <i class="ri-home-4-line me-8"></i> Go back to {{ auth()->check() ? 'Dashboard' : 'Home' }}
        </a>
    </div>
</div>
@endsection
