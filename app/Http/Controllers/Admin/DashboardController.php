<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Booking;
use App\Models\CakeEnquiry;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $total_bookings = Booking::count();
        $total_revenue = Booking::sum('total_amount');
        $total_kids = Booking::sum('child_count');

        $stats = [
            'staff_count' => User::count(),
            'branch_count' => Branch::count(),
            'total_bookings' => $total_bookings,
            'total_revenue' => $total_revenue,
            'total_kids' => $total_kids,
            'contact_enquiries_count' => ContactEnquiry::count(),
            'cake_enquiries_count' => CakeEnquiry::count(),
        ];

        // Fetch recent paid bookings
        $recent_bookings = Booking::with(['package', 'branch'])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(10)
            ->get();

        // Available Booking Years for filtering
        $available_years = Booking::selectRaw('YEAR(booking_date) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        if (empty($available_years)) {
            $available_years = [date('Y')];
        }

        // Get filter year
        $selected_year = $request->input('chart_year', date('Y'));
        if (!in_array($selected_year, $available_years)) {
            $selected_year = reset($available_years);
        }

        // Monthly trends for the selected year
        $trends_raw = Booking::whereYear('booking_date', $selected_year)
            ->selectRaw('MONTH(booking_date) as month, COUNT(*) as count, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $trends = [];
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        foreach ($months as $num => $name) {
            $trends[] = [
                'date' => $name, // Keep key 'date' so JS reads it as the category label
                'count' => $trends_raw->has($num) ? $trends_raw->get($num)->count : 0,
                'revenue' => $trends_raw->has($num) ? (float) $trends_raw->get($num)->revenue : 0.0,
            ];
        }

        return view('dashboard', compact(
            'stats',
            'recent_bookings',
            'trends',
            'selected_year',
            'available_years'
        ));
    }

    public function clearCache()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');

            return redirect()->back()->with('success', 'Application cache cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }
}
