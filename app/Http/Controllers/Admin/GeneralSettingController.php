<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralSettingController extends Controller
{
    public function edit()
    {
        $this->authorizeGeneralSettingPermission('view_general_settings');

        return view('settings.general', [
            'settingGroups' => $this->settingGroups(),
            'settingValues' => SiteSetting::pluck('value', 'key')->all(),
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeGeneralSettingPermission('edit_general_settings');

        $validated = $request->validate($this->rules());
        $settingValues = SiteSetting::pluck('value', 'key')->all();

        foreach ($this->fields() as $field) {
            $key = $field['key'];

            if (($field['type'] ?? 'text') === 'file') {
                if (! $request->hasFile($key)) {
                    continue;
                }

                if (! empty($settingValues[$key])) {
                    Storage::disk('public')->delete($settingValues[$key]);
                }

                $value = $request->file($key)->store('settings', 'public');
            } else {
                $value = $validated[$key] ?? null;
            }

            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $field['group'],
                    'value' => $value,
                    'type' => $field['type'] ?? 'text',
                    'sort_order' => $field['sort_order'] ?? 0,
                ]
            );
        }

        return redirect()->route('general-settings.edit')->with('success', 'General settings updated');
    }

    private function rules(): array
    {
        $rules = [];

        foreach ($this->fields() as $field) {
            $rules[$field['key']] = $field['rules'];
        }

        return $rules;
    }

    private function settingGroups(): array
    {
        $groups = [];

        foreach ($this->fields() as $field) {
            $groups[$field['group']]['title'] = $field['group_title'];
            $groups[$field['group']]['fields'][] = $field;
        }

        return $groups;
    }

    private function fields(): array
    {
        return [
            [
                'group' => 'identity',
                'group_title' => 'Website Identity',
                'key' => 'site_name',
                'label' => 'Website Name',
                'type' => 'text',
                'placeholder' => 'Enter website name',
                'rules' => ['nullable', 'string', 'max:255'],
                'sort_order' => 10,
            ],
            [
                'group' => 'identity',
                'group_title' => 'Website Identity',
                'key' => 'footer_text',
                'label' => 'Footer Text',
                'type' => 'text',
                'placeholder' => 'Enter footer text',
                'rules' => ['nullable', 'string', 'max:255'],
                'sort_order' => 20,
            ],
            [
                'group' => 'identity',
                'group_title' => 'Website Identity',
                'key' => 'logo',
                'label' => 'Website Logo',
                'type' => 'file',
                'accept' => 'image/png,image/jpeg,image/webp,image/svg+xml',
                'rules' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
                'sort_order' => 30,
            ],
            [
                'group' => 'identity',
                'group_title' => 'Website Identity',
                'key' => 'favicon',
                'label' => 'Favicon',
                'type' => 'file',
                'accept' => 'image/x-icon,image/png,image/jpeg,image/webp,image/svg+xml',
                'rules' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg,webp,svg', 'max:1024'],
                'sort_order' => 40,
            ],
            [
                'group' => 'contact',
                'group_title' => 'Contact Details',
                'key' => 'email',
                'label' => 'Email',
                'type' => 'email',
                'placeholder' => 'Enter email',
                'rules' => ['nullable', 'email', 'max:255'],
                'sort_order' => 50,
            ],
            [
                'group' => 'contact',
                'group_title' => 'Contact Details',
                'key' => 'phone',
                'label' => 'Phone',
                'type' => 'text',
                'placeholder' => 'Enter phone',
                'rules' => ['nullable', 'string', 'max:50'],
                'sort_order' => 60,
            ],
            [
                'group' => 'contact',
                'group_title' => 'Contact Details',
                'key' => 'whatsapp',
                'label' => 'Whatsapp',
                'type' => 'text',
                'placeholder' => 'Enter whatsapp number',
                'rules' => ['nullable', 'string', 'max:50'],
                'sort_order' => 70,
            ],
            [
                'group' => 'contact',
                'group_title' => 'Contact Details',
                'key' => 'working_hours',
                'label' => 'Working Hours',
                'type' => 'text',
                'placeholder' => 'Mon - Sat, 9:00 AM - 6:00 PM',
                'rules' => ['nullable', 'string', 'max:255'],
                'sort_order' => 80,
            ],
            [
                'group' => 'contact',
                'group_title' => 'Contact Details',
                'key' => 'address',
                'label' => 'Address',
                'type' => 'textarea',
                'placeholder' => 'Enter address',
                'rules' => ['nullable', 'string', 'max:1000'],
                'sort_order' => 90,
            ],
            [
                'group' => 'vat',
                'group_title' => 'Vat Percentage',
                'key' => 'vat_percentage',
                'label' => 'Vat Percentage',
                'type' => 'number',
                'placeholder' => 'Enter vat percentage',
                'rules' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'sort_order' => 95,
            ],
            [
                'group' => 'social',
                'group_title' => 'Social Links',
                'key' => 'facebook_url',
                'label' => 'Facebook URL',
                'type' => 'url',
                'placeholder' => 'https://',
                'rules' => ['nullable', 'url', 'max:255'],
                'sort_order' => 100,
            ],
            [
                'group' => 'social',
                'group_title' => 'Social Links',
                'key' => 'instagram_url',
                'label' => 'Instagram URL',
                'type' => 'url',
                'placeholder' => 'https://',
                'rules' => ['nullable', 'url', 'max:255'],
                'sort_order' => 110,
            ],
            [
                'group' => 'social',
                'group_title' => 'Social Links',
                'key' => 'twitter_url',
                'label' => 'Twitter / X URL',
                'type' => 'url',
                'placeholder' => 'https://',
                'rules' => ['nullable', 'url', 'max:255'],
                'sort_order' => 120,
            ],
            [
                'group' => 'social',
                'group_title' => 'Social Links',
                'key' => 'linkedin_url',
                'label' => 'LinkedIn URL',
                'type' => 'url',
                'placeholder' => 'https://',
                'rules' => ['nullable', 'url', 'max:255'],
                'sort_order' => 130,
            ],
            [
                'group' => 'social',
                'group_title' => 'Social Links',
                'key' => 'youtube_url',
                'label' => 'YouTube URL',
                'type' => 'url',
                'placeholder' => 'https://',
                'rules' => ['nullable', 'url', 'max:255'],
                'sort_order' => 140,
            ],
            [
                'group' => 'seo',
                'group_title' => 'SEO & Map',
                'key' => 'meta_title',
                'label' => 'Meta Title',
                'type' => 'text',
                'placeholder' => 'Enter meta title',
                'rules' => ['nullable', 'string', 'max:255'],
                'sort_order' => 150,
            ],
            [
                'group' => 'seo',
                'group_title' => 'SEO & Map',
                'key' => 'meta_description',
                'label' => 'Meta Description',
                'type' => 'textarea',
                'placeholder' => 'Enter meta description',
                'rules' => ['nullable', 'string', 'max:1000'],
                'sort_order' => 160,
            ],
            [
                'group' => 'seo',
                'group_title' => 'SEO & Map',
                'key' => 'map_embed_url',
                'label' => 'Map Embed / Link',
                'type' => 'textarea',
                'placeholder' => 'Paste Google Map embed code or map link',
                'rules' => ['nullable', 'string', 'max:5000'],
                'sort_order' => 170,
            ],
        ];
    }

    private function authorizeGeneralSettingPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }
}
