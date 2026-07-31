<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalCategory;
use App\Models\RentalEnquiry;
use App\Models\RentalItem;
use App\Models\SiteSetting;
use App\Mail\RentalEnquiryMail;
use App\Mail\RentalEnquiryThankYouMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RentalApiController extends Controller
{
    public function rentals()
    {
        $categories = RentalCategory::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Rental categories found.',
            'data' => $categories,
            'page_content' => \App\Models\Page::getPageContent('rental-services'),
        ]);
    }

    public function rentalItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['nullable', 'exists:rental_categories,id'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'keyword' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }

        $limit = min($request->input('limit', 10), 50);
        
        $query = RentalItem::where('status', 1);

        if ($categoryId = $request->input('category_id')) {
            $query->where('rental_category_id', $categoryId);
        }

        if ($keyword = $request->input('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        $items = $query->orderBy('sort_order', 'asc')
            ->paginate($limit);

        // Transform image paths to asset URLs
        $items->getCollection()->transform(function ($item) {
            $item->image = $item->image ? asset($item->image) : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Rental items found.',
            'data' => $items,
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

        // Send thank-you email to client
        try {
            Mail::to($enquiry->email)->send(new RentalEnquiryThankYouMail($enquiry));
        } catch (\Exception $e) {
            logger()->error('Failed sending rental enquiry thank you email: ' . $e->getMessage(), [
                'recipient' => $enquiry->email,
                'enquiry' => $enquiry->toArray()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your rental enquiry has been successfully submitted.'
        ], 200);
    }

}
