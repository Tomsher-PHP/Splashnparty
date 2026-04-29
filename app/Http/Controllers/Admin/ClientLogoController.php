<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientLogoController extends Controller
{
    private function authorizeClientLogoPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    public function index()
    {
        $this->authorizeClientLogoPermission('view_client_logos');

        $query = ClientLogo::orderBy('sort_order')->latest();

        if ($search = request('search')) {
            $query->where(function ($clientLogoQuery) use ($search) {
                $clientLogoQuery->where('title', 'like', '%'.$search.'%')
                    ->orWhere('link', 'like', '%'.$search.'%');
            });
        }

        if ($status = request('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status === 'active');
            }
        }

        $clientLogos = $query->paginate(10)->withQueryString();

        return view('client-logos.index', compact('clientLogos'));
    }

    public function create()
    {
        $this->authorizeClientLogoPermission('create_client_logos');

        return view('client-logos.create');
    }

    public function store(Request $request)
    {
        $this->authorizeClientLogoPermission('create_client_logos');

        $validated = $request->validate($this->rules(true), $this->messages());
        $validated['logo'] = $request->file('logo')->store('client-logos', 'public');

        ClientLogo::create($validated);

        return redirect()->route('client-logos.index')->with('success', 'Client logo created');
    }

    public function edit(ClientLogo $clientLogo)
    {
        $this->authorizeClientLogoPermission('edit_client_logos');

        return view('client-logos.edit', compact('clientLogo'));
    }

    public function update(Request $request, ClientLogo $clientLogo)
    {
        $this->authorizeClientLogoPermission('edit_client_logos');

        $validated = $request->validate($this->rules(false), $this->messages());

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($clientLogo->logo);
            $validated['logo'] = $request->file('logo')->store('client-logos', 'public');
        }

        $clientLogo->update($validated);

        return redirect()->route('client-logos.index')->with('success', 'Client logo updated');
    }

    public function updateStatus(Request $request, ClientLogo $clientLogo)
    {
        $this->authorizeClientLogoPermission('edit_client_logos');

        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $clientLogo->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('client-logos.index', [
            'search' => $request->input('search'),
            'status' => $request->input('status_filter'),
            'page' => $request->input('page'),
        ])->with('success', 'Client logo status updated');
    }

    public function destroy(ClientLogo $clientLogo)
    {
        $this->authorizeClientLogoPermission('delete_client_logos');

        Storage::disk('public')->delete($clientLogo->logo);
        $clientLogo->delete();

        return back()->with('success', 'Client logo deleted');
    }

    private function rules(bool $logoRequired): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'logo' => [$logoRequired ? 'required' : 'nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
        ];
    }

    private function messages(): array
    {
        return [
            'logo.uploaded' => 'The selected logo could not be uploaded. Please choose an image up to 4 MB.',
            'logo.max' => 'Logo images must be 4 MB or smaller.',
            'logo.mimes' => 'Please upload a JPG, PNG, WEBP, or SVG logo.',
        ];
    }
}
