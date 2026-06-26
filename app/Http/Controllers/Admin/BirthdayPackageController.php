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

        // BRANCH FILTER
        if ($branchId = request('branch_id')) {
            $query->where('branch_id', $branchId);
        }

        // STATUS FILTER
        if (request()->has('status') && request('status') !== null && request('status') !== '') {
            $query->where('status', request('status'));
        }

        $packages = $query
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::where('status', 1)
            ->orderBy('title')
            ->get();

        return view(
            'birthday-packages.packages.index',
            compact('packages', 'branches')
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

        $allFaqs = \App\Models\Faq::where('status', 1)->orderBy('sort_order', 'asc')->get();

        return view(
            'birthday-packages.packages.create',
            compact('branches', 'allFaqs')
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

            // SEO Validation
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'schema' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $jsonOnly = preg_replace('/<\/?script[^>]*>/i', '', $value);
                    $jsonOnly = trim($jsonOnly);
                    json_decode($jsonOnly);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail('The Schema Markup must be a valid JSON structure.');
                    }
                }
            ],
            'faq_selection' => 'nullable|array',
            'faq_title' => 'nullable|string|max:255',
            'faq_description' => 'nullable|string',
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

        $ogImage = null;

        if ($request->hasFile('og_image')) {
            $path = $request->file('og_image')->store(
                'uploads/birthday-packages/seo',
                'public'
            );
            $ogImage = 'storage/' . $path;
        }

        $schema = $request->schema;
        if (!empty($schema)) {
            $jsonOnly = preg_replace('/<\/?script[^>]*>/i', '', $schema);
            $jsonOnly = trim($jsonOnly);
            $decoded = json_decode($jsonOnly, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $schema = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }

        $faqSelection = $request->faq_selection;
        if (is_array($faqSelection)) {
            $faqIds = $faqSelection['faq_ids'] ?? [];
            $questions = $faqSelection['questions'] ?? [];
            
            $filteredFaqIds = [];
            foreach ($faqIds as $id) {
                if (!empty($questions[$id]) && is_array($questions[$id])) {
                    $filteredFaqIds[] = (int) $id;
                }
            }
            
            $faqSelection['faq_ids'] = $filteredFaqIds;
        }

        BirthdayPackage::create([

            'branch_id' => $request->branch_id,

            'title' => $request->title,

            'slug' => Str::slug($request->slug),

            'image' => $image,

            'banner_image' => $bannerImage,

            'price' => $request->price,

            'minimum_kids' => $request->minimum_kids,
            'duration' => $request->duration,
            'weekday_rate' => $request->weekday_rate,
            'weekend_rate' => $request->weekend_rate,

            'highlighted_description' => $request->highlighted_description ?? NULL,

            'description' => $request->description,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status,

            // SEO Fields
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'og_image' => $ogImage,
            'twitter_title' => $request->twitter_title,
            'twitter_description' => $request->twitter_description,
            'schema' => $schema,
            'faq_selection' => $faqSelection,
            'faq_title' => $request->faq_title,
            'faq_description' => $request->faq_description,
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

        $allFaqs = \App\Models\Faq::where('status', 1)->orderBy('sort_order', 'asc')->get();

        return view(
            'birthday-packages.packages.edit',
            compact(
                'birthday_package',
                'branches',
                'allFaqs'
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

            // SEO Validation
            'remove_og_image' => 'nullable|boolean',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'schema' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $jsonOnly = preg_replace('/<\/?script[^>]*>/i', '', $value);
                    $jsonOnly = trim($jsonOnly);
                    json_decode($jsonOnly);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail('The Schema Markup must be a valid JSON structure.');
                    }
                }
            ],
            'faq_selection' => 'nullable|array',
            'faq_title' => 'nullable|string|max:255',
            'faq_description' => 'nullable|string',
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

        $ogImage = $birthday_package->og_image;

        // Remove OG Image
        if ($request->remove_og_image == 1 && $birthday_package->og_image) {
            if (file_exists(public_path($birthday_package->og_image))) {
                unlink(public_path($birthday_package->og_image));
            }
            $ogImage = null;
        }

        // Upload New OG Image
        if ($request->hasFile('og_image')) {
            if ($birthday_package->og_image && file_exists(public_path($birthday_package->og_image))) {
                unlink(public_path($birthday_package->og_image));
            }

            $path = $request->file('og_image')->store(
                'uploads/birthday-packages/seo',
                'public'
            );
            $ogImage = 'storage/' . $path;
        }

        $schema = $request->schema;
        if (!empty($schema)) {
            $jsonOnly = preg_replace('/<\/?script[^>]*>/i', '', $schema);
            $jsonOnly = trim($jsonOnly);
            $decoded = json_decode($jsonOnly, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $schema = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }

        $faqSelection = $request->faq_selection;
        if (is_array($faqSelection)) {
            $faqIds = $faqSelection['faq_ids'] ?? [];
            $questions = $faqSelection['questions'] ?? [];
            
            $filteredFaqIds = [];
            foreach ($faqIds as $id) {
                if (!empty($questions[$id]) && is_array($questions[$id])) {
                    $filteredFaqIds[] = (int) $id;
                }
            }
            
            $faqSelection['faq_ids'] = $filteredFaqIds;
        }

        $birthday_package->update([

            'branch_id' => $request->branch_id,

            'title' => $request->title,

            'slug' => Str::slug($request->slug),

            'image' => $image,

            'banner_image' => $bannerImage,

            'price' => $request->price,

            'highlighted_description' => $request->highlighted_description ?? NULL,

            'description' => $request->description,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status,

            'minimum_kids' => $request->minimum_kids,
            'duration' => $request->duration,
            'weekday_rate' => $request->weekday_rate,
            'weekend_rate' => $request->weekend_rate,

            // SEO Fields
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'og_image' => $ogImage,
            'twitter_title' => $request->twitter_title,
            'twitter_description' => $request->twitter_description,
            'schema' => $schema,
            'faq_selection' => $faqSelection,
            'faq_title' => $request->faq_title,
            'faq_description' => $request->faq_description,
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

        if (
            $birthday_package->og_image &&
            file_exists(
                public_path($birthday_package->og_image)
            )
        ) {
            unlink(
                public_path($birthday_package->og_image)
            );
        }

        $birthday_package->delete();

        return back()->with(
            'success',
            'Deleted successfully'
        );
    }

    public function copy(BirthdayPackage $birthday_package)
    {
        $this->authorizeBirthdayPackagePermission(
            'create_birthday_packages'
        );

        $newPackage = $birthday_package->replicate();

        $originalTitle = $birthday_package->title;
        $title = $originalTitle . ' - Copy';
        $slug = Str::slug($title);

        $counter = 1;
        while (BirthdayPackage::where('slug', $slug)->exists()) {
            $title = $originalTitle . ' - Copy (' . $counter . ')';
            $slug = Str::slug($title);
            $counter++;
        }

        $newPackage->title = $title;
        $newPackage->slug = $slug;

        // Copy files to avoid shared reference when unlinking
        if ($birthday_package->image && file_exists(public_path($birthday_package->image))) {
            $originalPath = public_path($birthday_package->image);
            $pathInfo = pathinfo($originalPath);
            $newFilename = $pathInfo['filename'] . '_copy_' . time() . '.' . ($pathInfo['extension'] ?? 'jpg');
            $newPath = $pathInfo['dirname'] . '/' . $newFilename;
            if (copy($originalPath, $newPath)) {
                $newPackage->image = str_replace($pathInfo['basename'], $newFilename, $birthday_package->image);
            }
        }

        if ($birthday_package->banner_image && file_exists(public_path($birthday_package->banner_image))) {
            $originalPath = public_path($birthday_package->banner_image);
            $pathInfo = pathinfo($originalPath);
            $newFilename = $pathInfo['filename'] . '_copy_' . time() . '.' . ($pathInfo['extension'] ?? 'jpg');
            $newPath = $pathInfo['dirname'] . '/' . $newFilename;
            if (copy($originalPath, $newPath)) {
                $newPackage->banner_image = str_replace($pathInfo['basename'], $newFilename, $birthday_package->banner_image);
            }
        }

        if ($birthday_package->og_image && file_exists(public_path($birthday_package->og_image))) {
            $originalPath = public_path($birthday_package->og_image);
            $pathInfo = pathinfo($originalPath);
            $newFilename = $pathInfo['filename'] . '_copy_' . time() . '.' . ($pathInfo['extension'] ?? 'jpg');
            $newPath = $pathInfo['dirname'] . '/' . $newFilename;
            if (copy($originalPath, $newPath)) {
                $newPackage->og_image = str_replace($pathInfo['basename'], $newFilename, $birthday_package->og_image);
            }
        }

        $newPackage->save();

        return redirect()
            ->route('birthday-packages.index')
            ->with(
                'success',
                'Birthday package copied successfully'
            );
    }
}