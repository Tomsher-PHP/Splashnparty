@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">FAQs</h6>

    <div class="d-flex align-items-center gap-2">
        <span class="bg-primary-50 text-primary-600 px-20 py-8 rounded fw-medium text-sm">
            {{ $faqs->total() }} Total FAQs
        </span>

        @can('create_faqs')
        <a href="{{ route('faqs.create') }}"
            class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
            <i class="ri-add-line"></i>
            Add FAQ
        </a>
        @endcan
    </div>
</div>

<div class="card basic-data-table">
    <div class="card-body">

        <!-- FILTER -->
        <form method="GET" action="{{ route('faqs.index') }}" class="mb-20">
            <div class="d-flex flex-wrap align-items-end gap-3">

                <div>
                    <label class="fw-semibold text-sm mb-1">Keyword</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Search question / category">
                </div>

                <div>
                    <label class="fw-semibold text-sm mb-1">Category</label>
                    <input type="text" name="category" value="{{ request('category') }}"
                        class="form-control form-control-sm" placeholder="Category">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary-600">
                        <i class="ri-search-line"></i> Filter
                    </button>

                    <a href="{{ route('faqs.index') }}"
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
                        <th>Category</th>
                        <th>Question / Answer</th>
                        <th class="text-center">Sort</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Created</th>

                        @if(auth()->user()?->can('edit_faqs') || auth()->user()?->can('delete_faqs'))
                        <th class="text-center">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($faqs as $faq)
                    <tr>
                        <td class="text-center">
                            {{ $faqs->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <span class="badge bg-primary-50 text-sm text-primary-700">
                                {{ $faq->category }}
                            </span>
                        </td>

                        {{-- COUNT + INFO --}}
                        <td>

                            <span>
                                {{ is_array($faq->details) ? count($faq->details) : 0 }} Questions
                            </span>

                            @php
                            $details = $faq->details ?? [];
                            @endphp

                            <span class="ms-2"
                                tabindex="0"
                                data-bs-toggle="popover"
                                data-bs-html="true"
                                data-bs-trigger="focus"
                                title="FAQ Details"
                                data-bs-content="
                                            <div class='faq-popover-content'>
                                                @foreach($details as $item)
                                                    <div class='mb-2'>
                                                        <div class='fw-semibold text-dark'>
                                                            {{ $item['question'] ?? '' }}
                                                        </div>
                                                        <div class='text-muted small'>
                                                            {{ $item['answer'] ?? '' }}
                                                        </div>
                                                        <hr>
                                                    </div>
                                                @endforeach
                                            </div>
                                        ">
                                <i class="ri-information-line text-primary" style="cursor:pointer;"></i>
                            </span>

                        </td>

                        <td class="text-center">
                            {{ $faq->sort_order }}
                        </td>

                        <td class="text-center">

                            <span class="badge {{ $faq->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $faq->status ? 'Active' : 'Inactive' }}
                            </span>

                        </td>

                        <td class="text-center">
                            {{ optional($faq->created_at)->format('d M Y') }}
                        </td>

                        @if (auth()->user()?->can('edit_faqs') || auth()->user()?->can('delete_faqs'))
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                @can('edit_faqs')
                                <a href="{{ route('faqs.edit', $faq) }}"
                                    class="faq-icon-btn bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="Edit">
                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                </a>
                                @endcan
                                @can('delete_faqs')
                                <form action="{{ route('faqs.destroy', $faq) }}" method="POST"
                                    class="faq-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="faq-icon-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                        data-confirm-title="Delete FAQ"
                                        data-confirm-message="Are you sure you want to delete this FAQ?"
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
                        <td colspan="7" class="text-center py-32">
                            <div class="text-secondary-light">
                                <i class="ri-question-answer-line text-3xl d-block mb-8"></i>
                                No FAQs found.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        @if($faqs->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-24">
            <div class="text-secondary-light">
                Showing {{ $faqs->firstItem() }} to {{ $faqs->lastItem() }}
                of {{ $faqs->total() }} FAQs
            </div>

            <div>
                {{ $faqs->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif

    </div>
    <div class="card-footer bg-white border-0">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <small class="text-muted">
                    Showing {{ $faqs->firstItem() }} to {{ $faqs->lastItem() }} of {{ $faqs->total() }} entries
                </small>
            </div>
            <div>
                {{ $faqs->links() }}
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));

        popoverTriggerList.forEach(function(el) {
            new bootstrap.Popover(el, {
                html: true,
                sanitize: false,
                customClass: 'faq-popover',
            });
        });
    });

    document.querySelectorAll('.faq-delete-form button[type="submit"]').forEach(function(button) {
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