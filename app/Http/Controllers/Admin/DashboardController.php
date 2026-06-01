<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\BirthdayPackage;
use App\Models\Event;
use App\Models\Cake;
use App\Models\CafeMenu;
use App\Models\RentalItem;
use App\Models\Testimonial;
use App\Models\Banner;
use App\Models\ImageGallery;
use App\Models\VideoGallery;
use App\Models\Faq;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'staff_count' => User::count(),
            'branch_count' => Branch::count(),
            'birthday_package_count' => BirthdayPackage::count(),
            'event_count' => Event::count(),
            'cake_count' => Cake::count(),
            'cafe_menu_count' => CafeMenu::count(),
            'rental_item_count' => RentalItem::count(),
            'testimonial_count' => Testimonial::count(),
            'banner_count' => Banner::count(),
            'gallery_count' => ImageGallery::count() + VideoGallery::count(),
            'faq_count' => Faq::count(),
        ];

        // Fetch recent items for preview lists
        $recent_birthday_packages = BirthdayPackage::with('branch')->latest()->take(5)->get();
        $recent_events = Event::latest()->take(5)->get();
        $recent_cakes = Cake::latest()->take(5)->get();
        $recent_cafe_menus = CafeMenu::with('category')->latest()->take(5)->get();
        $staff_members = User::latest()->take(6)->get();

        return view('dashboard', compact(
            'stats',
            'recent_birthday_packages',
            'recent_events',
            'recent_cakes',
            'recent_cafe_menus',
            'staff_members'
        ));
    }
}

