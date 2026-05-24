<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    private function authorizeTestimonialPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeTestimonialPermission('view_testimonials');

        $query = Testimonial::orderBy('sort_order')->latest();

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('title', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($status = request('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status === 'active');
            }
        }

        $testimonials = $query->paginate(10)->withQueryString();

        return view('testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $this->authorizeTestimonialPermission('create_testimonials');

        return view('testimonials.create');
    }

    public function store(Request $request)
    {
        $this->authorizeTestimonialPermission('create_testimonials');

        $validated = $request->validate($this->rules(), $this->messages());

        Testimonial::create($validated);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        $this->authorizeTestimonialPermission('edit_testimonials');

        return view('testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $this->authorizeTestimonialPermission('edit_testimonials');

        $validated = $request->validate($this->rules(), $this->messages());

        $testimonial->update($validated);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function updateStatus(Request $request, Testimonial $testimonial)
    {
        $this->authorizeTestimonialPermission('edit_testimonials');

        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $testimonial->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('testimonials.index', [
            'search' => $request->input('search'),
            'status' => $request->input('status_filter'),
            'page' => $request->input('page'),
        ])->with('success', 'Testimonial status updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $this->authorizeTestimonialPermission('delete_testimonials');

        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'star_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'description' => ['required', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ];
    }

    private function messages(): array
    {
        return [
            'star_rating.required' => 'Please select a star rating.',
            'star_rating.min' => 'Star rating must be at least 1.',
            'star_rating.max' => 'Star rating cannot be greater than 5.',
        ];
    }
}
