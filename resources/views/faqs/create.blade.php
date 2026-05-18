@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-4">Create FAQs</h6>
        <p class="mb-0 text-secondary-light">
            Add a category with multiple questions and answers.
        </p>
    </div>

    <a href="{{ route('faqs.index') }}"
        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="ri-arrow-left-line"></i>
        Back to FAQs
    </a>
</div>

<form action="{{ route('faqs.store') }}" method="POST">
    @csrf

    <div class="card">
        <div class="card-body">

            {{-- CATEGORY (ONCE ONLY) --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Category</label>
                <input type="text" name="category" class="form-control"
                    placeholder="Enter FAQ category (e.g. Shipping, Payment)" required>
            </div>

            <br>

            {{-- FAQ ROWS --}}
            <div id="faqWrapper">

                <div class="faq-item border rounded p-3 mb-3">

                    <div class="row g-3">

                        {{-- QUESTION --}}
                        <div class="col-md-10">
                            <label class="form-label fw-semibold">Question</label>
                            <input type="text" name="faqs[0][question]" class="form-control"
                                placeholder="Enter question" required>
                                
                        </div>

                        {{-- SORT --}}
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Sort</label>
                            <input type="number" name="faqs[0][sort_order]" class="form-control" value="0">
                        </div>

                        {{-- ANSWER + DELETE SAME LINE --}}
                        <div class="col-md-10">
                            <label class="form-label fw-semibold">Answer</label>
                            <textarea name="faqs[0][answer]" class="form-control" rows="2"
                                placeholder="Enter answer" required></textarea>
                        </div>

                        <div class="col-md-2 d-flex align-items-end justify-content-end">
                            <button type="button"
                                class="btn btn-light-danger btn-sm  d-flex align-items-center justify-content-center"
                                title="Delete">
                                <i class="ri-delete-bin-line remove-faq text-danger"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            {{-- ADD MORE --}}
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
                    Save FAQ
                </button>
            </div>
        </div>
    </div>

</form>
@endsection

@section('script')
<script>
    let faqIndex = 1;

    document.getElementById('addFaqBtn').addEventListener('click', function() {

        let wrapper = document.getElementById('faqWrapper');

        let newItem = document.createElement('div');
        newItem.classList.add('faq-item', 'border', 'rounded', 'p-3', 'mb-3');

        newItem.innerHTML = `
    <div class="row g-3">

        <div class="col-md-10">
            <label class="form-label fw-semibold">Question</label>
            <input type="text" name="faqs[${faqIndex}][question]" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-semibold">Sort</label>
            <input type="number" name="faqs[${faqIndex}][sort_order]" class="form-control" value="0">
        </div>

        <div class="col-md-10">
            <label class="form-label fw-semibold">Answer</label>
            <textarea name="faqs[${faqIndex}][answer]" class="form-control" rows="2" required></textarea>
        </div>

        <div class="col-md-2 d-flex align-items-end justify-content-end">
            <button type="button"
                class="btn btn-light-danger btn-sm  d-flex align-items-center justify-content-center"
                title="Delete">
                <i class="remove-faq ri-delete-bin-line text-danger"></i>
            </button>
        </div>

    </div>
`;

        wrapper.appendChild(newItem);
        faqIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-faq')) {
            e.target.closest('.faq-item').remove();
        }
    });
</script>
@endsection