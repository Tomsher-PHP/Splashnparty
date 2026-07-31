<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cake;
use App\Models\CakeEnquiry;
use App\Models\SiteSetting;
use App\Mail\CakeEnquiry as CakeEnquiryMail;
use App\Mail\CakeEnquiryThankYouMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CakeApiController extends Controller
{
    public function submitEnquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cake_id' => ['nullable', 'exists:cakes,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'preferred_date' => ['nullable', 'date'],
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

        $enquiry = CakeEnquiry::create($validated);

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
            $mail->send(new CakeEnquiryMail($enquiry));
        } catch (\Exception $e) {
            logger()->error('Failed forwarding cake enquiry email: ' . $e->getMessage(), [
                'recipient' => $recipient,
                'enquiry' => $enquiry->toArray()
            ]);
        }

        // Send thank-you email to client
        try {
            Mail::to($enquiry->email)->send(new CakeEnquiryThankYouMail($enquiry));
        } catch (\Exception $e) {
            logger()->error('Failed sending cake enquiry thank you email: ' . $e->getMessage(), [
                'recipient' => $enquiry->email,
                'enquiry' => $enquiry->toArray()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your cake enquiry has been successfully submitted.'
        ], 200);
    }

    public function cakes()
    {
        $limit = min(
            request('limit', 10),
            50
        );

        $query = Cake::query();
        $cakes = $query->where('status',1)
            ->orderBy('sort_order','asc')
            ->paginate($limit);

        // IMAGE URL FIX
        $cakes->getCollection()->transform(function ($cake) {

            $cake->thumbnail_image = $cake->thumbnail_image
                ? asset($cake->thumbnail_image)
                : null;

            $cake->gallery_images = collect($cake->gallery_images)
                ->map(fn ($image) => asset($image))
                ->toArray();

            return $cake;
        });

        return response()->json([

            'success' => true,
            'message' => 'Cakes found.',
            'data' => $cakes,
            'page_content' => \App\Models\Page::getPageContent('cake-listing'),

        ]);
    }

    public function cakeDetails(){
        $code = request('code');
        $cake = Cake::where('product_code', $code)->first();
        return response()->json([
            'success' => true,
            'message' => 'Cake found.',
            'data' => $cake,
            'page_content' => \App\Models\Page::getPageContent('cake-listing'),
        ]);
    }

}