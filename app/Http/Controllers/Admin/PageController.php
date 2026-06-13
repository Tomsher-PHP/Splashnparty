<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the pages.
     */
    public function index()
    {
        $this->authorizePagePermission('view_pages');

        $configPages = config('pages', []);

        foreach ($configPages as $slug => $schema) {
            Page::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $schema['title'],
                    'content' => [],
                ]
            );
        }

        $pages = Page::whereIn('slug', array_keys($configPages))->orderBy('title', 'asc')->get();

        return view('pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        $this->authorizePagePermission('edit_pages');

        $schema = config("pages.{$page->slug}");

        if (!$schema) {
            abort(404, 'Page schema configuration not found.');
        }

        // Dynamically append the common SEO section to all pages except footer settings and news-updates-details
        if ($page->slug !== 'footer' && $page->slug !== 'news-updates-details') {
            $schema['sections'][] = $this->getSeoSectionSchema();
        }

        // Dynamically populate options if needed
        foreach ($schema['sections'] as &$section) {
            foreach ($section['fields'] as &$field) {
                if (isset($field['options_source'])) {
                    if ($field['options_source'] === 'banners') {
                        $field['options'] = \App\Models\Banner::where('status', true)->get()->map(function ($banner) {
                            return [
                                'value' => $banner->id,
                                'label' => $banner->title ?: 'Banner #' . $banner->id,
                                'image' => $banner->file ? asset('storage/' . $banner->file) : null,
                                'type' => $banner->banner_type
                            ];
                        })->toArray();
                    }
                }
            }
        }

        return view('pages.edit', compact('page', 'schema'));
    }

    public function update(Request $request, Page $page)
    {
        $this->authorizePagePermission('edit_pages');

        $schema = config("pages.{$page->slug}");

        if (!$schema) {
            abort(404, 'Page schema configuration not found.');
        }

        // Dynamically append the common SEO section to all pages except footer settings and news-updates-details
        if ($page->slug !== 'footer' && $page->slug !== 'news-updates-details') {
            $schema['sections'][] = $this->getSeoSectionSchema();
        }

        // Build validation rules dynamically
        $rules = [];
        $messages = [];

        foreach ($schema['sections'] as $section) {
            foreach ($section['fields'] as $field) {
                $fieldName = $field['name'];

                if ($field['type'] === 'repeater') {
                    $rules[$fieldName] = $field['rules'] ?? ['nullable', 'array'];
                    
                    foreach ($field['fields'] as $subField) {
                        $subName = $subField['name'];
                        $subRules = $subField['rules'] ?? [];

                        if ($subField['type'] === 'image') {
                            // If it's an image, a new upload is validated, otherwise it can be empty (meaning keep existing)
                            $rules["{$fieldName}.*.{$subName}"] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:500'];
                        } elseif ($subField['type'] === 'repeater') {
                            $rules["{$fieldName}.*.{$subName}"] = $subField['rules'] ?? ['nullable', 'array'];
                            foreach ($subField['fields'] as $nestedSubField) {
                                $nestedSubName = $nestedSubField['name'];
                                $nestedSubRules = $nestedSubField['rules'] ?? [];
                                if ($nestedSubField['type'] === 'image') {
                                    $rules["{$fieldName}.*.{$subName}.*.{$nestedSubName}"] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:500'];
                                } else {
                                    $rules["{$fieldName}.*.{$subName}.*.{$nestedSubName}"] = $nestedSubRules;
                                }
                                $messages["{$fieldName}.*.{$subName}.*.{$nestedSubName}.required"] = "The :attribute field is required inside nested " . ($subField['label'] ?? 'items') . ".";
                            }
                        } else {
                            $rules["{$fieldName}.*.{$subName}"] = $subRules;
                        }

                        // Custom error messages for readable repeater validation errors
                        $messages["{$fieldName}.*.{$subName}.required"] = "The :attribute field is required inside " . ($field['label'] ?? 'items') . ".";
                    }
                } elseif ($field['type'] === 'gallery') {
                    $rules[$fieldName] = ['nullable', 'array'];
                    $rules["{$fieldName}.*.type"] = ['required', 'string', 'in:existing,upload'];
                    $rules["{$fieldName}.*.value"] = ['nullable', 'string'];
                    $rules["{$fieldName}.*.file"] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:500'];
                } else {
                    $fieldRules = $field['rules'] ?? [];
                    if ($field['type'] === 'image') {
                        $rules[$fieldName] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:500'];
                    } else {
                        $rules[$fieldName] = $fieldRules;
                    }
                }
            }
        }

        $validated = $request->validate($rules, $messages);

        // Process fields and prepare JSON content
        $content = $page->content ?? [];

        foreach ($schema['sections'] as $section) {
            foreach ($section['fields'] as $field) {
                $fieldName = $field['name'];

                if ($field['type'] === 'image') {
                    if ($request->hasFile($fieldName)) {
                        // Delete old file if exists
                        if (!empty($content[$fieldName])) {
                            Storage::disk('public')->delete($content[$fieldName]);
                        }
                        $content[$fieldName] = $request->file($fieldName)->store("pages/{$page->slug}", 'public');
                    }
                    // If no file uploaded, retain the existing one (already in $content[$fieldName])
                } elseif ($field['type'] === 'repeater') {
                    $repeaterInput = $request->input($fieldName, []);
                    $savedRows = [];

                    foreach ($repeaterInput as $index => $rowInput) {
                        $savedRow = [];
                        foreach ($field['fields'] as $subField) {
                            $subName = $subField['name'];

                            if ($subField['type'] === 'image') {
                                $fileKey = "{$fieldName}.{$index}.{$subName}";

                                if ($request->hasFile($fileKey)) {
                                    // Upload new image
                                    $savedRow[$subName] = $request->file($fileKey)->store("pages/{$page->slug}", 'public');
                                    
                                    // If there was an old image, we could delete it, but in repeaters, it's safer to keep or cleanup when unused.
                                    $oldImagePath = $request->input("{$fieldName}.{$index}.{$subName}_existing");
                                    if ($oldImagePath && $oldImagePath !== $savedRow[$subName]) {
                                        Storage::disk('public')->delete($oldImagePath);
                                    }
                                } else {
                                    // Retain existing image
                                    $savedRow[$subName] = $request->input("{$fieldName}.{$index}.{$subName}_existing") ?: null;
                                }
                            } elseif ($subField['type'] === 'repeater') {
                                $nestedRepeaterInput = $rowInput[$subName] ?? [];
                                $nestedSavedRows = [];
                                foreach ($nestedRepeaterInput as $nestedIndex => $nestedRowInput) {
                                    $nestedSavedRow = [];
                                    foreach ($subField['fields'] as $nestedSubField) {
                                        $nestedSubName = $nestedSubField['name'];
                                        if ($nestedSubField['type'] === 'image') {
                                            $nestedFileKey = "{$fieldName}.{$index}.{$subName}.{$nestedIndex}.{$nestedSubName}";
                                            if ($request->hasFile($nestedFileKey)) {
                                                $nestedSavedRow[$nestedSubName] = $request->file($nestedFileKey)->store("pages/{$page->slug}", 'public');
                                                $nestedOldImagePath = $request->input("{$fieldName}.{$index}.{$subName}.{$nestedIndex}.{$nestedSubName}_existing");
                                                if ($nestedOldImagePath && $nestedOldImagePath !== $nestedSavedRow[$nestedSubName]) {
                                                    Storage::disk('public')->delete($nestedOldImagePath);
                                                }
                                            } else {
                                                $nestedSavedRow[$nestedSubName] = $request->input("{$fieldName}.{$index}.{$subName}.{$nestedIndex}.{$nestedSubName}_existing") ?: null;
                                            }
                                        } else {
                                            $nestedSavedRow[$nestedSubName] = $nestedRowInput[$nestedSubName] ?? null;
                                        }
                                    }
                                    $nestedSavedRows[] = $nestedSavedRow;
                                }
                                $savedRow[$subName] = $nestedSavedRows;
                            } else {
                                $savedRow[$subName] = $rowInput[$subName] ?? null;
                            }
                        }
                        $savedRows[] = $savedRow;
                    }

                    $content[$fieldName] = $savedRows;
                } elseif ($field['type'] === 'gallery') {
                    $galleryInput = $request->input($fieldName, []);
                    $savedPaths = [];

                    foreach ($galleryInput as $index => $item) {
                        if ($item['type'] === 'existing') {
                            $savedPaths[] = $item['value'];
                        } else {
                            $fileKey = "{$fieldName}.{$index}.file";
                            if ($request->hasFile($fileKey)) {
                                $savedPaths[] = $request->file($fileKey)->store("pages/{$page->slug}", 'public');
                            }
                        }
                    }

                    $content[$fieldName] = $savedPaths;
                } else {
                    $content[$fieldName] = $request->input($fieldName);
                }
            }
        }

        // Save updated content
        $page->update([
            'content' => $content
        ]);

        return redirect()->route('pages.edit', $page->id)->with('success', "{$page->title} content updated successfully.");
    }

    /**
     * Helper to authorize permissions.
     */
    private function authorizePagePermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    /**
     * Get the common SEO & Social Sharing metadata section schema.
     */
    private function getSeoSectionSchema(): array
    {
        return [
            'title' => 'SEO Details',
            'description' => 'Manage page search engine optimization, keywords, and appearance on social sharing platforms (Facebook, WhatsApp, Twitter, etc.)',
            'fields' => [
                [
                    'name' => 'meta_title',
                    'label' => 'Meta Title',
                    'type' => 'text',
                    'placeholder' => 'Enter SEO meta title',
                    'rules' => ['nullable', 'string', 'max:255'],
                ],
                [
                    'name' => 'meta_description',
                    'label' => 'Meta Description',
                    'type' => 'textarea',
                    'placeholder' => 'Enter SEO meta description',
                    'rules' => ['nullable', 'string'],
                ],
                [
                    'name' => 'keywords',
                    'label' => 'Meta Keywords',
                    'type' => 'text',
                    'placeholder' => 'Enter SEO keywords (comma separated, e.g. birthday, cake, waterpark)',
                    'rules' => ['nullable', 'string', 'max:255'],
                ],
                [
                    'name' => 'twitter_title',
                    'label' => 'Twitter Title',
                    'type' => 'text',
                    'placeholder' => 'Enter custom Twitter card title',
                    'rules' => ['nullable', 'string', 'max:255'],
                ],
                [
                    'name' => 'twitter_description',
                    'label' => 'Twitter Description',
                    'type' => 'textarea',
                    'placeholder' => 'Enter custom Twitter card description',
                    'rules' => ['nullable', 'string'],
                ],
                [
                    'name' => 'og_title',
                    'label' => 'OG Title',
                    'type' => 'text',
                    'placeholder' => 'Enter custom social sharing title',
                    'rules' => ['nullable', 'string', 'max:255'],
                ],
                [
                    'name' => 'og_description',
                    'label' => 'OG Description',
                    'type' => 'textarea',
                    'placeholder' => 'Enter custom social sharing description',
                    'rules' => ['nullable', 'string'],
                ],
                [
                    'name' => 'og_image',
                    'label' => 'OG Image',
                    'type' => 'image',
                    'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                ]
            ]
        ];
    }
}
