@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Banners</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">{{ $banners->total() }} Total
                Banners</span>
            @can('create_banners')
                <a href="{{ route('banners.create') }}"
                    class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                    <i class="ri-add-line"></i>
                    Add Banner
                </a>
            @endcan
        </div>
    </div>

    <div class="card basic-data-table">
        <div class="card-body">
            <form method="GET" action="{{ route('banners.index') }}" class="banners-toolbar-wrap mb-20">
                <div class="d-flex flex-wrap align-items-end gap-3">
                    <div class="d-flex flex-wrap align-items-end gap-3">
                        <div class="banners-filter-group">
                            <label for="bannersSearch" class="banners-filter-label">Keyword</label>
                            <input type="text" id="bannersSearch" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm banners-filter-input banners-toolbar-search"
                                placeholder="Search banners">
                        </div>

                        <div class="banners-filter-group">
                            <label for="bannersTypeFilter" class="banners-filter-label">Type</label>
                            <select id="bannersTypeFilter" name="banner_type"
                                class="form-control form-control-sm banners-filter-select banners-toolbar-type">
                                <option value="">All</option>
                                <option value="image" {{ request('banner_type') === 'image' ? 'selected' : '' }}>Image</option>
                                <option value="video" {{ request('banner_type') === 'video' ? 'selected' : '' }}>Video</option>
                            </select>
                        </div>

                        <div class="banners-filter-group">
                            <label for="bannersStatusFilter" class="banners-filter-label">Status</label>
                            <select id="bannersStatusFilter" name="status"
                                class="form-control form-control-sm banners-filter-select banners-toolbar-status">
                                <option value="">All</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('banners.index') }}"
                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <i class="ri-refresh-line"></i>
                            Reset
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                            <i class="ri-search-line"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="table bordered-table mb-0 mx-0" id="dataTable" data-page-length="10">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Sl No</th>
                            <th scope="col">Banner</th>
                            <th scope="col" class="text-center">Type</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Created Date</th>
                            @if (auth()->user()?->can('edit_banners') || auth()->user()?->can('delete_banners'))
                                <th scope="col" class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="bannersTableBody">
                        @forelse ($banners as $banner)
                            <tr>
                                <td class="text-center">{{ $banners->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-12">
                                        <div class="banner-thumb">
                                            @if ($banner->banner_type === 'video')
                                                <video src="{{ asset('storage/' . $banner->file) }}" muted></video>
                                            @else
                                                <img src="{{ asset('storage/' . $banner->file) }}"
                                                    alt="{{ $banner->title ?: 'Banner' }}">
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-dark">{{ $banner->title ?: 'Untitled banner' }}</div>
                                            <div class="text-secondary-light small banner-subtitle">
                                                {{ $banner->subtitle ?: '-' }}
                                            </div>
                                            @if ($banner->btn_text || $banner->btn_link)
                                                <div class="text-secondary-light small">
                                                    {{ $banner->btn_text ?: 'Button' }}{{ $banner->btn_link ? ' - ' . $banner->btn_link : '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="bg-primary-50 text-primary-600 px-12 py-6 rounded fw-medium text-sm">
                                        {{ ucfirst($banner->banner_type) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @can('edit_banners')
                                        <form action="{{ route('banners.update-status', $banner) }}" method="POST"
                                            class="banner-status-form d-inline-flex align-items-center gap-12">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $banner->status ? 0 : 1 }}">
                                            @if (request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            @if (request('banner_type'))
                                                <input type="hidden" name="banner_type" value="{{ request('banner_type') }}">
                                            @endif
                                            @if (request('status'))
                                                <input type="hidden" name="status_filter" value="{{ request('status') }}">
                                            @endif
                                            @if (request('page'))
                                                <input type="hidden" name="page" value="{{ request('page') }}">
                                            @endif

                                            <label class="banner-status-switch mb-0">
                                                <input type="checkbox" class="banner-status-switch__input"
                                                    {{ $banner->status ? 'checked' : '' }}>
                                                <span class="banner-status-switch__slider"></span>
                                            </label>
                                        </form>
                                    @else
                                        <span
                                            class="badge {{ $banner->status ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }}">
                                            {{ $banner->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="text-center">{{ optional($banner->created_at)->format('d M Y') }}</td>
                                @if (auth()->user()?->can('edit_banners') || auth()->user()?->can('delete_banners'))
                                    <td class="text-center">
                                        <div class="banners-action-buttons">
                                            @can('edit_banners')
                                                <a href="{{ route('banners.edit', $banner) }}"
                                                    class="banners-icon-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                    title="Edit">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('delete_banners')
                                                <form action="{{ route('banners.destroy', $banner) }}" method="POST"
                                                    class="banner-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="banners-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-confirm-title="Delete Banner"
                                                        data-confirm-message="Are you sure you want to delete this banner?"
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
                            <tr id="bannersEmptyState">
                                <td colspan="{{ auth()->user()?->can('edit_banners') || auth()->user()?->can('delete_banners') ? 6 : 5 }}"
                                    class="text-center py-32">
                                    <div class="text-secondary-light">
                                        <i class="ri-image-line text-3xl d-block mb-8"></i>
                                        No banners created yet.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($banners->hasPages())
                <div class="banners-pagination-wrap mt-24">
                    <div class="banners-pagination-summary text-secondary-light">
                        Showing {{ $banners->firstItem() }} to {{ $banners->lastItem() }} of {{ $banners->total() }} banners
                    </div>
                    <div class="banners-pagination">
                        {{ $banners->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.banner-status-switch__input').forEach(function(toggle) {
            toggle.addEventListener('change', function(event) {
                const form = event.target.closest('.banner-status-form');
                const nextState = event.target.checked ? 'activate' : 'inactivate';

                window.openAppConfirm({
                    title: 'Update Banner Status',
                    message: `Are you sure you want to ${nextState} this banner?`,
                    buttonText: 'Yes, Update',
                    buttonClass: 'btn btn-sm btn-primary-600',
                    onConfirm: function() {
                        form.submit();
                    }
                });

                event.target.checked = !event.target.checked;
            });
        });

        document.querySelectorAll('.banner-delete-form button[type="submit"]').forEach(function(button) {
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

@section('style')
    <style>
        .banners-filter-group {
            min-width: 180px;
        }

        .banners-filter-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary-light);
            margin-bottom: 6px;
            display: block;
        }

        .banners-filter-input,
        .banners-filter-select {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--input-form-light);
            background: #fff;
        }

        .banners-toolbar-search {
            min-width: 220px;
        }

        .banners-toolbar-type,
        .banners-toolbar-status {
            min-width: 150px;
        }

        .banner-thumb {
            width: 72px;
            height: 42px;
            border-radius: 8px;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            flex-shrink: 0;
        }

        .banner-thumb img,
        .banner-thumb video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .banner-subtitle {
            max-width: 420px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .banners-action-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .banners-icon-btn {
            border: 0;
        }

        .banner-status-switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .banner-status-switch__input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .banner-status-switch__slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #dbe3ef;
            transition: .2s ease;
            border-radius: 999px;
        }

        .banner-status-switch__slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            top: 3px;
            background-color: white;
            transition: .2s ease;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .25);
        }

        .banner-status-switch__input:checked+.banner-status-switch__slider {
            background-color: #22c55e;
        }

        .banner-status-switch__input:checked+.banner-status-switch__slider:before {
            transform: translateX(14px);
        }

        .banners-pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid var(--input-form-light);
        }

        .banners-pagination-summary {
            font-size: 14px;
            font-weight: 500;
        }

        .banners-pagination {
            margin-left: auto;
        }

        .banners-pagination .pagination {
            margin-bottom: 0;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .banners-pagination .page-link {
            border-radius: 8px;
            border: 1px solid var(--input-form-light);
            color: var(--text-primary-light);
            padding: 6px 10px;
            min-width: 34px;
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
            line-height: 1;
            background: #fff;
            box-shadow: none;
        }

        .banners-pagination .page-item.active .page-link {
            background: var(--primary-600);
            border-color: var(--primary-600);
            color: #fff;
        }

        .banners-pagination .page-item.disabled .page-link {
            color: var(--text-secondary-light);
            background: #f8fafc;
            border-color: var(--input-form-light);
        }

        .banners-pagination .page-link:hover {
            background: var(--primary-50);
            border-color: var(--primary-200);
            color: var(--primary-700);
        }

        @media (max-width: 767px) {
            .banners-filter-group,
            .banners-toolbar-search,
            .banners-toolbar-type,
            .banners-toolbar-status,
            .banners-pagination-wrap,
            .banners-pagination {
                width: 100%;
                min-width: 100%;
            }

            .banners-pagination-summary {
                text-align: center;
                width: 100%;
            }

            .banners-pagination .pagination {
                justify-content: center;
            }
        }
    </style>
@endsection
