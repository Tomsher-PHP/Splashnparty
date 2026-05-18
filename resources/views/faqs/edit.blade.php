@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-4">Edit FAQs</h6>
        <p class="mb-0 text-secondary-light">
            Update FAQ questions and answers.
        </p>
    </div>

    <a href="{{ route('faqs.index') }}"
        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="ri-arrow-left-line"></i>
        Back to FAQs
    </a>
</div>

<form action="{{ route('faqs.update', $faq->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">

            @include('faqs.partials.form', [
                'faq' => $faq,
                'details' => old('faqs', $faq->details ?? []),
                'isEdit' => true
            ])

        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">

            <button type="button" id="addFaqBtn"
                class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                <i class="ri-add-line"></i>
                Add More Question
            </button>

            <div class="d-flex gap-2">

                <a href="{{ route('faqs.index') }}"
                    class="btn btn-sm btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-sm btn-primary-600">
                    Update FAQs
                </button>

            </div>

        </div>
    </div>
</form>
@endsection