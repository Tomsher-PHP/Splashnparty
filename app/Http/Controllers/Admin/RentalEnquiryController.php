<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalEnquiry;
use Illuminate\Http\Request;

class RentalEnquiryController extends Controller
{
    private function authorizeEnquiryPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    /**
     * Display a listing of the enquiries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorizeEnquiryPermission('view_rental_enquiries');

        $query = RentalEnquiry::with('rentalItem');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('rentalItem', function ($itemQuery) use ($search) {
                      $itemQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Read/Unread status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sort by unread first, then latest
        $enquiries = $query->orderByRaw("CASE WHEN status = 'unread' THEN 0 ELSE 1 END")
                           ->latest()
                           ->paginate(10)
                           ->withQueryString();

        $totalCount = RentalEnquiry::count();
        $unreadCount = RentalEnquiry::where('status', 'unread')->count();

        return view('rental-enquiries.index', compact('enquiries', 'totalCount', 'unreadCount'));
    }

    /**
     * Display the specified enquiry.
     *
     * @param  \App\Models\RentalEnquiry  $rentalEnquiry
     * @return \Illuminate\View\View
     */
    public function show(RentalEnquiry $rentalEnquiry)
    {
        $this->authorizeEnquiryPermission('view_rental_enquiries');

        // Automatically mark as read
        if ($rentalEnquiry->status === 'unread') {
            $rentalEnquiry->update(['status' => 'read']);
        }

        return view('rental-enquiries.show', compact('rentalEnquiry'));
    }

    /**
     * Remove the specified enquiry from storage.
     *
     * @param  \App\Models\RentalEnquiry  $rentalEnquiry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(RentalEnquiry $rentalEnquiry)
    {
        $this->authorizeEnquiryPermission('delete_rental_enquiries');

        $rentalEnquiry->delete();

        return redirect()->route('rental-enquiries.index')->with('success', 'Rental enquiry deleted successfully.');
    }
}
