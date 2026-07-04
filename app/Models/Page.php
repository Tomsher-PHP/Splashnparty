<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;

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

        // Dynamically fetch and append selected FAQs
        $content['selected_faqs'] = self::getPageFaqs($content['faq_selection'] ?? []);
        unset($content['faq_selection']); 

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
                    if (in_array($key, ['image', 'banner_image', 'bottom_image', 'og_image', 'icon', 'file', 'center_image']) || str_starts_with($value, 'pages/')) {
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

    /**
     * Resolve selected FAQs (categories and selected questions) from the database.
     */
    public static function getPageFaqs($faqSelection)
    {
        if (empty($faqSelection) || empty($faqSelection['faq_ids'])) {
            return [];
        }

        $categoryIds = $faqSelection['faq_ids'];
        $selectedQuestions = $faqSelection['questions'] ?? [];

        $faqs = Faq::whereIn('id', $categoryIds)
            ->where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $formattedFaqs = [];

        foreach ($faqs as $faq) {
            $catId = $faq->id;
            $allowedQuestions = $selectedQuestions[$catId] ?? [];
            if (empty($allowedQuestions)) {
                continue;
            }

            // Filter questions in the category details
            $details = collect($faq->details)
                ->filter(function ($item) use ($allowedQuestions) {
                    return ($item['status'] ?? 1) == 1 && in_array($item['question'], $allowedQuestions);
                })
                ->sortBy(fn($item) => (int) ($item['sort_order'] ?? 0))
                ->map(fn($item) => [
                    'question' => $item['question'] ?? '',
                    'answer' => $item['answer'] ?? ''
                ])
                ->values()
                ->all();

            if (!empty($details)) {
                $formattedFaqs[] = [
                    'category' => $faq->category,
                    'details' => $details
                ];
            }
        }

        return $formattedFaqs;
    }
}
