@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Image Gallery</h6>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $galleries->total() }} Total Categories
        </span>
        @can('create_image_gallery')
        <a href="{{ route('image-gallery.create') }}"
            class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
            <i class="ri-add-line"></i>
            Add New Category
        </a>
        @endcan
    </div>
</div>


<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('image-gallery.index') }}" class="mb-20">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Search category..."
                        name="category"
                        value="{{ request('category') }}">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>

                    <a href="{{ route('image-gallery.index') }}"
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
                        <th>Category Name</th>
                        <th width="500">Images</th>
                        <th width="120">Total</th>
                        <th width="120">Status</th>
                        <th width="180" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($galleries as $key => $gallery)
                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $gallery->category_name }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                @foreach(array_slice($gallery->images ?? [], 0, 4) as $image)
                                <a href="{{ asset($image) }}" target="_blank">
                                    <img src="{{ asset($image) }}"
                                        class="rounded-3 border"
                                        style="width:60px;height:60px;object-fit:cover;">
                                </a>
                                @endforeach

                                @if(count($gallery->images ?? []) > 4)
                                <div class="d-flex align-items-center justify-content-center rounded-3 bg-light border fw-bold"
                                    style="width:60px;height:60px;">

                                    +{{ count($gallery->images) - 4 }}
                                </div>
                                @endif
                            </div>
                        </td>

                        <td>
                            <span class="fw-semibold">
                                {{ count($gallery->images ?? []) }}
                            </span>
                        </td>
                        <td>
                            @if($gallery->status == 1)
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                Active
                            </span>
                            @else
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                Inactive
                            </span>
                            @endif
                        </td>
                       <td class="text-end pe-4">

                        <div class="d-flex justify-content-end align-items-center gap-2">

                            {{-- VIEW --}}
                            @can('view_image_gallery')

                                <button type="button"
                                    class="image-gallery-icon-btn bg-info-focus text-info-600 bg-hover-info-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                    data-bs-toggle="modal"
                                    data-bs-target="#galleryModal{{ $gallery->id }}"
                                    title="View">

                                    <iconify-icon icon="mdi:eye-outline"
                                        class="menu-icon">
                                    </iconify-icon>

                                </button>

                            @endcan

                            {{-- EDIT --}}
                            @can('edit_image_gallery')

                                <a href="{{ route('image-gallery.edit', $gallery) }}"
                                    class="image-gallery-icon-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="Edit">

                                    <iconify-icon icon="lucide:edit"
                                        class="menu-icon">
                                    </iconify-icon>

                                </a>

                            @endcan

                            {{-- DELETE --}}
                            @can('delete_image_gallery')

                                <form action="{{ route('image-gallery.destroy', $gallery) }}"
                                    method="POST"
                                    class="delete-form">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="image-gallery-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                        data-confirm-title="Delete Gallery"
                                        data-confirm-message="Are you sure you want to delete this gallery?"
                                        title="Delete">

                                        <iconify-icon icon="fluent:delete-24-regular"
                                            class="menu-icon">
                                        </iconify-icon>

                                    </button>

                                </form>

                            @endcan

                        </div>

                    </td>
                    </tr>

                    {{-- IMAGE GALLERY MODAL --}}
                    <div class="modal fade"
                        id="galleryModal{{ $gallery->id }}"
                        tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header">
                                    <h6 class="modal-title">
                                        {{ $gallery->category_name }}
                                    </h6>
                                    <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    @if(!empty($gallery->images))
                                    <div class="row g-3" data-gallery-id="{{ $gallery->id }}">
                                        @foreach($gallery->images as $image)
                                        <div class="col-auto" data-image="{{ $image }}">
                                            <a href="{{ asset($image) }}"
                                                target="_blank"
                                                class="d-block">
                                                <img src="{{ asset($image) }}"
                                                    class="rounded-3 border"
                                                    style="height:90px;width:90px;object-fit:cover;">
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-5 text-muted">
                                        No images found
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-32">
                            <div class="text-secondary-light">
                                <i class="ri-question-answer-line text-3xl d-block mb-8"></i>
                                No Gallery images Found
                            </div>
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
                    Showing {{ $galleries->firstItem() }} to {{ $galleries->lastItem() }} of {{ $galleries->total() }} entries
                </small>
            </div>
            <div>
                {{ $galleries->links('pagination::bootstrap-4') }}
            </div>
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