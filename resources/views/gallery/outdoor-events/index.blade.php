@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-24">

    <h6 class="fw-semibold mb-0">
        Outdoor Events
    </h6>

    @can('create_outdoor_events')
        @if($events->count() == 0)
            <a href="{{ route('outdoor-events.create') }}"
            class="btn btn-primary-600 btn-sm">
                <i class="ri-add-line"></i>
                Add Event
            </a>
        @endif
    @endcan

</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="row g-4">
            @forelse($events as $event)
                <div class="col-12">
                    <div class="card border shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Gallery</h6>

                                    <small class="text-muted">
                                        {{ count($event->images ?? []) }}
                                        Images
                                    </small>
                                </div>

                                <div class="d-flex gap-2">
                                    @can('edit_outdoor_events')
                                    <a href="{{ route('outdoor-events.edit', $event) }}"
                                       class="btn btn-sm btn-success">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    @endcan
                                    @can('delete_outdoor_events')
                                    <form action="{{ route('outdoor-events.destroy', $event) }}"
                                        method="POST"
                                        class="outdoor-events-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="outdoor-events-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                            data-confirm-title="Delete Gallery"
                                            data-confirm-message="Are you sure you want to delete this gallery?"
                                            title="Delete">

                                            <iconify-icon icon="fluent:delete-24-regular"
                                                class="menu-icon">
                                            </iconify-icon>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- SCROLLABLE IMAGE GRID --}}
                            <div class="outdoor-scroll-wrapper">
                                <div class="row g-3"
                                     data-event-id="{{ $event->id }}"
                                     id="gallery-{{ $event->id }}">
                                    @foreach($event->images ?? [] as $image)
                                        <div class="col-auto"
                                             data-image="{{ $image }}">
                                            <div class="position-relative">
                                                <img src="{{ asset($image) }}"
                                                     class="rounded-3 border outdoor-image">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="ri-image-line text-3xl mb-3 d-block"></i>
                        No Outdoor Events Found
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.outdoor-events-delete-form button[type="submit"]').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const form = button.closest('form');

            window.openAppConfirm({
                title: button.dataset.confirmTitle || 'Delete',
                message: button.dataset.confirmMessage || 'Are you sure you want to continue?',
                buttonText: 'Yes, Delete',
                buttonClass: 'btn btn-sm btn-danger',
                onConfirm: function() {
                    form.submit();
                }
            });
        });
    });

});
</script>
@endsection