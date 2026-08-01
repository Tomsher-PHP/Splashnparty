<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request;

class ContactEnquiryController extends Controller
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
        $this->authorizeEnquiryPermission('view_contact_enquiries');

        $query = ContactEnquiry::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('about', 'like', "%{$search}%");
            });
        }

        // Filter by Read/Unread status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by Date Range (Flatpickr range: YYYY-MM-DD to YYYY-MM-DD)
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->input('date_range'));
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            } elseif (count($dates) === 1) {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        // Sort by unread first, then latest
        $enquiries = $query->orderByRaw("CASE WHEN status = 'unread' THEN 0 ELSE 1 END")
                           ->latest()
                           ->paginate(1)
                           ->withQueryString();

        $totalCount = ContactEnquiry::count();
        $unreadCount = ContactEnquiry::where('status', 'unread')->count();

        return view('contact-enquiries.index', compact('enquiries', 'totalCount', 'unreadCount'));
    }

    /**
     * Display the specified enquiry.
     *
     * @param  \App\Models\ContactEnquiry  $contactEnquiry
     * @return \Illuminate\View\View
     */
    public function show(ContactEnquiry $contactEnquiry)
    {
        $this->authorizeEnquiryPermission('view_contact_enquiries');

        // Automatically mark as read
        if ($contactEnquiry->status === 'unread') {
            $contactEnquiry->update(['status' => 'read']);
        }

        return view('contact-enquiries.show', compact('contactEnquiry'));
    }

    /**
     * Remove the specified enquiry from storage.
     *
     * @param  \App\Models\ContactEnquiry  $contactEnquiry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ContactEnquiry $contactEnquiry)
    {
        $this->authorizeEnquiryPermission('delete_contact_enquiries');

        $contactEnquiry->delete();

        return redirect()->route('contact-enquiries.index')->with('success', 'Enquiry deleted successfully.');
    }
}
