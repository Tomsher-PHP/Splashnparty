@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Client Logos</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
                {{ $clientLogos->total() }} Total Client Logos
            </span>
            @can('create_client_logos')
                <a href="{{ route('client-logos.create') }}"
                    class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                    <i class="ri-add-line"></i>
                    Add Client Logo
                </a>
            @endcan
        </div>
    </div>

    <div class="card basic-data-table">
        <div class="card-body">
            <form method="GET" action="{{ route('client-logos.index') }}" class="client-logos-toolbar-wrap mb-20">
                <div class="d-flex flex-wrap align-items-end gap-3">
                    <div class="d-flex flex-wrap align-items-end gap-3">
                        <div class="client-logos-filter-group">
                            <label for="clientLogosSearch" class="client-logos-filter-label">Keyword</label>
                            <input type="text" id="clientLogosSearch" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm client-logos-filter-input client-logos-toolbar-search"
                                placeholder="Search client logos">
                        </div>

                        <div class="client-logos-filter-group">
                            <label for="clientLogosStatusFilter" class="client-logos-filter-label">Status</label>
                            <select id="clientLogosStatusFilter" name="status"
                                class="form-control form-control-sm client-logos-filter-select client-logos-toolbar-status">
                                <option value="">All</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('client-logos.index') }}"
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
                            <th scope="col">Client</th>
                            <th scope="col" class="text-center">Sort Order</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Created Date</th>
                            @if (auth()->user()?->can('edit_client_logos') || auth()->user()?->can('delete_client_logos'))
                                <th scope="col" class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="clientLogosTableBody">
                        @forelse ($clientLogos as $clientLogo)
                            <tr>
                                <td class="text-center">{{ $clientLogos->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-12">
                                        <div class="client-logo-thumb">
                                            <img src="{{ asset('storage/' . $clientLogo->logo) }}" alt="{{ $clientLogo->title }}">
                                        </div>
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-dark">{{ $clientLogo->title }}</div>
                                            @if ($clientLogo->link)
                                                <a href="{{ $clientLogo->link }}" target="_blank" rel="noopener"
                                                    class="text-secondary-light small client-logo-link">
                                                    {{ $clientLogo->link }}
                                                </a>
                                            @else
                                                <div class="text-secondary-light small">-</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $clientLogo->sort_order }}</td>
                                <td class="text-center">
                                    @can('edit_client_logos')
                                        <form action="{{ route('client-logos.update-status', $clientLogo) }}" method="POST"
                                            class="client-logo-status-form d-inline-flex align-items-center gap-12">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $clientLogo->status ? 0 : 1 }}">
                                            @if (request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            @if (request('status'))
                                                <input type="hidden" name="status_filter" value="{{ request('status') }}">
                                            @endif
                                            @if (request('page'))
                                                <input type="hidden" name="page" value="{{ request('page') }}">
                                            @endif

                                            <label class="client-logo-status-switch mb-0">
                                                <input type="checkbox" class="client-logo-status-switch__input"
                                                    {{ $clientLogo->status ? 'checked' : '' }}>
                                                <span class="client-logo-status-switch__slider"></span>
                                            </label>
                                        </form>
                                    @else
                                        <span
                                            class="badge {{ $clientLogo->status ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }}">
                                            {{ $clientLogo->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="text-center">{{ optional($clientLogo->created_at)->format('d M Y') }}</td>
                                @if (auth()->user()?->can('edit_client_logos') || auth()->user()?->can('delete_client_logos'))
                                    <td class="text-center">
                                        <div class="client-logos-action-buttons">
                                            @can('edit_client_logos')
                                                <a href="{{ route('client-logos.edit', $clientLogo) }}"
                                                    class="client-logos-icon-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                    title="Edit">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('delete_client_logos')
                                                <form action="{{ route('client-logos.destroy', $clientLogo) }}" method="POST"
                                                    class="client-logo-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="client-logos-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-confirm-title="Delete Client Logo"
                                                        data-confirm-message="Are you sure you want to delete this client logo?"
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
                            <tr id="clientLogosEmptyState">
                                <td colspan="{{ auth()->user()?->can('edit_client_logos') || auth()->user()?->can('delete_client_logos') ? 6 : 5 }}"
                                    class="text-center py-32">
                                    <div class="text-secondary-light">
                                        <i class="ri-layout-grid-line text-3xl d-block mb-8"></i>
                                        No client logos created yet.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($clientLogos->hasPages())
                <div class="client-logos-pagination-wrap mt-24">
                    <div class="client-logos-pagination-summary text-secondary-light">
                        Showing {{ $clientLogos->firstItem() }} to {{ $clientLogos->lastItem() }} of
                        {{ $clientLogos->total() }} client logos
                    </div>
                    <div class="client-logos-pagination">
                        {{ $clientLogos->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.client-logo-status-switch__input').forEach(function(toggle) {
            toggle.addEventListener('change', function(event) {
                const form = event.target.closest('.client-logo-status-form');
                const nextState = event.target.checked ? 'activate' : 'inactivate';

                window.openAppConfirm({
                    title: 'Update Client Logo Status',
                    message: `Are you sure you want to ${nextState} this client logo?`,
                    buttonText: 'Yes, Update',
                    buttonClass: 'btn btn-sm btn-primary-600',
                    onConfirm: function() {
                        form.submit();
                    }
                });

                event.target.checked = !event.target.checked;
            });
        });

        document.querySelectorAll('.client-logo-delete-form button[type="submit"]').forEach(function(button) {
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
        .client-logos-filter-group {
            min-width: 180px;
        }

        .client-logos-filter-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary-light);
            margin-bottom: 6px;
            display: block;
        }

        .client-logos-filter-input,
        .client-logos-filter-select {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--input-form-light);
            background: #fff;
        }

        .client-logos-toolbar-search {
            min-width: 220px;
        }

        .client-logos-toolbar-status {
            min-width: 150px;
        }

        .client-logo-thumb {
            width: 72px;
            height: 48px;
            border-radius: 8px;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid var(--input-form-light);
            flex-shrink: 0;
            padding: 8px;
        }

        .client-logo-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .client-logo-link {
            max-width: 420px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }

        .client-logos-action-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .client-logos-icon-btn {
            border: 0;
        }

        .client-logo-status-switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .client-logo-status-switch__input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .client-logo-status-switch__slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #dbe3ef;
            transition: .2s ease;
            border-radius: 999px;
        }

        .client-logo-status-switch__slider:before {
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

        .client-logo-status-switch__input:checked+.client-logo-status-switch__slider {
            background-color: #22c55e;
        }

        .client-logo-status-switch__input:checked+.client-logo-status-switch__slider:before {
            transform: translateX(14px);
        }

        .client-logos-pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid var(--input-form-light);
        }

        .client-logos-pagination-summary {
            font-size: 14px;
            font-weight: 500;
        }

        .client-logos-pagination {
            margin-left: auto;
        }

        .client-logos-pagination .pagination {
            margin-bottom: 0;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .client-logos-pagination .page-link {
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

        .client-logos-pagination .page-item.active .page-link {
            background: var(--primary-600);
            border-color: var(--primary-600);
            color: #fff;
        }

        .client-logos-pagination .page-item.disabled .page-link {
            color: var(--text-secondary-light);
            background: #f8fafc;
            border-color: var(--input-form-light);
        }

        .client-logos-pagination .page-link:hover {
            background: var(--primary-50);
            border-color: var(--primary-200);
            color: var(--primary-700);
        }

        @media (max-width: 767px) {
            .client-logos-filter-group,
            .client-logos-toolbar-search,
            .client-logos-toolbar-status,
            .client-logos-pagination-wrap,
            .client-logos-pagination {
                width: 100%;
                min-width: 100%;
            }

            .client-logos-pagination-summary {
                text-align: center;
                width: 100%;
            }

            .client-logos-pagination .pagination {
                justify-content: center;
            }
        }
    </style>
@endsection
