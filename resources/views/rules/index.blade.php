@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Manage Rules</h6>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $rules->total() }} Total Rules
        </span>

        @can('create_rules')
        <a href="{{ route('rules.create') }}"
            class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
            <i class="ri-add-line"></i>
            Add Rule
        </a>
        @endcan
    </div>
</div>

<div class="card basic-data-table">
    <div class="card-body">

        <!-- FILTER -->
        <form method="GET" action="{{ route('rules.index') }}" class="mb-20">
            <div class="d-flex flex-wrap align-items-end gap-3">

                <div>
                    <label class="fw-semibold text-sm mb-1">Title Keyword</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Search title...">
                </div>



                <div>
                    <label class="fw-semibold text-sm mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>

                    <a href="{{ route('rules.index') }}"
                        class="btn btn-sm btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Title</th>
                        <th>Preview</th>
                        <th class="text-center">Sort</th>
                        <th class="text-center">Show in Email</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Created</th>
                        @if(auth()->user()?->can('edit_rules') || auth()->user()?->can('delete_rules'))
                        <th class="text-center">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($rules as $rule)
                    <tr>
                        <td class="text-center">
                            {{ $rules->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <span class="fw-semibold text-primary-light">{{ $rule->title }}</span>
                        </td>

                        <td>
                            @if($rule->image)
                                <a href="{{ asset($rule->image) }}" target="_blank" class="d-inline-block mb-1">
                                    <img src="{{ asset($rule->image) }}" width="60" class="rounded border" alt="Rule image">
                                </a>
                            @endif
                            @if($rule->content)
                                <div class="text-wrap text-secondary-light small" style="max-width: 300px; max-height: 80px; overflow: hidden; text-overflow: ellipsis;">
                                    {!! strip_tags($rule->content) !!}
                                </div>
                            @endif
                            @if(!$rule->image && !$rule->content)
                                <span class="text-muted small">No preview available</span>
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $rule->sort_order }}
                        </td>

                        <td class="text-center">
                            @if($rule->show_in_email)
                                <span class="badge bg-success-50 text-success-600 px-12 py-6 rounded fw-medium text-sm">Yes</span>
                            @else
                                <span class="badge bg-danger-50 text-danger-600 px-12 py-6 rounded fw-medium text-sm">No</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @can('edit_rules')
                            <form action="{{ route('rules.update-status', $rule) }}" method="POST"
                                class="rule-status-form d-inline-flex align-items-center">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $rule->status ? 0 : 1 }}">
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if (request('status'))
                                    <input type="hidden" name="status_filter" value="{{ request('status') }}">
                                @endif
                                @if (request('page'))
                                    <input type="hidden" name="page" value="{{ request('page') }}">
                                @endif

                                <label class="rule-status-switch mb-0">
                                    <input type="checkbox" class="rule-status-switch__input"
                                        {{ $rule->status ? 'checked' : '' }}>
                                    <span class="rule-status-switch__slider"></span>
                                </label>
                            </form>
                            @else
                            <span class="badge {{ $rule->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $rule->status ? 'Active' : 'Inactive' }}
                            </span>
                            @endcan
                        </td>

                        <td class="text-center">
                            {{ optional($rule->created_at)->format('d M Y') }}
                        </td>

                        @if (auth()->user()?->can('edit_rules') || auth()->user()?->can('delete_rules'))
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                @can('edit_rules')
                                <a href="{{ route('rules.edit', $rule) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="Edit">
                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                </a>
                                @endcan

                                @can('delete_rules')
                                <form action="{{ route('rules.destroy', $rule) }}" method="POST"
                                    class="rule-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                        data-confirm-title="Delete Rule"
                                        data-confirm-message="Are you sure you want to delete this rule?"
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
                        <td colspan="8" class="text-center py-32">
                            <div class="text-secondary-light">
                                <i class="ri-shield-line text-3xl d-block mb-8"></i>
                                No rules found.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    @if ($rules->hasPages())
    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <small class="text-muted">
                    Showing {{ $rules->firstItem() }} to {{ $rules->lastItem() }} of {{ $rules->total() }} entries
                </small>
            </div>
            <div>
                {{ $rules->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle status toggle switches
        document.querySelectorAll('.rule-status-switch__input').forEach(function(toggle) {
            toggle.addEventListener('change', function(event) {
                const form = event.target.closest('.rule-status-form');
                const nextState = event.target.checked ? 'activate' : 'inactivate';

                window.openAppConfirm({
                    title: 'Update Rule Status',
                    message: `Are you sure you want to ${nextState} this rule?`,
                    buttonText: 'Yes, Update',
                    buttonClass: 'btn btn-sm btn-primary-600',
                    onConfirm: function() {
                        form.submit();
                    },
                    onCancel: function() {
                        toggle.checked = !toggle.checked; // Reset checkbox state if cancelled
                    }
                });
            });
        });

        // Handle delete form submissions
        document.querySelectorAll('.rule-delete-form').forEach(function(form) {
            const button = form.querySelector('button[type="submit"]');
            button.addEventListener('click', function(event) {
                event.preventDefault();

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
    });
</script>
@endsection

@section('style')
<style>
    .rule-status-switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .rule-status-switch__input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .rule-status-switch__slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background-color: #dbe3ef;
        transition: .2s ease;
        border-radius: 999px;
    }

    .rule-status-switch__slider:before {
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

    .rule-status-switch__input:checked+.rule-status-switch__slider {
        background-color: #22c55e;
    }

    .rule-status-switch__input:checked+.rule-status-switch__slider:before {
        transform: translateX(14px);
    }
</style>
@endsection
