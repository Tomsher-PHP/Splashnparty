<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    private function authorizeFaqPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeFaqPermission('view_faqs');

        $query = Faq::orderBy('sort_order')->latest();

        // SEARCH (question / answer / category)
        if ($search = request('search')) {
            $query->where(function ($faqQuery) use ($search) {
                $faqQuery->where('question', 'like', '%' . $search . '%')
                    ->orWhere('answer', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        // CATEGORY FILTER
        if ($category = request('category')) {
            $query->where('category', 'like', '%' . $category . '%');
        }

        // STATUS FILTER (if you use active/inactive)
        if ($status = request('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status === 'active');
            }
        }

        $faqs = $query->paginate(10)->withQueryString();

        return view('faqs.index', compact('faqs'));
    }

     public function create()
    {
        $this->authorizeFaqPermission('create_faqs');

        return view('faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',

            'faqs' => 'required|array',
            'faqs.*.question' => 'required|string|max:255',
            'faqs.*.answer' => 'required|string',
            'faqs.*.sort_order' => 'nullable|integer',
        ]);

        $category = $request->category;

        foreach ($request->faqs as $faq) {
            Faq::create([
                'category' => $category,
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'sort_order' => $faq['sort_order'] ?? 0,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $faq->update([
            'title' => $request->title,
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    public function updateStatus(Faq $faq)
    {
        $faq->status = !$faq->status;
        $faq->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully'
        ]);
    }
}