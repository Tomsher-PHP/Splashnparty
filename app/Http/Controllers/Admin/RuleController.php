<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rule as VenueRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RuleController extends Controller
{
    private function authorizeRulePermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeRulePermission('view_rules');

        $query = VenueRule::orderBy('sort_order')->latest();

        // Title Search
        if ($search = request('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Status Filter
        if ($status = request('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status === 'active');
            }
        }

        $rules = $query->paginate(10)->withQueryString();

        return view('rules.index', compact('rules'));
    }

    public function create()
    {
        $this->authorizeRulePermission('create_rules');

        return view('rules.create');
    }

    public function store(Request $request)
    {
        $this->authorizeRulePermission('create_rules');

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
            'show_in_email' => ['required', 'boolean'],
        ]);

        $data = $request->except(['image']);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/rules', 'public');
            $data['image'] = 'storage/' . $path;
        }

        VenueRule::create($data);

        return redirect()->route('rules.index')->with('success', 'Rule created successfully.');
    }

    public function edit(VenueRule $rule)
    {
        $this->authorizeRulePermission('edit_rules');

        return view('rules.edit', compact('rule'));
    }

    public function update(Request $request, VenueRule $rule)
    {
        $this->authorizeRulePermission('edit_rules');

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
            'show_in_email' => ['required', 'boolean'],
        ]);

        $data = $request->except(['image']);
        $data['updated_by'] = auth()->id();

        if ($request->input('remove_image') == 1 && $rule->image) {
            if (file_exists(public_path($rule->image))) {
                unlink(public_path($rule->image));
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($rule->image && file_exists(public_path($rule->image))) {
                unlink(public_path($rule->image));
            }

            $path = $request->file('image')->store('uploads/rules', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $rule->update($data);

        return redirect()->route('rules.index')->with('success', 'Rule updated successfully.');
    }

    public function updateStatus(Request $request, VenueRule $rule)
    {
        $this->authorizeRulePermission('edit_rules');

        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $rule->update([
            'status' => $validated['status'],
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('rules.index', [
            'search' => $request->input('search'),
            'status' => $request->input('status_filter'),
            'page' => $request->input('page'),
        ])->with('success', 'Rule status updated successfully.');
    }

    public function destroy(VenueRule $rule)
    {
        $this->authorizeRulePermission('delete_rules');

        if ($rule->image && file_exists(public_path($rule->image))) {
            unlink(public_path($rule->image));
        }

        $rule->delete();

        return redirect()->route('rules.index')->with('success', 'Rule deleted successfully.');
    }
}
