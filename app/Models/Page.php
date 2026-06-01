<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Get a formatted page and its dynamic assets by slug.
     */
    public static function getPageContent(string $slug)
    {
        $page = self::where('slug', $slug)->first();
        if (!$page) {
            return null;
        }

        // Recursively fix relative storage URLs in JSON content
        $content = $page->content ?? [];
        self::fixImagePaths($content);
        return $content;
    }

    /**
     * Recursively find relative storage file paths and prepend the absolute app URL.
     */
    private static function fixImagePaths(&$content)
    {
        if (is_array($content)) {
            foreach ($content as $key => &$value) {
                if (is_array($value)) {
                    self::fixImagePaths($value);
                } elseif (is_string($value) && !empty($value)) {
                    if (in_array($key, ['image', 'banner_image', 'bottom_image', 'og_image', 'icon', 'file']) || str_starts_with($value, 'pages/')) {
                        if (!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
                            if (str_starts_with($value, 'pages/')) {
                                $value = asset('storage/' . $value);
                            } else {
                                $value = asset($value);
                            }
                        }
                    }
                }
            }
        }
    }
}
