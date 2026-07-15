<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    private function authorizeBannerPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeBannerPermission('view_banners');

        $query = Banner::latest();

        if ($search = request('search')) {
            $query->where(function ($bannerQuery) use ($search) {
                $bannerQuery->where('title', 'like', '%'.$search.'%')
                    ->orWhere('subtitle', 'like', '%'.$search.'%')
                    ->orWhere('btn_text', 'like', '%'.$search.'%')
                    ->orWhere('btn_link', 'like', '%'.$search.'%');
            });
        }

        if ($type = request('banner_type')) {
            if (in_array($type, ['image', 'video'], true)) {
                $query->where('banner_type', $type);
            }
        }

        if ($status = request('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status === 'active');
            }
        }

        $banners = $query->paginate(10)->withQueryString();

        return view('banners.index', compact('banners'));
    }

    public function create()
    {
        $this->authorizeBannerPermission('create_banners');

        return view('banners.create');
    }

    public function store(Request $request)
    {
        $this->authorizeBannerPermission('create_banners');

        $validated = $request->validate($this->rules($request, true), $this->messages());
        $validated['file'] = $request->file('file')->store('banners', 'public');

        Banner::create($validated);

        return redirect()->route('banners.index')->with('success', 'Banner created');
    }

    public function edit(Banner $banner)
    {
        $this->authorizeBannerPermission('edit_banners');

        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $this->authorizeBannerPermission('edit_banners');

        $validated = $request->validate(
            $this->rules($request, $request->input('banner_type') !== $banner->banner_type),
            $this->messages()
        );

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($banner->file);
            $validated['file'] = $request->file('file')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()->route('banners.index')->with('success', 'Banner updated');
    }

    public function updateStatus(Request $request, Banner $banner)
    {
        $this->authorizeBannerPermission('edit_banners');

        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $banner->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('banners.index', [
            'search' => $request->input('search'),
            'banner_type' => $request->input('banner_type'),
            'status' => $request->input('status_filter'),
            'page' => $request->input('page'),
        ])
            ->with('success', 'Banner status updated');
    }

    public function destroy(Banner $banner)
    {
        $this->authorizeBannerPermission('delete_banners');

        Storage::disk('public')->delete($banner->file);
        $banner->delete();

        return back()->with('success', 'Banner deleted');
    }

    private function rules(Request $request, bool $fileRequired): array
    {
        $bannerType = $request->input('banner_type');
        $fileRules = [$fileRequired ? 'required' : 'nullable', 'file'];

        if ($bannerType === 'video') {
            $fileRules[] = 'mimes:mp4,webm,mov,ogg';
            $fileRules[] = 'max:5120';
        } else {
            $fileRules[] = 'mimes:jpg,jpeg,png,webp';
            $fileRules[] = 'max:1024';
        }

        return [
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:1000'],
            'btn_text' => ['nullable', 'string', 'max:100'],
            'btn_link' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'boolean'],
            'banner_type' => ['required', Rule::in(['image', 'video'])],
            'file' => $fileRules,
        ];
    }

    private function messages(): array
    {
        return [
            'file.uploaded' => 'The selected file could not be uploaded. Please choose an image up to 500kb or a video up to 5 MB.',
            'file.max' => 'Images must be 500kb or smaller. Videos must be 5 MB or smaller.',
            'file.mimes' => 'Please upload a valid banner file type.',
        ];
    }
}
