<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CakeEnquiry;
use Illuminate\Http\Request;

class CakeEnquiryController extends Controller
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
        $this->authorizeEnquiryPermission('view_cake_enquiries');

        $query = CakeEnquiry::with('cake');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('cake', function ($cakeQuery) use ($search) {
                      $cakeQuery->where('title', 'like', "%{$search}%")
                                ->orWhere('product_code', 'like', "%{$search}%");
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

        $totalCount = CakeEnquiry::count();
        $unreadCount = CakeEnquiry::where('status', 'unread')->count();

        return view('cake-enquiries.index', compact('enquiries', 'totalCount', 'unreadCount'));
    }

    /**
     * Display the specified enquiry.
     *
     * @param  \App\Models\CakeEnquiry  $cakeEnquiry
     * @return \Illuminate\View\View
     */
    public function show(CakeEnquiry $cakeEnquiry)
    {
        $this->authorizeEnquiryPermission('view_cake_enquiries');

        // Automatically mark as read
        if ($cakeEnquiry->status === 'unread') {
            $cakeEnquiry->update(['status' => 'read']);
        }

        return view('cake-enquiries.show', compact('cakeEnquiry'));
    }

    /**
     * Remove the specified enquiry from storage.
     *
     * @param  \App\Models\CakeEnquiry  $cakeEnquiry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CakeEnquiry $cakeEnquiry)
    {
        $this->authorizeEnquiryPermission('delete_cake_enquiries');

        $cakeEnquiry->delete();

        return redirect()->route('cake-enquiries.index')->with('success', 'Cake enquiry deleted successfully.');
    }
}
