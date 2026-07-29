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
                if ($key === 'schema' && !empty($value)) {
                    $jsonOnly = preg_replace('/<\/?script[^>]*>/i', '', $value);
                    $jsonOnly = trim($jsonOnly);
                    $decoded = json_decode($jsonOnly, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $value = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    }
                }
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
                'group' => 'enquiry_email_settings',
                'group_title' => 'Enquiry Email Settings',
                'key' => 'enquiry_email',
                'label' => 'To Email (<small>Enter email where website contact enquiries should be forwarded</small>)',
                'type' => 'email',
                'placeholder' => 'Enter email where website contact enquiries should be forwarded',
                'rules' => ['nullable', 'email', 'max:255'],
                'sort_order' => 95,
            ],
            [
                'group' => 'enquiry_email_settings',
                'group_title' => 'Enquiry Email Settings',
                'key' => 'enquiry_cc_emails',
                'label' => 'CC Emails (<small>Enter multiple CC emails separated by commas.</small>)',
                'type' => 'text',
                'placeholder' => 'cc1@example.com, cc2@example.com',
                'rules' => [
                    'nullable',
                    'string',
                    'max:1000',
                    function ($attribute, $value, $fail) {
                        if (empty(trim($value))) return;
                        $emails = array_map('trim', explode(',', $value));
                        foreach ($emails as $email) {
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $fail("The $attribute must be a comma-separated list of valid email addresses. '$email' is invalid.");
                            }
                        }
                    }
                ],
                'sort_order' => 96,
            ],
            [
                'group' => 'notification_email_settings',
                'group_title' => 'Booking Notification Email Settings',
                'key' => 'notification_email',
                'label' => 'To Email (<small>Enter email where notifications should be sent (e.g. new bookings)</small>)',
                'type' => 'email',
                'placeholder' => 'Enter email where notifications should be sent (e.g. new bookings)',
                'rules' => ['nullable', 'email', 'max:255'],
                'sort_order' => 97,
            ],
            [
                'group' => 'notification_email_settings',
                'group_title' => 'Booking Notification Email Settings',
                'key' => 'notification_cc_emails',
                'label' => 'CC Emails (<small>Enter multiple CC emails separated by commas.</small>)',
                'type' => 'text',
                'placeholder' => 'cc1@example.com, cc2@example.com',
                'rules' => [
                    'nullable',
                    'string',
                    'max:1000',
                    function ($attribute, $value, $fail) {
                        if (empty(trim($value))) return;
                        $emails = array_map('trim', explode(',', $value));
                        foreach ($emails as $email) {
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $fail("The $attribute must be a comma-separated list of valid email addresses. '$email' is invalid.");
                            }
                        }
                    }
                ],
                'sort_order' => 98,
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
                'group' => 'vat',
                'group_title' => 'Vat Percentage',
                'key' => 'trn',
                'label' => 'Tax Registration Number (TRN)',
                'type' => 'text',
                'placeholder' => 'Enter TRN (e.g. 100xxxxxxxxxxxx)',
                'rules' => ['nullable', 'string', 'max:50'],
                'sort_order' => 96,
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
            [
                'group' => 'seo',
                'group_title' => 'SEO & Map',
                'key' => 'schema',
                'label' => 'Schema Markup',
                'type' => 'textarea',
                'rows' => 10,
                'placeholder' => 'Enter schema markup (e.g. JSON format)',
                'rules' => [
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
                'sort_order' => 180,
            ],
            [
                'group' => 'popup',
                'group_title' => 'Homepage Popup Settings',
                'key' => 'popup_status',
                'label' => 'Popup Status',
                'type' => 'select',
                'options' => [
                    '0' => 'Disabled',
                    '1' => 'Enabled',
                ],
                'rules' => ['nullable', 'in:0,1'],
                'sort_order' => 200,
            ],
            [
                'group' => 'popup',
                'group_title' => 'Homepage Popup Settings',
                'key' => 'popup_image',
                'label' => 'Popup Image',
                'type' => 'file',
                'accept' => 'image/png,image/jpeg,image/webp,image/svg+xml',
                'rules' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
                'sort_order' => 210,
            ],
            [
                'group' => 'popup',
                'group_title' => 'Homepage Popup Settings',
                'key' => 'popup_button_text',
                'label' => 'Popup Button Text',
                'type' => 'text',
                'placeholder' => 'Enter button text (e.g. Book Now)',
                'rules' => ['nullable', 'string', 'max:255'],
                'sort_order' => 220,
            ],
            [
                'group' => 'popup',
                'group_title' => 'Homepage Popup Settings',
                'key' => 'popup_button_link',
                'label' => 'Popup Button Link',
                'type' => 'text',
                'placeholder' => 'Enter button redirect URL / link',
                'rules' => ['nullable', 'string', 'max:2000'],
                'sort_order' => 230,
            ],
            [
                'group' => 'rental_settings',
                'group_title' => 'Rental Settings',
                'key' => 'rental_items_pdf',
                'label' => 'Rental Items PDF File',
                'type' => 'file',
                'accept' => 'application/pdf',
                'rules' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
                'sort_order' => 250,
            ],
        ];
    }

    private function authorizeGeneralSettingPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }
}
