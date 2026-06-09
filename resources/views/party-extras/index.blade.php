@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="fw-semibold mb-0">Party Extras</h6>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $partyExtras->total() }} Total Items
        </span>
        @can('create_party_extras')
        <a href="{{ route('party-extras.create') }}"
            class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add New
        </a>
        @endcan
    </div>
</div>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <form method="GET" action="{{ route('party-extras.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-3">

                {{-- Category --}}
                <div>
                    <label class="form-label mb-1">Category</label>
                    <input type="text"
                        name="category"
                        value="{{ request('category') }}"
                        class="form-control form-control-sm"
                        placeholder="Category">
                </div>

                {{-- Title --}}
                <div>
                    <label class="form-label mb-1">Title</label>
                    <input type="text"
                        name="title"
                        value="{{ request('title') }}"
                        class="form-control form-control-sm"
                        placeholder="Search title">
                </div>

                {{-- Type --}}
                <div>
                    <label class="form-label mb-1">Type</label>
                    <select name="type"
                        class="form-select form-select-sm">
                        <option value="">All Types</option>

                        <option value="image_gallery"
                            {{ request('type') == 'image_gallery' ? 'selected' : '' }}>
                            Image Gallery
                        </option>

                        <option value="videolink"
                            {{ request('type') == 'videolink' ? 'selected' : '' }}>
                            Video Link
                        </option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i>
                        Filter
                    </button>

                    <a href="{{ route('party-extras.index') }}"
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
                        <th>Thumbnail</th>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        @if (auth()->user()?->can('edit_party_extras') || auth()->user()?->can('delete_party_extras'))
                        <th class="text-end pe-4">Action</th>
                        @endcan
                    </tr>
                </thead>

                <tbody>
                    @forelse($partyExtras as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($item->thumbnail_image)

                            <img src="{{ asset($item->thumbnail_image) }}"
                                width="80">
                            @endif

                        </td>

                        <td>{{ $item->category }}</td>

                        <td>{{ $item->title }}</td>

                        <td>{{ ucfirst($item->type) }}</td>

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

                        @if (auth()->user()?->can('edit_party_extras') || auth()->user()?->can('delete_party_extras'))
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                @can('edit_party_extras')
                                <a href="{{ route('party-extras.edit',$item) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path></g></svg>
                                </a>
                                @endcan
                                @can('delete_party_extras')
                                <form action="{{ route('party-extras.destroy',$item) }}" method="POST"
                                    class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="party-extras-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
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
                        <td colspan="7"
                            class="text-center">
                            No records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $partyExtras->links('pagination::bootstrap-4') }}
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