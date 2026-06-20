<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CcAvenueController extends Controller
{

    public function success(Request $request)
    {
        $workingKey = config('services.ccavenue.working_key');
        $encResponse = $request->input('encResp');
        $frontendSuccessUrl = config('services.ccavenue.frontend_success_url');
        $frontendFailureUrl = config('services.ccavenue.frontend_failure_url');

        if (!$encResponse) {
            return redirect()->to($frontendFailureUrl . '?status=failed');
        }

        $decryptedText = $this->decrypt($encResponse, $workingKey);
        parse_str($decryptedText, $responseParams);

        $orderId = $responseParams['order_id'] ?? null;
        $trackingId = $responseParams['tracking_id'] ?? null;
        $orderStatus = $responseParams['order_status'] ?? null;
        $message = $responseParams['status_message'] ?? 'Transaction was not successful.';

        $booking = null;
        if ($orderId) {
            $booking = Booking::where('booking_reference', $orderId)->first();
        }

        if ($booking && strtolower($orderStatus) === 'success') {
            $booking->update([
                'payment_status' => 'paid'
            ]);

            // Send booking confirmation email to customer and admin
            try {
                if ($booking->email) {
                    \Illuminate\Support\Facades\Mail::to($booking->email)->send(new \App\Mail\BookingInvoiceMail($booking));
                }

                $adminEmail = \App\Models\SiteSetting::where('key', 'notification_email')->value('value');
                if ($adminEmail) {
                    \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\BookingInvoiceMail($booking));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error sending booking confirmation emails: ' . $e->getMessage());
            }

            return redirect()->to($frontendSuccessUrl . '?status=success&id=' . base64_encode($booking->id));
        }

        if ($booking) {
            $booking->update([
                'payment_status' => 'unpaid'
            ]);
        }

        return redirect()->to($frontendFailureUrl . '?status=failed');
    }

    public function failure(Request $request)
    {
        $workingKey = config('services.ccavenue.working_key');
        $encResponse = $request->input('encResp');
        $frontendFailureUrl = config('services.ccavenue.frontend_failure_url');

        if (!$encResponse) {
            return redirect()->to($frontendFailureUrl . '?status=failed');
        }

        $decryptedText = $this->decrypt($encResponse, $workingKey);
        parse_str($decryptedText, $responseParams);

        $orderId = $responseParams['order_id'] ?? null;
        $trackingId = $responseParams['tracking_id'] ?? null;
        $message = $responseParams['status_message'] ?? 'Transaction failed.';

        $booking = null;
        if ($orderId) {
            $booking = Booking::where('booking_reference', $orderId)->first();
        }

        if ($booking) {
            $booking->update([
                'payment_status' => 'unpaid'
            ]);
        }

        return redirect()->to($frontendFailureUrl . '?status=failed');
    }

    public function encrypt($plainText, $key)
    {
        // $key = $this->hextobin(md5($key));
        // $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        // $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        // return bin2hex($openMode);
        $key = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		$encryptedText = bin2hex($openMode);
		return $encryptedText;
    }

    private function decrypt($encryptedText, $key)
    {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        return openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    }

    private function hextobin($hexString)
    {
         $length = strlen($hexString); 
        $binString="";   
        $count=0; 
        while($count<$length) 
        {       
            $subString =substr($hexString,$count,2);           
            $packedString = pack("H*",$subString); 
            if ($count==0)
        {
            $binString=$packedString;
        } 
            
        else 
        {
            $binString.=$packedString;
        } 
            
        $count+=2; 
        } 
        return $binString; 
    }
}