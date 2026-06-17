<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Mail\ContactEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactApiController extends Controller
{
    /**
     * Handle the submission of the Contact Us form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitContactForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'about' => ['nullable', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'preferred_date' => ['nullable', 'date'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }

        $validated = $validator->validated();

        // Save the enquiry to database
        \App\Models\ContactEnquiry::create($validated);

        // Retrieve recipient email from Enquiry Settings (enquiry_email)
        $recipient = SiteSetting::where('key', 'enquiry_email')->value('value');

        if (empty($recipient)) {
            $recipient = env('MAIL_ADMIN');
        }

        try {
            Mail::to($recipient)->send(new ContactEnquiry($validated));

            return response()->json([
                'success' => true,
                'message' => 'Your enquiry has been successfully submitted.'
            ], 200);
        } catch (\Exception $e) {
            logger()->error('Failed forwarding website contact enquiry: ' . $e->getMessage(), [
                'recipient' => $recipient,
                'payload' => $validated
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enquiry captured, but email forwarding encountered a server issue.'
            ], 200);
        }
    }
}
