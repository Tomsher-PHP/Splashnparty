@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="fw-semibold mb-0">Rental Categories</h6>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $categories->total() }} Total Categories
        </span>
        @can('create_rental_categories')
        <a href="{{ route('rental-categories.create') }}"
            class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add Category
        </a>
        @endcan
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <form method="GET" action="{{ route('rental-categories.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Search Category..."
                        name="title"
                        value="{{ request('title') }}">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>

                    <a href="{{ route('rental-categories.index') }}"
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
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        @if (auth()->user()?->can('edit_rental_categories') || auth()->user()?->can('delete_rental_categories'))
                        <th class="text-end pe-4">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>

                    @foreach($categories as $key => $item)

                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->slug }}</td>
                        <td>{{ $item->sort_order }}</td>

                        <td>
                            {{ $item->status ? 'Active' : 'Inactive' }}
                        </td>
                        @if (auth()->user()?->can('edit_rental_categories') || auth()->user()?->can('delete_rental_categories'))
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                @can('edit_rental_categories')
                                <a href="{{ route('rental-categories.edit', $item) }}"
                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path></g></svg>
                                </a>
                                @endcan
                                @can('delete_rental_categories')
                                <form action="{{ route('rental-categories.destroy', $item) }}" method="POST"
                                    class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rental-category-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                        data-confirm-title="Delete Category"
                                        data-confirm-message="Are you sure you want to delete this Category?"
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

                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <small class="text-muted">
                    Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries
                </small>
            </div>
            <div>
                {{ $categories->links('pagination::bootstrap-4') }}
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