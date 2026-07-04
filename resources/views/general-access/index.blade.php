@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="fw-semibold mb-0">
        General Access
    </h6>

    <div class="d-flex align-items-center gap-2">

        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $generalAccesses->total() }} Total Items
        </span>

        @can('create_general_access')
        <a href="{{ route('general-access.create') }}"
            class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add New
        </a>
        @endcan

    </div>
</div>

<div class="card">

    <div class="card-header">

        <form method="GET"
            action="{{ route('general-access.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <label class="form-label mb-1">
                        Title
                    </label>
                    <input type="text"
                        name="title"
                        value="{{ request('title') }}"
                        class="form-control form-control-sm"
                        placeholder="Search title">
                </div>

                <div>
                    <label class="form-label form-label-sm">
                        Branch
                    </label>
                    <select name="branch"
                        class="form-select form-select-sm">
                        <option value="">
                            All Branches
                        </option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                        class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i>
                        Filter
                    </button>

                    <a href="{{ route('general-access.index') }}"
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

                        <th>#</th>

                        <th>Title</th>

                        <th>Weekday Price</th>

                        <th>Weekend Price</th>

                        <th>Branch</th>

                        <th>Status</th>

                        @if(auth()->user()?->can('edit_general_access') || auth()->user()?->can('delete_general_access'))
                        <th class="text-center pe-4">
                            Action
                        </th>
                        @endif

                    </tr>
                </thead>

                <tbody>

                    @forelse($generalAccesses as $item)

                    <tr>

                        <td>
                            {{ $generalAccesses->firstItem() + $loop->index }}
                        </td>

                        <td>
                            {{ $item->title }}
                        </td>

                        <td>
                            {{ $item->weekday_price }}
                        </td>

                        <td>
                            {{ $item->weekend_price }}
                        </td>

                        <td>
                            {{ $item->branch->title }}
                        </td>

                        <td>

                            @if($item->status)

                            <span class="badge bg-success">
                                Active
                            </span>

                            @else

                            <span class="badge bg-danger">
                                Inactive
                            </span>

                            @endif

                        </td>

                        @if(auth()->user()?->can('edit_general_access') || auth()->user()?->can('delete_general_access'))
                        <td class="text-center pe-4">

                            <div class="d-flex justify-content-center align-items-center gap-2">

                                @can('edit_general_access')

                                <a href="{{ route('general-access.edit',$item) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        width="1em"
                                        height="1em"
                                        viewBox="0 0 24 24">

                                        <g fill="none"
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2">

                                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>

                                            <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path>

                                        </g>

                                    </svg>

                                </a>

                                @endcan

                                @can('delete_general_access')

                                <form action="{{ route('general-access.destroy',$item) }}"
                                    method="POST"
                                    class="delete-form">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                        data-confirm-title="Delete General Access"
                                        data-confirm-message="Are you sure you want to delete this item?">

                                        <iconify-icon
                                            icon="fluent:delete-24-regular">
                                        </iconify-icon>

                                    </button>

                                </form>

                                @endcan

                            </div>

                        </td>
                        @endif

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7"
                            class="text-center">
                            No records found
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $generalAccesses->links('pagination::bootstrap-4') }}
        </div>

    </div>

</div>

@endsection

@section('script')

<script>
    document.querySelectorAll('.delete-form button[type="submit"]').forEach(function(button) {

        button.addEventListener('click', function(event) {

            event.preventDefault();

            const form = button.closest('form');

            window.openAppConfirm({
                title: button.dataset.confirmTitle || 'Delete',
                message: button.dataset.confirmMessage || 'Are you sure?',
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