@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="fw-semibold mb-0">Branches</h6>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $branches->total() }} Total Branches
        </span>
        @can('create_branches')
        <a href="{{ route('branches.create') }}"
        class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add Branch
        </a>
        @endcan
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('branches.index') }}" class="mb-20">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Search Branch..."
                        name="title"
                        value="{{ request('title') }}">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>

                    <a href="{{ route('branches.index') }}"
                        class="btn btn-sm btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $key => $branch)
                        <tr>
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td width="90">
                                @if($branch->image)
                                    <img src="{{ asset($branch->image) }}"
                                         class="rounded"
                                         width="60">
                                @endif
                            </td>
                            <td>
                                {{ $branch->title }}
                            </td>
                            <td>
                                {{ $branch->sort_order }}
                            </td>
                            <td>
                                @if($branch->status)
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    @can('edit_branches')
                                    <a href="{{ route('branches.edit', $branch) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path></g></svg>
                                    </a>
                                    @endcan
                                    @can('delete_branches')
                                    <form action="{{ route('branches.destroy', $branch) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="text-center py-4">
                                No branches found
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
                    Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} of {{ $branches->total() }} entries
                </small>
            </div>
            <div>
                {{ $branches->links() }}
            </div>
        </div>
    </div>
</div>
@endsection