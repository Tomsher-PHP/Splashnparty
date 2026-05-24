@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="mb-0">
        Cakes
    </h6>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $cakes->total() }} Total Entries
        </span>
        @can('create_cakes')
        <a href="{{ route('cakes.create') }}"
            class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add Cake
        </a>
        @endcan
    </div>
</div>
<div class="card">

    <div class="card-header">
        <form method="GET" action="{{ route('cakes.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="text"
                        name="title"
                        class="form-control form-control-sm"
                        placeholder="Search title"
                        value="{{ request('title') }}">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>
                    <a href="{{ route('cakes.index') }}"
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
                        <th>Image</th>
                        <th>Title</th>
                        <th>Product Code</th>
                        <th>Price</th>
                        <th>Sort</th>
                        <th>Status</th>
                        @if (auth()->user()?->can('edit_cakes') || auth()->user()?->can('delete_cakes'))
                        <th class="text-end">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($cakes as $cake)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            @if($cake->thumbnail_image)
                            <img src="{{ asset($cake->thumbnail_image) }}"
                                width="60"
                                class="rounded border">
                            @endif
                        </td>
                        <td>
                            {{ $cake->title }}
                        </td>
                        <td>
                            {{ $cake->product_code }}
                        </td>

                        <td>
                            {{ $cake->price }}
                        </td>

                        <td>
                            {{ $cake->sort_order }}
                        </td>

                        <td>
                            @if($cake->status)
                            <span class="badge bg-success">
                                Active
                            </span>
                            @else
                            <span class="badge bg-danger">
                                Inactive
                            </span>
                            @endif
                        </td>

                        @if (auth()->user()?->can('edit_cakes') || auth()->user()?->can('delete_cakes'))
                        <td>
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                @can('edit_cakes')
                                <a href="{{ route('cakes.edit', $cake) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path>
                                        </g>
                                    </svg>
                                </a>
                                @endcan

                                @can('delete_cakes')
                                <form action="{{ route('cakes.destroy', $cake) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                        onclick="return confirm('Delete this cake?')">
                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
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
                            No data found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{ $cakes->links() }}

    </div>
    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <small class="text-muted">
                    Showing {{ $cakes->firstItem() }} to {{ $cakes->lastItem() }} of {{ $cakes->total() }} entries
                </small>
            </div>
            <div>
                {{ $cakes->links() }}
            </div>
        </div>
    </div>
</div>

@endsection