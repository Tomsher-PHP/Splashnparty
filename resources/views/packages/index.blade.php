@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-24">
    <h6 class="mb-0">
        Packages
    </h6>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $packages->total() }} Total Entries
        </span>
        @can('create_packages')
        <a href="{{ route('packages.create') }}"
            class="btn btn-primary-600 btn-sm">
            <i class="ri-add-line"></i>
            Add Package
        </a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('packages.index') }}">
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
                    <a href="{{ route('packages.index') }}"
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
                        <th>Branch</th>
                        <th>Food Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($packages as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->branch->title ?? '' }}</td>
                        <td>{{ $item->food_type }}</td>
                        <td>
                            {{ $item->status ? 'Active' : 'Inactive' }}
                        </td>
                        <td>
                            <a href="{{ route('packages.edit', $item) }}">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $packages->links() }}

    </div>
    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <small class="text-muted">
                    Showing {{ $packages->firstItem() }} to {{ $packages->lastItem() }} of {{ $packages->total() }} entries
                </small>
            </div>
            <div>
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</div>
{{ $packages->links() }}

@endsection