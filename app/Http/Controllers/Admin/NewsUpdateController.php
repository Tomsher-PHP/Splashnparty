<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsUpdateController extends Controller
{
    public function index()
    {
        $newsUpdates = NewsUpdate::latest()->paginate(20);

        return view('news-updates.index', compact('newsUpdates'));
    }

    public function create()
    {
        return view('news-updates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image',
            'og_image' => 'nullable|image',
            'publish_date' => 'nullable|date',
        ]);

        $data = $request->except([
            'image',
            'og_image',
        ]);

        $data['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store(
                'uploads/news-updates',
                'public'
            );
            $data['image'] = 'storage/' . $path;
        }

        if ($request->hasFile('og_image')) {
            $path = $request
                ->file('og_image')
                ->store(
                    'uploads/news-updates/seo',
                    'public'
                );

            $data['og_image'] = 'storage/' . $path;
        }

        NewsUpdate::create($data);

        return redirect()
            ->route('news-updates.index')
            ->with('success', 'News created successfully.');
    }

    public function edit(NewsUpdate $newsUpdate)
    {
        return view(
            'news-updates.edit',
            compact('newsUpdate')
        );
    }

    public function update(
        Request $request,
        NewsUpdate $newsUpdate
    ) {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image',
            'og_image' => 'nullable|image',
            'publish_date' => 'nullable|date',
        ]);

        $data = $request->except([
            'image',
            'og_image',
        ]);

        $data['slug'] = Str::slug($request->title) . '-' . time();


        // IMAGE
        $image = $newsUpdate->image;

        // REMOVE IMAGE
        if ($request->remove_image == 1 && $newsUpdate->image) {

            $oldPath = public_path($newsUpdate->image);

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $data['image'] = null;
        }

        // NEW IMAGE
        if ($request->hasFile('image')) {
            if ($newsUpdate->image && file_exists(public_path($newsUpdate->image))) {
                unlink(public_path($newsUpdate->image));
            }

            $path = $request->file('image')->store('uploads/news-updates', 'public');

            $data['image'] = 'storage/' . $path;
        }


        // OG Image
        if ($request->hasFile('og_image')) {

            if (
                $newsUpdate->og_image &&
                file_exists(public_path($newsUpdate->og_image))
            ) {
                unlink(public_path($newsUpdate->og_image));
            }

            $path = $request->file('og_image')->store(
                'uploads/news-updates/seo',
                'public'
            );

            $data['og_image'] = 'storage/' . $path;
        }

        $newsUpdate->update($data);

        return redirect()
            ->route('news-updates.index')
            ->with('success', 'News updated successfully.');
    }

    public function destroy(NewsUpdate $newsUpdate)
    {
       if (
            $newsUpdate->image &&
            file_exists(public_path($newsUpdate->image))
        ) {
            unlink(public_path($newsUpdate->image));
        }

        if (
            $newsUpdate->og_image &&
            file_exists(public_path($newsUpdate->og_image))
        ) {
            unlink(public_path($newsUpdate->og_image));
        }

        $newsUpdate->delete();

        return redirect()
            ->route('news-updates.index')
            ->with('success', 'News deleted successfully.');
    }
}