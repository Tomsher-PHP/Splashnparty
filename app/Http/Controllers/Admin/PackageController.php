<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    private function authorizePackagePermission($permission)
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizePackagePermission('view_packages');

        $query = Package::with('branch')->latest();

        if ($title = request('title')) {
            $query->where('title', 'like', "%{$title}%");
        }

        if ($branch = request('branch_id')) {
            $query->where('branch_id', $branch);
        }

        $packages = $query->paginate(10)->withQueryString();

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view('packages.index', compact('packages', 'branches'));
    }

    public function create()
    {
        $this->authorizePackagePermission('create_packages');
        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view('packages.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $this->authorizePackagePermission('create_packages');

        $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'title' => 'required|max:255',
            // 'food_type' => 'nullable|in:with_food,without_food',

            // With Food Prices
            'child_weekday_price_with_food' => 'nullable|numeric',
            'adult_weekday_price_with_food' => 'nullable|numeric',
            'child_weekend_price_with_food' => 'nullable|numeric',
            'adult_weekend_price_with_food' => 'nullable|numeric',

            // Without Food Prices
            'child_weekday_price_without_food' => 'nullable|numeric',
            'adult_weekday_price_without_food' => 'nullable|numeric',
            'child_weekend_price_without_food' => 'nullable|numeric',
            'adult_weekend_price_without_food' => 'nullable|numeric',

            'child_count_for_free_adult' => 'nullable|integer|min:0',

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',

            'days' => 'nullable|array',

            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Package::create([
            'branch_id' => $request->branch_id,
            'title' => $request->title,
            // 'food_type' => $request->food_type,

            // With Food Prices
            'child_weekday_price_with_food' => $request->child_weekday_price_with_food,
            'adult_weekday_price_with_food' => $request->adult_weekday_price_with_food,
            'child_weekend_price_with_food' => $request->child_weekend_price_with_food,
            'adult_weekend_price_with_food' => $request->adult_weekend_price_with_food,

            // Without Food Prices
            'child_weekday_price_without_food' => $request->child_weekday_price_without_food,
            'adult_weekday_price_without_food' => $request->adult_weekday_price_without_food,
            'child_weekend_price_without_food' => $request->child_weekend_price_without_food,
            'adult_weekend_price_without_food' => $request->adult_weekend_price_without_food,

            'child_count_for_free_adult' => $request->child_count_for_free_adult ?? 0,

            'start_date' => $request->start_date,
            'end_date' => $request->end_date,

            'days' => $request->days,

            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('packages.index')
            ->with('success', 'Package created successfully');
    }

    public function edit(Package $package)
    {
        $this->authorizePackagePermission('edit_packages');

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view('packages.edit', compact('package', 'branches'));
    }

    public function update(Request $request, Package $package)
    {
        $this->authorizePackagePermission('edit_packages');

        $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'title' => 'required|max:255',
            // 'food_type' => 'nullable|in:with_food,without_food',

            // With Food Prices
            'child_weekday_price_with_food' => 'nullable|numeric',
            'adult_weekday_price_with_food' => 'nullable|numeric',
            'child_weekend_price_with_food' => 'nullable|numeric',
            'adult_weekend_price_with_food' => 'nullable|numeric',

            // Without Food Prices
            'child_weekday_price_without_food' => 'nullable|numeric',
            'adult_weekday_price_without_food' => 'nullable|numeric',
            'child_weekend_price_without_food' => 'nullable|numeric',
            'adult_weekend_price_without_food' => 'nullable|numeric',

            'child_count_for_free_adult' => 'nullable|integer|min:0',

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',

            'days' => 'nullable|array',

            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $package->update([
            'branch_id' => $request->branch_id,
            'title' => $request->title,
            // 'food_type' => $request->food_type,

            // With Food Prices
            'child_weekday_price_with_food' => $request->child_weekday_price_with_food,
            'adult_weekday_price_with_food' => $request->adult_weekday_price_with_food,
            'child_weekend_price_with_food' => $request->child_weekend_price_with_food,
            'adult_weekend_price_with_food' => $request->adult_weekend_price_with_food,

            // Without Food Prices
            'child_weekday_price_without_food' => $request->child_weekday_price_without_food,
            'adult_weekday_price_without_food' => $request->adult_weekday_price_without_food,
            'child_weekend_price_without_food' => $request->child_weekend_price_without_food,
            'adult_weekend_price_without_food' => $request->adult_weekend_price_without_food,

            'child_count_for_free_adult' => $request->child_count_for_free_adult ?? 0,

            'start_date' => $request->start_date,
            'end_date' => $request->end_date,

            'days' => $request->days,

            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('packages.index')
            ->with('success', 'Package updated successfully');
    }

    public function destroy(Package $package)
    {
        $this->authorizePackagePermission('delete_packages');

        $package->delete();

        return back()->with('success', 'Deleted successfully');
    }
}