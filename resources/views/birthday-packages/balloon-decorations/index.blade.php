@extends('layouts.app')

@section('content') 



<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="fw-semibold mb-0">Balloon Decorations</h6>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $decorations->total() }} Total Items
        </span>
        @can('create_balloon_decorations')
        <a href="{{ route('balloon-decorations.create') }}"
            class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add Item
        </a>
        @endcan
    </div>
</div>


<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('balloon-decorations.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-3">
                {{-- KEYWORD SEARCH --}}
                <div>
                    <label class="form-label form-label-sm">
                        Keyword
                    </label>
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Search title..."
                        name="keyword"
                        value="{{ request('keyword') }}">
                </div>
                
                {{-- BUTTONS --}}
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i>
                        Filter
                    </button>
                    <a href="{{ route('balloon-decorations.index') }}"
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
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        @if (auth()->user()?->can('edit_balloon_decorations') || auth()->user()?->can('delete_balloon_decorations'))
                        <th class="text-end">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($decorations as $key => $item)
                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td width="80">
                            @if($item->image)
                            <img src="{{ asset($item->image) }}"
                                class="rounded"
                                width="60">
                            @endif
                        </td>

                        <td>{{ $item->title }}</td>


                        <td>{{ $item->price }}</td>

                        <td>{{ $item->sort_order }}</td>

                        <td>
                            {{ $item->status ? 'Active' : 'Inactive' }}
                        </td>

                        @if (auth()->user()?->can('edit_balloon_decorations') || auth()->user()?->can('delete_balloon_decorations'))
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                @can('edit_balloon_decorations')
                                <a href="{{ route('balloon-decorations.edit', $item->id) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path></g></svg>
                                </a>
                                @endcan
                                @can('delete_balloon_decorations')
                                <form action="{{ route('balloon-decorations.destroy', $item->id) }}"
                                    method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="item-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                        data-confirm-title="Delete Item"
                                        data-confirm-message="Are you sure you want to delete this Item?"
                                        title="Delete">
                                        <iconify-icon icon="fluent:delete-24-regular"
                                            class="item-icon"></iconify-icon>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                        @endif
                    </tr>

                    @empty

                    <tr>
                        <td colspan="8"
                            class="text-center">
                            No records found
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
                    Showing {{ $decorations->firstItem() }} to {{ $decorations->lastItem() }} of {{ $decorations->total() }} entries
                </small>
            </div>
            <div>
                {{ $decorations->links('pagination::bootstrap-4') }}
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