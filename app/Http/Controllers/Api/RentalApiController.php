<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalCategory;
use App\Models\RentalEnquiry;
use App\Models\SiteSetting;
use App\Mail\RentalEnquiryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RentalApiController extends Controller
{
    public function rentals()
    {
        $query = RentalCategory::where(
            'status',
            1
        );

        // CATEGORY FILTER
        if ($categorySlug = request('category_slug')) {
            $query->where(
                'slug',
                $categorySlug
            );
        }

        if ($categoryId = request('category_id')) {
            $query->where(
                'id',
                $categoryId
            );
        }

        $categories = $query
            ->with([
                'rentalItems' => function ($q) {
                    $q->where(
                        'status',
                        1
                    )
                    ->orderBy(
                        'sort_order'
                    );
                }
            ])
            ->orderBy('sort_order')
            ->get();

        $categories->each(function ($category) {
            $category->rentalItems->transform(
                function ($item) {
                    $item->image = $item->image
                        ? asset($item->image)
                        : null;
                    return $item;
                }
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Rental categories found.',
            'data' => $categories,
            'page_content' => \App\Models\Page::getPageContent('rental-services'),
        ]);
    }

     public function submitEnquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rental_id' => ['nullable', 'exists:rental_items,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }

        $validated = $validator->validated();
        $validated['status'] = 'unread';

        $enquiry = RentalEnquiry::create($validated);

        // Retrieve recipient email from Site Setting (enquiry_email) or fallback to env('MAIL_ADMIN') or a default email
        $recipient = SiteSetting::where('key', 'enquiry_email')->value('value');

        if (empty($recipient)) {
            $recipient = env('MAIL_ADMIN');
        }

        // Send mail notification
        try {
            $mail = Mail::to($recipient);
            $ccEmails = SiteSetting::getCcEmailsByKey('enquiry_cc_emails');
            if (!empty($ccEmails)) {
                $mail->cc($ccEmails);
            }
            $mail->send(new RentalEnquiryMail($enquiry));
        } catch (\Exception $e) {
            logger()->error('Failed forwarding rental enquiry email: ' . $e->getMessage(), [
                'recipient' => $recipient,
                'enquiry' => $enquiry->toArray()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your rental enquiry has been successfully submitted.'
        ], 200);
    }

}
