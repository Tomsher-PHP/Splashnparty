@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="fw-semibold mb-0">Branches</h6>
    @can('create_branches')
    <a href="{{ route('branches.create') }}"
       class="btn btn-primary-600 btn-sm">
        <i class="ri-add-line"></i>
        Add Branch
    </a>
    @endcan
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr>
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

                            <td class="text-end">
                                @can('edit_branches')
                                <a href="{{ route('branches.edit', $branch) }}"
                                   class="btn btn-sm btn-success">
                                    <i class="ri-edit-line"></i>
                                </a>
                                @endcan
                                @can('delete_branches')
                                <form action="{{ route('branches.destroy', $branch) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                                @endcan
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
        {{ $branches->links() }}
    </div>
</div>
@endsection