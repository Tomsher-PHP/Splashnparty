@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Testimonials</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
                {{ $testimonials->total() }} Total Testimonials
            </span>
            @can('create_testimonials')
                <a href="{{ route('testimonials.create') }}"
                    class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                    <i class="ri-add-line"></i>
                    Add Testimonial
                </a>
            @endcan
        </div>
    </div>

    <div class="card basic-data-table">
        <div class="card-body">
            <form method="GET" action="{{ route('testimonials.index') }}" class="testimonials-toolbar-wrap mb-20">
                <div class="d-flex flex-wrap align-items-end gap-3">
                    <div class="d-flex flex-wrap align-items-end gap-3">
                        <div class="testimonials-filter-group">
                            <label for="testimonialsSearch" class="testimonials-filter-label">Keyword</label>
                            <input type="text" id="testimonialsSearch" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm testimonials-filter-input testimonials-toolbar-search"
                                placeholder="Search testimonials">
                        </div>

                        <div class="testimonials-filter-group">
                            <label for="testimonialsStatusFilter" class="testimonials-filter-label">Status</label>
                            <select id="testimonialsStatusFilter" name="status"
                                class="form-control form-control-sm testimonials-filter-select testimonials-toolbar-status">
                                <option value="">All</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('testimonials.index') }}"
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
                            <th scope="col" class="text-center" style="width: 80px;">Sl No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Title & Description</th>
                            <th scope="col" class="text-center" style="width: 120px;">Sort Order</th>
                            <th scope="col" class="text-center" style="width: 120px;">Status</th>
                            @if (auth()->user()?->can('edit_testimonials') || auth()->user()?->can('delete_testimonials'))
                                <th scope="col" class="text-center" style="width: 120px;">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="testimonialsTableBody">
                        @forelse ($testimonials as $testimonial)
                            <tr>
                                <td class="text-center">{{ $testimonials->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $testimonial->name }}</div>
                                    
                                </td>
                                <td>
                                    <div class="text-warning-main d-flex align-items-center gap-1" title="{{ $testimonial->star_rating }} Stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $testimonial->star_rating)
                                                <i class="ri-star-fill text-lg"></i>
                                            @else
                                                <i class="ri-star-line text-lg text-secondary-light"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    @if ($testimonial->title)
                                        <div class="text-black" style="font-weight: 500;">{{ $testimonial->title }}</div>
                                    @else
                                        <div class="text-black" style="font-weight: 500;">-</div>
                                    @endif
                                    <div class="testimonial-desc-cell text-secondary-light text-wrap" style="max-width: 400px; min-width: 250px;">
                                        {{ Str::limit($testimonial->description, 100) }}
                                    </div>
                                </td>
                                <td class="text-center">{{ $testimonial->sort_order }}</td>
                                <td class="text-center">
                                    @can('edit_testimonials')
                                        <form action="{{ route('testimonials.update-status', $testimonial) }}" method="POST"
                                            class="testimonial-status-form d-inline-flex align-items-center gap-12">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $testimonial->status ? 0 : 1 }}">
                                            @if (request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            @if (request('status'))
                                                <input type="hidden" name="status_filter" value="{{ request('status') }}">
                                            @endif
                                            @if (request('page'))
                                                <input type="hidden" name="page" value="{{ request('page') }}">
                                            @endif

                                            <label class="testimonial-status-switch mb-0">
                                                <input type="checkbox" class="testimonial-status-switch__input"
                                                    {{ $testimonial->status ? 'checked' : '' }}>
                                                <span class="testimonial-status-switch__slider"></span>
                                            </label>
                                        </form>
                                    @else
                                        <span
                                            class="badge {{ $testimonial->status ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }}">
                                            {{ $testimonial->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    @endcan
                                </td>
                                @if (auth()->user()?->can('edit_testimonials') || auth()->user()?->can('delete_testimonials'))
                                    <td class="text-center">
                                        <div class="testimonials-action-buttons">
                                            @can('edit_testimonials')
                                                <a href="{{ route('testimonials.edit', $testimonial) }}"
                                                    class="testimonials-icon-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                    title="Edit">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('delete_testimonials')
                                                <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST"
                                                    class="testimonial-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="testimonials-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-confirm-title="Delete Testimonial"
                                                        data-confirm-message="Are you sure you want to delete this testimonial?"
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
                            <tr id="testimonialsEmptyState">
                                <td colspan="{{ auth()->user()?->can('edit_testimonials') || auth()->user()?->can('delete_testimonials') ? 7 : 6 }}"
                                    class="text-center py-32">
                                    <div class="text-secondary-light">
                                        <i class="ri-chat-quote-line text-3xl d-block mb-8"></i>
                                        No testimonials created yet.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($testimonials->hasPages())
                <div class="testimonials-pagination-wrap mt-24">
                    <div class="testimonials-pagination-summary text-secondary-light">
                        Showing {{ $testimonials->firstItem() }} to {{ $testimonials->lastItem() }} of
                        {{ $testimonials->total() }} testimonials
                    </div>
                    <div class="testimonials-pagination">
                        {{ $testimonials->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.testimonial-status-switch__input').forEach(function(toggle) {
            toggle.addEventListener('change', function(event) {
                const form = event.target.closest('.testimonial-status-form');
                const nextState = event.target.checked ? 'activate' : 'inactivate';

                window.openAppConfirm({
                    title: 'Update Testimonial Status',
                    message: `Are you sure you want to ${nextState} this testimonial?`,
                    buttonText: 'Yes, Update',
                    buttonClass: 'btn btn-sm btn-primary-600',
                    onConfirm: function() {
                        form.submit();
                    }
                });

                event.target.checked = !event.target.checked;
            });
        });

        document.querySelectorAll('.testimonial-delete-form button[type="submit"]').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const form = button.closest('form');

                window.openAppConfirm({
                    title: button.dataset.confirmTitle || 'Delete',
                    message: button.dataset.confirmMessage || 'Are you sure you want to delete this testimonial?',
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
        .testimonials-filter-group {
            min-width: 180px;
        }

        .testimonials-filter-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary-light);
            margin-bottom: 6px;
            display: block;
        }

        .testimonials-filter-input,
        .testimonials-filter-select {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--input-form-light);
            background: #fff;
        }

        .testimonials-toolbar-search {
            min-width: 220px;
        }

        .testimonials-toolbar-status {
            min-width: 150px;
        }

        .testimonials-action-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .testimonials-icon-btn {
            border: 0;
        }

        .testimonial-status-switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .testimonial-status-switch__input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .testimonial-status-switch__slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #dbe3ef;
            transition: .2s ease;
            border-radius: 999px;
        }

        .testimonial-status-switch__slider:before {
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

        .testimonial-status-switch__input:checked+.testimonial-status-switch__slider {
            background-color: #22c55e;
        }

        .testimonial-status-switch__input:checked+.testimonial-status-switch__slider:before {
            transform: translateX(14px);
        }

        .testimonials-pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid var(--input-form-light);
        }

        .testimonials-pagination-summary {
            font-size: 14px;
            font-weight: 500;
        }

        .testimonials-pagination {
            margin-left: auto;
        }

        .testimonials-pagination .pagination {
            margin-bottom: 0;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .testimonials-pagination .page-link {
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

        .testimonials-pagination .page-item.active .page-link {
            background: var(--primary-600);
            border-color: var(--primary-600);
            color: #fff;
        }

        .testimonials-pagination .page-item.disabled .page-link {
            color: var(--text-secondary-light);
            background: #f8fafc;
            border-color: var(--input-form-light);
        }

        .testimonials-pagination .page-link:hover {
            background: var(--primary-50);
            border-color: var(--primary-200);
            color: var(--primary-700);
        }

        @media (max-width: 767px) {
            .testimonials-filter-group,
            .testimonials-toolbar-search,
            .testimonials-toolbar-status,
            .testimonials-pagination-wrap,
            .testimonials-pagination {
                width: 100%;
                min-width: 100%;
            }

            .testimonials-pagination-summary {
                text-align: center;
                width: 100%;
            }

            .testimonials-pagination .pagination {
                justify-content: center;
            }
        }
    </style>
@endsection
