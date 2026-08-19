<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BirthdayPackage;
use App\Models\Branch;
use App\Models\Faq;

class BirthdayPackageApiController extends Controller
{
    public function birthdayPackages()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = BirthdayPackage::with('branch')
            ->where(
                'status',
                1
            );

        if ($branchId = request('branch_id')) {
            $query->where(
                'branch_id',
                $branchId
            );
        }

        if ($slug = request('slug')) {

            $query->where(
                'slug',
                $slug
            );
        }

        $packages = $query
            ->orderBy('sort_order')
            ->paginate($limit);

        $packages->getCollection()->transform(
            function ($item) {

                $item->image = $item->image
                    ? asset($item->image)
                    : null;

                $item->banner_image = $item->banner_image
                    ? asset($item->banner_image)
                    : null;

                $item->og_image = $item->og_image
                    ? asset($item->og_image)
                    : null;

                $item->selected_faqs = $this->getFaqs($item->faq_selection);
                $item->minimum_kids = null;
                $item->duration = null;

                unset($item->faq_selection);
                

                return $item;
            }
        );

        $locations = Branch::where('status', 1)
                                ->orderBy('sort_order', 'asc')
                                ->get(['id','title', 'description', 'image','location_link', 'embedded_link', 'address', 'phone', 'email','working_hours'])
                                ->map(function ($client) {
                                    return [
                                        'id' => $client->id,
                                        'title' => $client->title,
                                        'description' => $client->description,
                                        'image' => $client->image ? asset($client->image) : null,
                                        'location_link' => $client->location_link,
                                        'embedded_link' => $client->embedded_link,
                                        'address' => $client->address,
                                        'phone' => $client->phone,
                                        'email' => $client->email,
                                        'working_hours' => $client->working_hours
                                    ];
                                });

        $packagesArray = $packages->toArray();
        $packagesArray['locations'] = $locations;

        return response()->json([
            'success' => true,
            'message' => 'Birthday packages retrieved successfully.',
            'page_content' => \App\Models\Page::getPageContent('birthday-packages'),
            'data' => $packagesArray
        ]);
    }

    public static function getFaqs($faqSelection)
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
