<?php

use App\Http\Controllers\Admin\AttractionController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CakeEnquiryController;
use App\Http\Controllers\Admin\RentalEnquiryController;
use App\Http\Controllers\Admin\BalloonDecorationController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BirthdayPackageController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CafeMenuCategoryController;
use App\Http\Controllers\Admin\CafeMenuController;
use App\Http\Controllers\Admin\CakeController;
use App\Http\Controllers\Admin\ClientLogoController;
use App\Http\Controllers\Admin\ContactEnquiryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FoodMenuCategoryController;
use App\Http\Controllers\Admin\FoodMenuController;
use App\Http\Controllers\Admin\GeneralAccessController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\HeaderMenuController;
use App\Http\Controllers\Admin\RuleController;
use App\Http\Controllers\Admin\ImageGalleryController;
use App\Http\Controllers\Admin\NewsletterSubscriptionController;
use App\Http\Controllers\Admin\NewsUpdateController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OutDoorEventsController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PartyExtraController;
use App\Http\Controllers\Admin\RentalCategoryController;
use App\Http\Controllers\Admin\RentalItemController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\VideoGalleryController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::patch('staffs/{staff}/status', [StaffController::class, 'updateStatus'])->name('staffs.update-status');
    Route::resource('staffs', StaffController::class)->except(['show']);

    Route::patch('roles/{role}/status', [RoleController::class, 'updateStatus'])->name('roles.update-status');
    Route::resource('roles', RoleController::class)->except(['show']);

    Route::patch('banners/{banner}/status', [BannerController::class, 'updateStatus'])->name('banners.update-status');
    Route::resource('banners', BannerController::class)->except(['show']);

    Route::patch('client-logos/{clientLogo}/status', [ClientLogoController::class, 'updateStatus'])->name('client-logos.update-status');
    Route::resource('client-logos', ClientLogoController::class)->parameters(['client-logos' => 'clientLogo'])->except(['show']);

    Route::patch('testimonials/{testimonial}/status', [TestimonialController::class, 'updateStatus'])->name('testimonials.update-status');
    Route::resource('testimonials', TestimonialController::class)->except(['show']);

    Route::get('general-settings', [GeneralSettingController::class, 'edit'])->name('general-settings.edit');
    Route::put('general-settings', [GeneralSettingController::class, 'update'])->name('general-settings.update');

    Route::patch('rules/{rule}/status', [RuleController::class, 'updateStatus'])->name('rules.update-status');
    Route::resource('rules', RuleController::class)->except(['show']);

    // FAQ MODULE
    Route::resource('faqs', FaqController::class)->parameters(['faqs' => 'faq'])->except(['show']);

    // Image and video gallery
    Route::resource('image-gallery', ImageGalleryController::class);
    Route::resource('video-gallery', VideoGalleryController::class);

    Route::post('/image-gallery/sort', [ImageGalleryController::class, 'sort'])
    ->name('image-gallery.sort');

    Route::post('/video-gallery/sort', [VideoGalleryController::class, 'sort'])
    ->name('video-gallery.sort');

    Route::resource('outdoor-events', OutDoorEventsController::class);
    Route::post('/outdoor-events/sort', [OutDoorEventsController::class, 'sort'])
    ->name('outdoor-events.sort');

    Route::resource('branches', BranchController::class);

    Route::resource('pages', PageController::class)->only(['index', 'edit', 'update']);

    Route::post('header-menus/reorder', [HeaderMenuController::class, 'reorder'])->name('header-menus.reorder');
    Route::patch('header-menus/{headerMenu}/status', [HeaderMenuController::class, 'updateStatus'])->name('header-menus.update-status');
    Route::resource('header-menus', HeaderMenuController::class)->except(['show']);

    Route::resource('cakes', CakeController::class);

    Route::resource('cafe-menu-categories', CafeMenuCategoryController::class);

    Route::resource('cafe-menus', CafeMenuController::class);

    Route::resource('rental-categories', RentalCategoryController::class);

    Route::resource('rental-items', RentalItemController::class);

    Route::resource('balloon-decorations', BalloonDecorationController::class);

    Route::post('birthday-packages/{birthday_package}/copy', [BirthdayPackageController::class, 'copy'])->name('birthday-packages.copy');
    Route::resource('birthday-packages', BirthdayPackageController::class);

    Route::resource('events', EventController::class)->parameters(['events' => 'event'])->except(['show']);

    Route::resource('food-menu-categories', FoodMenuCategoryController::class);
    Route::resource('food-menus', FoodMenuController::class);

    Route::resource('party-extras', PartyExtraController::class);

    Route::resource('general-access', GeneralAccessController::class);
    Route::post('packages/{package}/copy', [PackageController::class, 'copy'])->name('packages.copy');
    Route::resource('packages', PackageController::class);
    Route::resource('contact-enquiries', ContactEnquiryController::class)->only(['index', 'show', 'destroy']);
    Route::resource('cake-enquiries', CakeEnquiryController::class)->only(['index', 'show', 'destroy']);
    Route::resource('rental-enquiries', RentalEnquiryController::class)->only(['index', 'show', 'destroy']);

    Route::patch('attractions/{attraction}/status', [AttractionController::class, 'updateStatus'])->name('attractions.update-status');
    Route::resource('attractions', AttractionController::class);

    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('admin.notifications.mark-all-read');
    Route::post('/notifications/bulk-delete', [NotificationController::class, 'bulkDelete'])->name('admin.notifications.bulk-delete');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');

    Route::group(['prefix' => 'bookings'], function () {

        Route::get('/', [
            BookingController::class,
            'index'
        ])->name('bookings.index');

        Route::get('/{booking}', [
            BookingController::class,
            'show'
        ])->name('bookings.show');

        Route::get('/{booking}/invoice', [
            BookingController::class,
            'invoice'
        ])->name('bookings.invoice');

        Route::post('/{booking}/payment-status', [
            BookingController::class,
            'updatePaymentStatus'
        ])->name('bookings.payment-status');
    });

    Route::resource(
        'news-updates',
        NewsUpdateController::class
    );

    Route::get('newsletter-subscriptions/export', [NewsletterSubscriptionController::class, 'export'])->name('newsletter-subscriptions.export');
    Route::resource('newsletter-subscriptions', NewsletterSubscriptionController::class)->only(['index', 'destroy']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    Route::post('/clear-cache', [DashboardController::class, 'clearCache'])->name('admin.clear-cache');

});
