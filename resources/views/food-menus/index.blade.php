@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="fw-semibold mb-0">Food Menus</h6>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $foodMenus->total() }} Total Menus
        </span>
        @can('create_food_menus')
        <a href="{{ route('food-menus.create') }}"
        class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add Food Menu
        </a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('food-menus.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Search Menu..."
                        name="title"
                        value="{{ request('title') }}">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>

                    <a href="{{ route('food-menus.index') }}"
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
                        <th width="80">
                            #
                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            Image
                        </th>
                        <th>
                            Branch
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Food Type
                        </th>
                        <th>
                            Price
                        </th>
                        <th>
                            Status
                        </th>
                        @if (auth()->user()?->can('edit_food_menus') || auth()->user()?->can('delete_food_menus'))
                        <th class="text-end pe-4">
                            Action
                        </th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($foodMenus as $key => $foodMenu)
                    <tr>
                        <td>
                            {{ $foodMenus->firstItem() + $key }}
                        </td>
                        <td>
                            {{ $foodMenu->title }}
                        </td>
                        <td>
                            @if($foodMenu->image)
                            <img src="{{ asset($foodMenu->image) }}"
                                width="80"
                                class="rounded border">
                            @endif
                        </td>
                        <td>
                            {{ $foodMenu->branch?->title }}
                        </td>
                        <td>
                            {{ ucfirst($foodMenu->type) }}
                        </td>
                        <td>
                            {{ ucfirst($foodMenu->food_type) }}
                        </td>
                        <td>
                            {{ $foodMenu->price ?: '-' }}
                        </td>
                        <td>
                            @if($foodMenu->status)
                            <span class="badge bg-success">
                                Active
                            </span>
                            @else
                            <span class="badge bg-danger">
                                Inactive
                            </span>
                            @endif
                        </td>
                        @if (auth()->user()?->can('edit_food_menus') || auth()->user()?->can('delete_food_menus'))
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                @can('edit_food_menus')
                                <a href="{{ route('food-menus.edit', $foodMenu->id) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path></g></svg>
                                </a>
                                @endcan
                                @can('delete_food_menus')
                                <form action="{{ route('food-menus.destroy', $foodMenu->id) }}" method="POST"
                                    class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="food-menu-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                            data-confirm-title="Delete Menu"
                                            data-confirm-message="Are you sure you want to delete this Item?"
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
                        <td colspan="8"
                            class="text-center">
                            No records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $foodMenus->links() }}
        </div>
    </div>

    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <small class="text-muted">
                    Showing {{ $foodMenus->firstItem() }} to {{ $foodMenus->lastItem() }} of {{ $foodMenus->total() }} entries
                </small>
            </div>
            <div>
                {{ $foodMenus->links() }}
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