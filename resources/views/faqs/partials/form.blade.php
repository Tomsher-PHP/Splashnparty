{{-- CATEGORY --}}
<div class="row g-3 mb-4">

    <div class="col-md-8">
        <label class="form-label fw-semibold">Category</label>

        <input type="text"
            name="category"
            class="form-control form-control-sm"
            value="{{ old('category', $isEdit ? $faq->category : '') }}"
            required>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Category Status</label>

        <select name="status" class="form-select form-select-sm">

            <option value="1"
                {{ old('status', $isEdit ? $faq->status : 1) == 1 ? 'selected' : '' }}>
                Active
            </option>

            <option value="0"
                {{ old('status', $isEdit ? $faq->status : 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>
    </div>

</div>

<div id="faqWrapper" class="mt-3">


    @foreach($details as $index => $item)

    <div class="faq-item border rounded p-3 mb-3">
        <div class="faq-header d-flex align-items-center gap-2 mb-3">
            <span class="badge bg-primary-600 faq-sl-no">{{ $index + 1 }}</span>
            <strong>FAQ Item</strong>
        </div>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Question</label>
                <input type="text"
                    name="faqs[{{ $index }}][question]"
                    class="form-control form-control-sm"
                    value="{{ $item['question'] ?? '' }}"
                    required>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Sort</label>
                <input type="number"
                    name="faqs[{{ $index }}][sort_order]"
                    class="form-control form-control-sm"
                    value="{{ $item['sort_order'] ?? 0 }}">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="faqs[{{ $index }}][status]"
                    class="form-select form-select-sm">
                    <option value="1"
                        {{ ($item['status'] ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ ($item['status'] ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    Answer
                </label>

                <div class="faq-answer-block border rounded-3">
                    {{-- QUILL EDITOR --}}
                    <div class="quill-editor"
                        data-input="answer_{{ $index }}"
                        style="height: 220px;">
                        {!! $item['answer'] ?? '' !!}
                    </div>

                    {{-- HIDDEN TEXTAREA --}}
                    <textarea
                        name="faqs[{{ $index }}][answer]"
                        id="answer_{{ $index }}"
                        class="d-none">{{ $item['answer'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <button type="button"
                    class="btn btn-light-danger btn-sm removeFaqBtn">
                    <i class="ri-delete-bin-line text-danger"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach

</div>

@section('script')
<script>
    let faqIndex = {{ count($details) }};

    document.getElementById('addFaqBtn').addEventListener('click', function() {
        let wrapper = document.getElementById('faqWrapper');
        let newItem = document.createElement('div');
        newItem.classList.add('faq-item', 'border', 'rounded', 'p-3', 'mb-3');
        newItem.innerHTML = `
            <div class="faq-header d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-primary-600 faq-sl-no">-</span>
                <strong>FAQ Item</strong>
            </div>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Question</label>
                    <input type="text"
                        name="faqs[${faqIndex}][question]"
                        class="form-control form-control-sm"
                        required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Sort</label>
                    <input type="number"
                        name="faqs[${faqIndex}][sort_order]"
                        class="form-control form-control-sm"
                        value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="faqs[${faqIndex}][status]" class="form-select form-select-sm">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Answer</label>
                    <div class="faq-answer-block border rounded-3">
                        <div class="quill-editor"
                            data-input="answer_${faqIndex}"
                            style="height: 220px;">
                        </div>
                        <textarea
                            name="faqs[${faqIndex}][answer]"
                            id="answer_${faqIndex}"
                            class="d-none"></textarea>
                    </div>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="button"
                        class="btn btn-light-danger btn-sm removeFaqBtn">
                        <i class="ri-delete-bin-line text-danger"></i>
                    </button>
                </div>
            </div>`;

        wrapper.appendChild(newItem);

        initQuillEditors(newItem);
        updateSlNos();
        faqIndex++;
    });

    document.addEventListener('click', function(e) {
        if (
            e.target.classList.contains('removeFaqBtn') ||
            e.target.closest('.removeFaqBtn')
        ) {
            e.target.closest('.faq-item').remove();
            updateSlNos();
        }

    });

    // quill editor starts
    document.addEventListener('DOMContentLoaded', function () {
        initQuillEditors();
    });

    function initQuillEditors(context = document) {
        context.querySelectorAll('.quill-editor').forEach(editor => {
            // AVOID MULTIPLE INIT
            if (editor.dataset.quillInit) {
                return;
            }

            const inputId = editor.dataset.input;
            const textarea = document.getElementById(inputId);
            const quill = new Quill(editor, {
                theme: 'snow',
                placeholder: 'Write answer here...',
                modules: {
                    toolbar: [
                        [{ font: [] }],
                        [{ size: ['small', false, 'large', 'huge'] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ color: [] }, { background: [] }],
                        [{ align: [] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['blockquote', 'link', 'image'],
                        ['clean']
                    ]
                }
            });

            // SYNC TO TEXTAREA
            quill.on('text-change', function () {
                textarea.value = quill.root.innerHTML;
            });
            editor.dataset.quillInit = "1";
        });
    }
    // Quill ends
    document.addEventListener('DOMContentLoaded', function() {
        updateSlNos();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.removeFaqBtn')) {
            let item = e.target.closest('.faq-item');
            item.remove();
        }
    });

    function updateSlNos() {
        document.querySelectorAll('#faqWrapper .faq-item').forEach((item, index) => {
            let badge = item.querySelector('.faq-sl-no');

            if (badge) {
                badge.innerText = index + 1;
            }
        });
    }
</script>
@endsection