@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="fw-semibold mb-0">Events</h6>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $events->total() }} Total Events
        </span>
        @can('create_events')
        <a href="{{ route('events.create') }}"
        class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add Event
        </a>
        @endcan
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <form method="GET" action="{{ route('events.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Search Event..."
                        name="title"
                        value="{{ request('title') }}">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>

                    <a href="{{ route('events.index') }}"
                        class="btn btn-sm btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead class="bg-light">

                <tr>
                    <th width="60">#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    @if (auth()->user()?->can('edit_events') || auth()->user()?->can('delete_events'))
                    <th class="text-end">Action</th>
                    @endif

                </tr>

            </thead>

            <tbody>

                @forelse($events as $key => $event)

                    <tr>

                    <td>
                                {{ $key + 1 }}
                            </td>
                        <td>

                            @if($event->image)

                                <img src="{{ asset($event->image) }}"
                                    width="70">

                            @endif

                        </td>

                        <td>
                            {{ $event->title }}
                        </td>

                        <td>
                            {{ $event->slug }}
                        </td>

                        <td>

                            @if($event->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        @if (auth()->user()?->can('edit_events') || auth()->user()?->can('delete_events'))
                        <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    @can('edit_events')
                                    <a href="{{ route('events.edit', $event) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path></g></svg>
                                    </a>
                                    @endcan
                                    @can('delete_events')
                                    <form action="{{ route('events.destroy', $event) }}" method="POST"
                                    class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="event-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                            data-confirm-title="Delete Event"
                                            data-confirm-message="Are you sure you want to delete this Event?"
                                            title="Delete">
                                            <iconify-icon icon="fluent:delete-24-regular"
                                                class="menu-icon"></iconify-icon>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                            @endif

                    </tr>

                @empty
                        <tr>
                            <td colspan="5"
                                class="text-center py-4">
                                No events found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <small class="text-muted">
                    Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} entries
                </small>
            </div>
            <div>
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
<script>

    document.querySelectorAll('.delete-form button[type="submit"]').forEach(function(button) {
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

</script>
@endsection