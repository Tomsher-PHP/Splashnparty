<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthdayPackage;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BirthdayPackageController extends Controller
{
    private function authorizeBirthdayPackagePermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }

    public function index()
    {
        $this->authorizeBirthdayPackagePermission(
            'view_birthday_packages'
        );

        $query = BirthdayPackage::with('branch')
            ->latest();

        // KEYWORD SEARCH
        if ($keyword = request('keyword')) {

            $query->where(
                'title',
                'like',
                '%' . $keyword . '%'
            );
        }

        $packages = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'birthday-packages.packages.index',
            compact('packages')
        );
    }

    public function create()
    {
        $this->authorizeBirthdayPackagePermission(
            'create_birthday_packages'
        );

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view(
            'birthday-packages.packages.create',
            compact('branches')
        );
    }

    public function store(Request $request)
    {
        $this->authorizeBirthdayPackagePermission(
            'create_birthday_packages'
        );

        $request->validate([

            'branch_id' => 'nullable|exists:branches,id',

            'title' => 'required|string|max:255',

            'slug' => 'required|string|max:255|unique:birthday_packages,slug',

            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'price' => 'nullable|string|max:255',

            'highlighted_description' => 'nullable|string',

            'description' => 'nullable|string',

            'sort_order' => 'nullable|integer',

            'status' => 'required|boolean',
            'minimum_kids' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'weekday_rate' => 'nullable|numeric|min:0',
            'weekend_rate' => 'nullable|numeric|min:0',
        ]);

        $image = null;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store(
                'uploads/birthday-packages',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $bannerImage = null;

        if ($request->hasFile('banner_image')) {

            $path = $request->file('banner_image')->store(
                'uploads/birthday-packages/banner',
                'public'
            );

            $bannerImage = 'storage/' . $path;
        }

        BirthdayPackage::create([

            'branch_id' => $request->branch_id,

            'title' => $request->title,

            'slug' => Str::slug($request->slug),

            'image' => $image,

            'banner_image' => $bannerImage,

            'price' => $request->price,

            'highlighted_description' => $request->highlighted_description,

            'description' => $request->description,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status,
        ]);

        return redirect()
            ->route('birthday-packages.index')
            ->with(
                'success',
                'Birthday package created successfully'
            );
    }

    public function edit(
        BirthdayPackage $birthday_package
    ) {

        $this->authorizeBirthdayPackagePermission(
            'edit_birthday_packages'
        );

        $branches = Branch::where(
            'status',
            1
        )->orderBy('title')->get();

        return view(
            'birthday-packages.packages.edit',
            compact(
                'birthday_package',
                'branches'
            )
        );
    }

    public function update(
        Request $request,
        BirthdayPackage $birthday_package
    ) {

        $this->authorizeBirthdayPackagePermission(
            'edit_birthday_packages'
        );

        $request->validate([

            'branch_id' => 'nullable|exists:branches,id',

            'title' => 'required|string|max:255',

            'slug' => 'required|string|max:255|unique:birthday_packages,slug,' . $birthday_package->id,

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'price' => 'nullable|string|max:255',

            'highlighted_description' => 'nullable|string',

            'description' => 'nullable|string',

            'sort_order' => 'nullable|integer',

            'status' => 'required|boolean',

            'minimum_kids' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'weekday_rate' => 'nullable|numeric|min:0',
            'weekend_rate' => 'nullable|numeric|min:0',
        ]);

        $image = $birthday_package->image;

        if ($request->hasFile('image')) {

            if (
                $birthday_package->image &&
                file_exists(
                    public_path($birthday_package->image)
                )
            ) {

                unlink(
                    public_path($birthday_package->image)
                );
            }

            $path = $request->file('image')->store(
                'uploads/birthday-packages',
                'public'
            );

            $image = 'storage/' . $path;
        }

        $bannerImage = $birthday_package->banner_image;

        if ($request->hasFile('banner_image')) {

            if (
                $birthday_package->banner_image &&
                file_exists(
                    public_path($birthday_package->banner_image)
                )
            ) {

                unlink(
                    public_path($birthday_package->banner_image)
                );
            }

            $path = $request->file('banner_image')->store(
                'uploads/birthday-packages/banner',
                'public'
            );

            $bannerImage = 'storage/' . $path;
        }

        $birthday_package->update([

            'branch_id' => $request->branch_id,

            'title' => $request->title,

            'slug' => Str::slug($request->slug),

            'image' => $image,

            'banner_image' => $bannerImage,

            'price' => $request->price,

            'highlighted_description' => $request->highlighted_description,

            'description' => $request->description,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status,

            'minimum_kids' => $request->minimum_kids,
            'duration' => $request->duration,
            'weekday_rate' => $request->weekday_rate,
            'weekend_rate' => $request->weekend_rate,
        ]);

        return redirect()
            ->route('birthday-packages.index')
            ->with(
                'success',
                'Birthday package updated successfully'
            );
    }

    public function destroy(
        BirthdayPackage $birthday_package
    ) {

        $this->authorizeBirthdayPackagePermission(
            'delete_birthday_packages'
        );

        if (
            $birthday_package->image &&
            file_exists(
                public_path($birthday_package->image)
            )
        ) {

            unlink(
                public_path($birthday_package->image)
            );
        }

        if (
            $birthday_package->banner_image &&
            file_exists(
                public_path($birthday_package->banner_image)
            )
        ) {

            unlink(
                public_path($birthday_package->banner_image)
            );
        }

        $birthday_package->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }
}