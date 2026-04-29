<footer class="d-footer">
    <div class="row align-items-center justify-content-between text-xs">
        <div class="col-auto">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $generalSettings?->site_name ?: config('app.name') }}.
                {{ $generalSettings?->footer_text ?: 'All Rights Reserved.' }}</p>
        </div>
        <div class="col-auto">
            <p class="mb-0">Made by <span class="text-primary-600">Tomsher</span></p>
        </div>
    </div>
</footer>
