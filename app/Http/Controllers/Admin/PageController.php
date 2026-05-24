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
        $pages = [];

        foreach ($configPages as $slug => $schema) {
            $pages[] = Page::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $schema['title'],
                    'content' => [],
                ]
            );
        }

        return view('pages.index', compact('pages'));
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        $this->authorizePagePermission('edit_pages');

        $schema = config("pages.{$page->slug}");

        if (!$schema) {
            abort(404, 'Page schema configuration not found.');
        }

        return view('pages.edit', compact('page', 'schema'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page)
    {
        $this->authorizePagePermission('edit_pages');

        $schema = config("pages.{$page->slug}");

        if (!$schema) {
            abort(404, 'Page schema configuration not found.');
        }

        // Build validation rules dynamically
        $rules = [];
        $messages = [];

        foreach ($schema['sections'] as $section) {
            foreach ($section['fields'] as $field) {
                $fieldName = $field['name'];

                if ($field['type'] === 'repeater') {
                    $rules[$fieldName] = ['nullable', 'array'];
                    
                    foreach ($field['fields'] as $subField) {
                        $subName = $subField['name'];
                        $subRules = $subField['rules'] ?? [];

                        if ($subField['type'] === 'image') {
                            // If it's an image, a new upload is validated, otherwise it can be empty (meaning keep existing)
                            $rules["{$fieldName}.*.{$subName}"] = ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'];
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
                    $rules["{$fieldName}.*.file"] = ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'];
                } else {
                    $fieldRules = $field['rules'] ?? [];
                    if ($field['type'] === 'image') {
                        $rules[$fieldName] = ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'];
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
}
