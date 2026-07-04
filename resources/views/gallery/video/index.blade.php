@extends('layouts.app')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">
        Video Gallery
    </h6>
    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $galleries->total() }} Total Categories
        </span>
        @can('create_video_gallery')
        <a href="{{ route('video-gallery.create') }}"
           class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
            <i class="ri-add-line"></i>
            Add New Category
        </a>
        @endcan
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body border-bottom">
        {{-- FILTER --}}
        <form method="GET"
              action="{{ route('video-gallery.index') }}"
              class="mb-20">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <input type="text"
                           class="form-control form-control-sm"
                           placeholder="Search category..."
                           name="category"
                           value="{{ request('category') }}">
                </div>

                {{-- STATUS --}}
                <div>
                    <select name="status"
                        class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i>
                        Filter
                    </button>

                    <a href="{{ route('video-gallery.index') }}"
                       class="btn btn-sm btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Category Name</th>
                        <th width="500">Videos</th>
                        <th width="120">Total</th>
                        <th width="120">Status</th>
                        <th width="180" class="text-end pe-4">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($galleries as $key => $gallery)
                        @php
                            $videos = is_array($gallery->youtube_link)
                                ? $gallery->youtube_link
                                : json_decode($gallery->youtube_link, true);

                            $videos = $videos ?? [];
                        @endphp

                        <tr>
                            <td>
                                {{ $galleries->firstItem() + $key }}
                            </td>

                            <td>
                                <span class="fw-semibold d-block">
                                    {{ $gallery->category_name }}
                                </span>
                                <span class="text-xs text-secondary-light font-monospace">
                                    {{ $gallery->slug }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex flex-column gap-2" id="video-preview-{{ $gallery->id }}">
                                    @foreach(array_slice($videos, 0, 3) as $video)
                                        <a href="{{ $video }}"
                                           target="_blank"
                                           class="d-flex align-items-center gap-2 text-decoration-none">
                                            <div class="bg-danger-subtle text-danger rounded-circle d-flex justify-content-center align-items-center"
                                                 style="width:32px;height:32px;">
                                                <i class="ri-youtube-fill"></i>
                                            </div>

                                            <span class="text-truncate d-inline-block"
                                                  style="max-width:350px;">
                                                {{ $video }}
                                            </span>
                                        </a>

                                    @endforeach

                                    @if(count($videos) > 3)
                                        <small class="text-muted">
                                            +{{ count($videos) - 3 }}
                                            more videos
                                        </small>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <span class="fw-semibold">
                                    {{ count($videos) }}
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
                                    @can('view_video_gallery')
                                    <button type="button"
                                            class="image-gallery-icon-btn bg-info-focus text-info-600 bg-hover-info-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                            data-bs-toggle="modal"
                                            data-bs-target="#videoModal{{ $gallery->id }}"
                                            title="View">

                                        <iconify-icon icon="mdi:eye-outline"
                                                      class="menu-icon">
                                        </iconify-icon>
                                    </button>
                                    @endcan
                                    {{-- EDIT --}}
                                    @can('edit_video_gallery')
                                    <a href="{{ route('video-gallery.edit', $gallery) }}"
                                       class="image-gallery-icon-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                       title="Edit">
                                        <iconify-icon icon="lucide:edit"
                                                      class="menu-icon">
                                        </iconify-icon>
                                    </a>
                                    @endcan
                                    {{-- DELETE --}}
                                    @can('delete_video_gallery')
                                    <form action="{{ route('video-gallery.destroy', $gallery) }}"
                                          method="POST"
                                          class="video-gallery-delete-form">
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

                        {{-- MODAL --}}
                        <div class="modal fade"
                             id="videoModal{{ $gallery->id }}"
                             tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            {{ $gallery->category_name }}
                                        </h5>
                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex flex-column gap-3" data-gallery-id="{{ $gallery->id }}">
                                            @foreach($videos as $video)
                                            <div data-video="{{ $video }}">
                                                <a href="{{ $video }}"
                                                   target="_blank"
                                                   class="border rounded-3 p-3 d-flex align-items-center gap-3 text-decoration-none">

                                                    <div class="bg-danger-subtle text-danger rounded-circle d-flex justify-content-center align-items-center"
                                                         style="width:45px;height:45px;">

                                                        <i class="ri-youtube-fill fs-5"></i>

                                                    </div>

                                                    <div class="flex-grow-1">
                                                        <div class="fw-medium text-dark">
                                                            YouTube Video
                                                        </div>
                                                        <small class="text-muted text-break">
                                                            {{ $video }}
                                                        </small>
                                                    </div>
                                                </a>
                                            </div>

                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="text-center py-32">
                                <div class="text-secondary-light">
                                    <i class="ri-video-line text-3xl d-block mb-8"></i>
                                    No Video Galleries Found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- PAGINATION --}}
    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <small class="text-muted">
                    Showing {{ $galleries->firstItem() }}
                    to {{ $galleries->lastItem() }}
                    of {{ $galleries->total() }} entries
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
    document.querySelectorAll('.video-gallery-delete-form button[type="submit"]').forEach(function(button) {
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