@extends('layouts.app')

@section('style')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .dashboard-container {
        font-family: 'Outfit', sans-serif !important;
    }

    /* Glassmorphism Design System */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.02), 0 1px 3px rgba(15, 23, 42, 0.01);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border-radius: 18px !important;
        overflow: hidden;
    }

    [data-theme="dark"] .glass-card {
        background: rgba(30, 41, 59, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .glass-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
        border-color: rgba(37, 99, 235, 0.2);
    }

    [data-theme="dark"] .glass-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
        border-color: rgba(37, 99, 235, 0.35);
    }

    /* Soft Accent Gradient Glows */
    .gradient-primary-glow {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.06) 0%, rgba(124, 58, 237, 0.02) 100%);
    }
    .gradient-success-glow {
        background: linear-gradient(135deg, rgba(22, 163, 74, 0.06) 0%, rgba(20, 184, 166, 0.02) 100%);
    }
    .gradient-purple-glow {
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.06) 0%, rgba(219, 39, 119, 0.02) 100%);
    }
    .gradient-warning-glow {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.06) 0%, rgba(234, 179, 8, 0.02) 100%);
    }
    .gradient-danger-glow {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.06) 0%, rgba(244, 63, 94, 0.02) 100%);
    }
    .gradient-info-glow {
        background: linear-gradient(135deg, rgba(8, 145, 178, 0.06) 0%, rgba(56, 189, 248, 0.02) 100%);
    }

    /* Micro-Animations & Glows */
    .pulse-glow-green {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #16a34a;
        box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 8px rgba(22, 163, 74, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(22, 163, 74, 0);
        }
    }

    /* Action Tiles styling */
    .action-tile {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px dashed rgba(37, 99, 235, 0.25);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.3);
    }
    [data-theme="dark"] .action-tile {
        border: 1px dashed rgba(255, 255, 255, 0.15);
        background: rgba(15, 23, 42, 0.2);
    }
    .action-tile:hover {
        background: rgba(37, 99, 235, 0.06) !important;
        border-style: solid;
        border-color: rgba(37, 99, 235, 0.5);
        transform: translateY(-3px);
    }

    /* Hero welcome panel background overlay pattern */
    .hero-panel {
        background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    [data-theme="dark"] .hero-panel {
        background: linear-gradient(135deg, #0f172a 0%, #020617 100%);
    }
    .hero-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        opacity: 0.1;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 20px 20px;
        pointer-events: none;
    }

    /* Modern Tabs Layout */
    .custom-pills .nav-link {
        border-radius: 10px;
        font-weight: 500;
        color: var(--text-secondary-light);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .custom-pills .nav-link.active {
        background-color: var(--primary-600) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    [data-theme="dark"] .custom-pills .nav-link:not(.active) {
        color: #94a3b8;
    }
    [data-theme="dark"] .custom-pills .nav-link:not(.active):hover {
        background: rgba(255, 255, 255, 0.05);
    }

    /* Rounded Avatar & pulse indicator */
    .avatar-wrapper {
        position: relative;
    }
    .online-indicator {
        position: absolute;
        bottom: 0;
        right: 0;
        border: 2px solid white;
    }
    [data-theme="dark"] .online-indicator {
        border-color: #1e293b;
    }

    /* Image hover animation inside table */
    .thumbnail-container {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
    }
    .thumbnail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .thumbnail-container:hover .thumbnail-img {
        transform: scale(1.2);
    }
    a.glass-card {
        color: inherit !important;
        text-decoration: none !important;
        display: block !important;
    }
</style>
@endsection
@section('content')

<div class="dashboard-container">
    <!-- Hero Welcome Banner Section -->
    <div class="hero-panel p-28 p-md-36 text-white mb-28 shadow-sm">
        <div class="row align-items-center gy-4">
            <div class="col-lg-8">
                <span class="badge bg-primary-500 text-white text-xs px-12 py-6 rounded-pill mb-12 fw-semibold">SPLASH N PARTY ADMIN</span>
                <h3 class="fw-bold text-white mb-8">Good Day, {{ auth()->user()->name }}!</h3>
                <p class="text-white-50 mb-0 max-w-480-px text-sm">
                    Welcome to your main operations console. You have full command over bookings, branch details, food catalog, custom cakes, and support settings.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-column bg-white bg-opacity-10 backdrop-blur-md rounded-12 p-16 border border-white border-opacity-10 text-start text-lg-end">
                    <span class="text-xs text-white-50 d-block mb-4">SYSTEM CLOCK & CALENDAR</span>
                    <h5 class="fw-bold mb-0 text-white" id="liveClock">00:00:00 AM</h5>
                    <span class="text-xs text-white-50 d-block mt-4" id="liveDate">{{ now()->format('l, d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row (6 KPI Metrics) -->
    <div class="row row-cols-xxl-6 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4 mb-28">
        <!-- KPI 1: Birthday Packages -->
        <div class="col">
            <a href="{{ route('birthday-packages.index') }}" class="card glass-card gradient-primary-glow h-100 border-0 text-decoration-none">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Birthday Packages</span>
                            <div class="w-36-px h-36-px bg-primary-100 dark:bg-primary-950 text-primary-600 dark:text-primary-400 rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:gift-bold" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <h4 class="fw-bold text-neutral-900 dark:text-white mb-4">{{ number_format($stats['birthday_package_count']) }}</h4>
                        <p class="text-xs text-secondary-light dark:text-neutral-400 mb-0">Active packages catalog</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- KPI 2: Events & Parties -->
        <div class="col">
            <a href="{{ route('events.index') }}" class="card glass-card gradient-purple-glow h-100 border-0 text-decoration-none">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Events & Parties</span>
                            <div class="w-36-px h-36-px bg-purple-100 dark:bg-purple-950 text-purple-600 dark:text-purple-400 rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:clapperboard-play-bold" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <h4 class="fw-bold text-neutral-900 dark:text-white mb-4">{{ number_format($stats['event_count']) }}</h4>
                        <p class="text-xs text-secondary-light dark:text-neutral-400 mb-0">Organized events</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- KPI 3: Cafe Menu Items -->
        <div class="col">
            <a href="{{ route('cafe-menus.index') }}" class="card glass-card gradient-info-glow h-100 border-0 text-decoration-none">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Cafe Items</span>
                            <div class="w-36-px h-36-px bg-info-100 dark:bg-info-950 text-info-600 dark:text-info-400 rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:cup-hot-bold" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <h4 class="fw-bold text-neutral-900 dark:text-white mb-4">{{ number_format($stats['cafe_menu_count']) }}</h4>
                        <p class="text-xs text-secondary-light dark:text-neutral-400 mb-0">Dishes & beverages</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- KPI 4: Custom Cake Designs -->
        <div class="col">
            <a href="{{ route('cakes.index') }}" class="card glass-card gradient-success-glow h-100 border-0 text-decoration-none">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Cake Designs</span>
                            <div class="w-36-px h-36-px bg-success-100 dark:bg-success-950 text-success-main dark:text-success-400 rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:crown-minimalistic-bold" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <h4 class="fw-bold text-neutral-900 dark:text-white mb-4">{{ number_format($stats['cake_count']) }}</h4>
                        <p class="text-xs text-secondary-light dark:text-neutral-400 mb-0">Custom cake models</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- KPI 5: Rental Gear & Decor -->
        <div class="col">
            <a href="{{ route('rental-items.index') }}" class="card glass-card gradient-danger-glow h-100 border-0 text-decoration-none">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Rental Items</span>
                            <div class="w-36-px h-36-px bg-danger-100 dark:bg-danger-950 text-danger-600 dark:text-danger-400 rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:box-bold" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <h4 class="fw-bold text-neutral-900 dark:text-white mb-4">{{ number_format($stats['rental_item_count']) }}</h4>
                        <p class="text-xs text-secondary-light dark:text-neutral-400 mb-0">Party rentals & decor</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- KPI 6: Active Venue Branches -->
        <div class="col">
            <a href="{{ route('branches.index') }}" class="card glass-card gradient-warning-glow h-100 border-0 text-decoration-none">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Active Branches</span>
                            <div class="w-36-px h-36-px bg-warning-100 dark:bg-warning-950 text-warning-main dark:text-warning-400 rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:map-point-bold" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <h4 class="fw-bold text-neutral-900 dark:text-white mb-4">{{ number_format($stats['branch_count']) }}</h4>
                        <p class="text-xs text-secondary-light dark:text-neutral-400 mb-0">Locations & venues</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Operations Hub & Quick Actions Grid -->
    <div class="row gy-4 mb-28">
        <div class="col-xxl-8 col-xl-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <h6 class="fw-bold text-lg mb-4 text-neutral-900 dark:text-white">Admin Quick-Action Hub</h6>
                    <p class="text-sm text-secondary-light dark:text-neutral-400 mb-20">Instantly launch new entries in the respective database catalogs from here.</p>

                    <div class="row g-3">
                        @can('create_birthday_packages')
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('birthday-packages.create') }}" class="action-tile p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
                                <iconify-icon icon="solar:gift-bold" class="text-2xl text-primary-600"></iconify-icon>
                                <span class="fw-semibold text-neutral-900 dark:text-white text-xs d-block">Add Package</span>
                            </a>
                        </div>
                        @endcan

                        @can('create_cafe_menus')
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('cafe-menus.create') }}" class="action-tile p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
                                <iconify-icon icon="solar:cup-hot-bold" class="text-2xl text-cyan"></iconify-icon>
                                <span class="fw-semibold text-neutral-900 dark:text-white text-xs d-block">New Cafe Item</span>
                            </a>
                        </div>
                        @endcan

                        @can('create_cakes')
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('cakes.create') }}" class="action-tile p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
                                <iconify-icon icon="solar:crown-minimalistic-bold" class="text-2xl text-success-main"></iconify-icon>
                                <span class="fw-semibold text-neutral-900 dark:text-white text-xs d-block">Add Custom Cake</span>
                            </a>
                        </div>
                        @endcan

                        @can('create_events')
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('events.create') }}" class="action-tile p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
                                <iconify-icon icon="solar:clapperboard-play-bold" class="text-2xl text-purple-600"></iconify-icon>
                                <span class="fw-semibold text-neutral-900 dark:text-white text-xs d-block">New Event</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-xl-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <h6 class="fw-bold text-lg mb-4 text-neutral-900 dark:text-white">Active System Health</h6>
                    <p class="text-sm text-secondary-light dark:text-neutral-400 mb-20">Operational metrics and data nodes status.</p>

                    <div class="d-flex flex-column gap-16">
                        {{-- <div class="d-flex align-items-center justify-content-between p-12 bg-neutral-50 dark:bg-neutral-900 bg-opacity-40 rounded-10">
                            <div class="d-flex align-items-center gap-3">
                                <span class="pulse-glow-green"></span>
                                <span class="text-xs fw-semibold text-neutral-900 dark:text-white">Database Server Node</span>
                            </div>
                            <span class="badge bg-success-focus text-success-main text-2xs px-8 py-4 rounded fw-semibold">ONLINE</span>
                        </div> --}}

                        <div class="d-flex align-items-center justify-content-between p-12 bg-neutral-50 dark:bg-neutral-900 bg-opacity-40 rounded-10">
                            <div class="d-flex align-items-center gap-3">
                                <iconify-icon icon="solar:users-group-two-rounded-bold" class="text-primary-600 text-sm"></iconify-icon>
                                <span class="text-xs fw-semibold text-neutral-900 dark:text-white">Staff Coverage</span>
                            </div>
                            <span class="text-xs fw-semibold text-primary-light">{{ $stats['staff_count'] }} Active Employees</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-12 bg-neutral-50 dark:bg-neutral-900 bg-opacity-40 rounded-10">
                            <div class="d-flex align-items-center gap-3">
                                <iconify-icon icon="solar:map-point-wave-bold" class="text-warning-main text-sm"></iconify-icon>
                                <span class="text-xs fw-semibold text-neutral-900 dark:text-white">Locations Connected</span>
                            </div>
                            <span class="text-xs fw-semibold text-warning-main">{{ $stats['branch_count'] }} Venues</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Suite -->
    <div class="row gy-4 mb-28">
        <!-- Catalog Distribution Breakdown -->
        <div class="col-xxl-7 col-xl-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between mb-24">
                        <div>
                            <h6 class="text-lg fw-bold text-neutral-900 dark:text-white mb-4">Catalog Distribution Matrix</h6>
                            <span class="text-xs text-secondary-light dark:text-neutral-400">Statistical distribution of system elements</span>
                        </div>
                        <span class="badge bg-primary-50 text-primary-600 dark:bg-primary-950 dark:text-primary-400 text-2xs px-10 py-6 rounded-pill fw-semibold">Live System Data</span>
                    </div>
                    <div id="catalogDistributionChart" style="min-height: 310px;"></div>
                </div>
            </div>
        </div>

        <!-- Supportive Content Donut Chart -->
        <div class="col-xxl-5 col-xl-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <h6 class="text-lg fw-bold text-neutral-900 dark:text-white mb-4">Support & Engagement Assets</h6>
                    <p class="text-sm text-secondary-light dark:text-neutral-400 mb-24">Readiness indicators of secondary modules and gallery components.</p>
                    
                    <div class="row align-items-center">
                        <div class="col-md-7 col-12 mb-16 mb-md-0">
                            <div id="engagementRadialChart" style="min-height: 250px;"></div>
                        </div>
                        <div class="col-md-5 col-12">
                            <div class="d-flex flex-column gap-12">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-12-px h-12-px rounded-circle bg-primary-600"></div>
                                    <div class="flex-grow-1">
                                        <span class="text-xs text-secondary-light dark:text-neutral-400 d-block">Gallery Assets</span>
                                        <h6 class="fw-bold mb-0 text-sm text-neutral-900 dark:text-white">{{ $stats['gallery_count'] }} files</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-12-px h-12-px rounded-circle bg-success-main"></div>
                                    <div class="flex-grow-1">
                                        <span class="text-xs text-secondary-light dark:text-neutral-400 d-block">Testimonials</span>
                                        <h6 class="fw-bold mb-0 text-sm text-neutral-900 dark:text-white">{{ $stats['testimonial_count'] }} quotes</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-12-px h-12-px rounded-circle bg-warning-main"></div>
                                    <div class="flex-grow-1">
                                        <span class="text-xs text-secondary-light dark:text-neutral-400 d-block">FAQ Entries</span>
                                        <h6 class="fw-bold mb-0 text-sm text-neutral-900 dark:text-white">{{ $stats['faq_count'] }} records</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-12-px h-12-px rounded-circle bg-danger-main"></div>
                                    <div class="flex-grow-1">
                                        <span class="text-xs text-secondary-light dark:text-neutral-400 d-block">Home Banners</span>
                                        <h6 class="fw-bold mb-0 text-sm text-neutral-900 dark:text-white">{{ $stats['banner_count'] }} active</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-20 p-12 bg-neutral-50 dark:bg-neutral-900 bg-opacity-50 rounded-12 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:settings-bold" class="text-lg text-primary-600"></iconify-icon>
                            <span class="text-xs fw-semibold text-neutral-900 dark:text-white">Master Site Settings</span>
                        </div>
                        <a href="{{ route('general-settings.edit') }}" class="btn btn-primary-600 btn-sm py-4 px-12 rounded-8 text-xs">Configure</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Catalog Lists & Team Section -->
    <div class="row gy-4">
        <!-- Tabbed Catalog Lists -->
        <div class="col-xxl-8 col-xl-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-24 gap-3">
                        <div>
                            <h6 class="fw-bold text-lg text-neutral-900 dark:text-white mb-2">Operational Asset Catalog</h6>
                            <span class="text-xs text-secondary-light dark:text-neutral-400">Browse the latest records registered in each category</span>
                        </div>
                        
                        <ul class="nav nav-pills custom-pills mb-0 gap-2" id="recentAssetsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active px-16 py-8 d-flex align-items-center text-xs" id="birthday-tab" data-bs-toggle="pill" data-bs-target="#birthday-panel" type="button" role="tab" aria-controls="birthday-panel" aria-selected="true">
                                    Packages
                                    <span class="badge bg-white bg-opacity-20 rounded-pill text-white ms-8 text-2xs">{{ count($recent_birthday_packages) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-16 py-8 d-flex align-items-center text-xs" id="cafe-tab" data-bs-toggle="pill" data-bs-target="#cafe-panel" type="button" role="tab" aria-controls="cafe-panel" aria-selected="false">
                                    Cafe Menu
                                    <span class="badge bg-white bg-opacity-20 rounded-pill text-white ms-8 text-2xs">{{ count($recent_cafe_menus) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-16 py-8 d-flex align-items-center text-xs" id="cakes-tab" data-bs-toggle="pill" data-bs-target="#cakes-panel" type="button" role="tab" aria-controls="cakes-panel" aria-selected="false">
                                    Custom Cakes
                                    <span class="badge bg-white bg-opacity-20 rounded-pill text-white ms-8 text-2xs">{{ count($recent_cakes) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-16 py-8 d-flex align-items-center text-xs" id="events-tab" data-bs-toggle="pill" data-bs-target="#events-panel" type="button" role="tab" aria-controls="events-panel" aria-selected="false">
                                    Events
                                    <span class="badge bg-white bg-opacity-20 rounded-pill text-white ms-8 text-2xs">{{ count($recent_events) }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="recentAssetsTabContent">
                        <!-- Birthday Packages Panel -->
                        <div class="tab-pane fade show active" id="birthday-panel" role="tabpanel" aria-labelledby="birthday-tab">
                            <div class="table-responsive">
                                <table class="table bordered-table sm-table mb-0 align-middle">
                                    <thead>
                                        <tr class="text-neutral-700 dark:text-neutral-300">
                                            <th>Title</th>
                                            <th>Branch Venue</th>
                                            <th>Rate / Price</th>
                                            <th class="text-center">Active Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recent_birthday_packages as $package)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="thumbnail-container border border-neutral-200 dark:border-neutral-800">
                                                            @if ($package->image)
                                                                <img src="{{ asset('storage/' . $package->image) }}" alt="" class="thumbnail-img">
                                                            @else
                                                                <div class="w-100 h-100 bg-neutral-100 dark:bg-neutral-800 d-flex justify-content-center align-items-center">
                                                                    <iconify-icon icon="solar:gift-bold" class="text-neutral-400"></iconify-icon>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="text-sm mb-0 fw-semibold text-neutral-900 dark:text-white">{{ $package->title }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-xs fw-medium text-secondary-light dark:text-neutral-300">{{ $package->branch?->name ?? 'Global Venue' }}</td>
                                                <td><span class="fw-bold text-primary-600 text-sm">{{ $package->price ? 'AED ' . number_format($package->price, 2) : 'N/A' }}</span></td>
                                                <td class="text-center">
                                                    @if($package->status)
                                                        <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-2xs">ACTIVE</span>
                                                    @else
                                                        <span class="bg-neutral-100 text-neutral-600 px-12 py-4 rounded-pill fw-semibold text-2xs">INACTIVE</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-36 text-secondary-light dark:text-neutral-500">No birthday packages cataloged yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Cafe Panel -->
                        <div class="tab-pane fade" id="cafe-panel" role="tabpanel" aria-labelledby="cafe-tab">
                            <div class="table-responsive">
                                <table class="table bordered-table sm-table mb-0 align-middle">
                                    <thead>
                                        <tr class="text-neutral-700 dark:text-neutral-300">
                                            <th>Dish / Drink</th>
                                            <th>Category Type</th>
                                            <th>Rate / Price</th>
                                            <th class="text-center">Active Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recent_cafe_menus as $menu)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="thumbnail-container border border-neutral-200 dark:border-neutral-800">
                                                            @if ($menu->image)
                                                                <img src="{{ asset('storage/' . $menu->image) }}" alt="" class="thumbnail-img">
                                                            @else
                                                                <div class="w-100 h-100 bg-neutral-100 dark:bg-neutral-800 d-flex justify-content-center align-items-center">
                                                                    <iconify-icon icon="solar:cup-hot-bold" class="text-neutral-400"></iconify-icon>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="text-sm mb-0 fw-semibold text-neutral-900 dark:text-white">{{ $menu->title }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-xs fw-medium text-secondary-light dark:text-neutral-300">{{ $menu->category?->name ?? 'General Delights' }}</td>
                                                <td><span class="fw-bold text-primary-600 text-sm">{{ $menu->price ? 'AED ' . number_format($menu->price, 2) : 'N/A' }}</span></td>
                                                <td class="text-center">
                                                    @if($menu->status)
                                                        <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-2xs">ACTIVE</span>
                                                    @else
                                                        <span class="bg-neutral-100 text-neutral-600 px-12 py-4 rounded-pill fw-semibold text-2xs">INACTIVE</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-36 text-secondary-light dark:text-neutral-500">No cafe items registered yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Cakes Panel (NEW) -->
                        <div class="tab-pane fade" id="cakes-panel" role="tabpanel" aria-labelledby="cakes-tab">
                            <div class="table-responsive">
                                <table class="table bordered-table sm-table mb-0 align-middle">
                                    <thead>
                                        <tr class="text-neutral-700 dark:text-neutral-300">
                                            <th>Cake Model</th>
                                            <th>Product ID</th>
                                            <th>Price Rate</th>
                                            <th class="text-center">Active Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recent_cakes as $cake)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="thumbnail-container border border-neutral-200 dark:border-neutral-800">
                                                            @if ($cake->thumbnail_image)
                                                                <img src="{{ asset($cake->thumbnail_image) }}" alt="" class="thumbnail-img">
                                                            @else
                                                                <div class="w-100 h-100 bg-neutral-100 dark:bg-neutral-800 d-flex justify-content-center align-items-center">
                                                                    <iconify-icon icon="solar:crown-minimalistic-bold" class="text-neutral-400"></iconify-icon>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="text-sm mb-0 fw-semibold text-neutral-900 dark:text-white">{{ $cake->title }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-xs fw-medium text-secondary-light dark:text-neutral-300">{{ $cake->product_code ?: 'N/A' }}</td>
                                                <td><span class="fw-bold text-primary-600 text-sm">{{ $cake->price ? 'AED ' . number_format($cake->price, 2) : 'N/A' }}</span></td>
                                                <td class="text-center">
                                                    @if($cake->status)
                                                        <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-2xs">ACTIVE</span>
                                                    @else
                                                        <span class="bg-neutral-100 text-neutral-600 px-12 py-4 rounded-pill fw-semibold text-2xs">INACTIVE</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-36 text-secondary-light dark:text-neutral-500">No custom cake designs loaded.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Events Panel -->
                        <div class="tab-pane fade" id="events-panel" role="tabpanel" aria-labelledby="events-tab">
                            <div class="table-responsive">
                                <table class="table bordered-table sm-table mb-0 align-middle">
                                    <thead>
                                        <tr class="text-neutral-700 dark:text-neutral-300">
                                            <th>Event Occasion</th>
                                            <th>Created Date</th>
                                            <th class="text-center">Active Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recent_events as $event)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="thumbnail-container border border-neutral-200 dark:border-neutral-800">
                                                            @if ($event->image)
                                                                <img src="{{ asset('storage/' . $event->image) }}" alt="" class="thumbnail-img">
                                                            @else
                                                                <div class="w-100 h-100 bg-neutral-100 dark:bg-neutral-800 d-flex justify-content-center align-items-center">
                                                                    <iconify-icon icon="solar:clapperboard-play-bold" class="text-neutral-400"></iconify-icon>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="text-sm mb-0 fw-semibold text-neutral-900 dark:text-white">{{ $event->title }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-xs fw-medium text-secondary-light dark:text-neutral-300">{{ $event->created_at?->format('d M Y') ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    @if($event->status)
                                                        <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-2xs">ACTIVE</span>
                                                    @else
                                                        <span class="bg-neutral-100 text-neutral-600 px-12 py-4 rounded-pill fw-semibold text-2xs">INACTIVE</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-36 text-secondary-light dark:text-neutral-500">No events registered yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Members List -->
        <div class="col-xxl-4 col-xl-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between mb-24">
                        <div>
                            <h6 class="fw-bold text-lg text-neutral-900 dark:text-white mb-2">Venue Team Directory</h6>
                            <span class="text-xs text-secondary-light dark:text-neutral-400">Administrative and playground staff roles</span>
                        </div>
                        <a href="{{ route('staffs.index') }}" class="text-primary-600 dark:text-primary-400 hover-text-primary d-inline-flex align-items-center gap-1 text-xs fw-semibold">
                            Directory
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>

                    <div class="d-flex flex-column gap-20">
                        @forelse ($staff_members as $member)
                            <div class="d-flex align-items-center justify-content-between gap-3 p-12 rounded-12 bg-neutral-50 dark:bg-neutral-900 bg-opacity-30 border border-neutral-100 border-opacity-50 dark:border-neutral-800 dark:border-opacity-50">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper me-12">
                                        <div class="w-40-px h-40-px bg-primary-100 dark:bg-primary-950 rounded-circle d-flex justify-content-center align-items-center text-primary-600 dark:text-primary-400 font-bold">
                                            {{ strtoupper(substr($member->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="online-indicator w-10-px h-10-px bg-success-main rounded-circle d-block"></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-sm mb-0 fw-semibold text-neutral-900 dark:text-white">{{ $member->name }}</h6>
                                        <span class="text-2xs text-secondary-light dark:text-neutral-400 d-block">{{ $member->email }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 text-2xs px-10 py-6 rounded-pill fw-semibold">
                                    {{ $member->roles->first()?->name ?? 'Staff' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-secondary-light dark:text-neutral-500 py-36 text-sm mb-0">No registered staff found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <!-- Apex Chart js -->
    <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Live Server Ticking Clock
            function updateClock() {
                const now = new Date();
                let hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                const hoursStr = String(hours).padStart(2, '0');
                
                const timeString = `${hoursStr}:${minutes}:${seconds} ${ampm}`;
                const clockEl = document.getElementById('liveClock');
                if (clockEl) {
                    clockEl.textContent = timeString;
                }
            }
            updateClock();
            setInterval(updateClock, 1000);

            // Catalog Distribution Column Chart
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const textColors = isDark ? '#94a3b8' : '#64748b';
            const gridColors = isDark ? '#334155' : '#f1f5f9';

            var catalogOptions = {
                series: [{
                    name: 'Catalog Records Count',
                    data: [
                        {{ $stats['birthday_package_count'] }},
                        {{ $stats['event_count'] }},
                        {{ $stats['cafe_menu_count'] }},
                        {{ $stats['cake_count'] }},
                        {{ $stats['rental_item_count'] }}
                    ]
                }],
                chart: {
                    type: 'bar',
                    height: 310,
                    toolbar: {
                        show: false
                    },
                    parentHeightOffset: 0
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        horizontal: false,
                        columnWidth: '38%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Birthday Packages', 'Events', 'Cafe Menu', 'Custom Cakes', 'Rental Items'],
                    labels: {
                        style: {
                            colors: textColors,
                            fontSize: '11px',
                            fontWeight: 500
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColors,
                            fontSize: '11px'
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "vertical",
                        shadeIntensity: 0.25,
                        gradientToColors: ['#7c3aed', '#db2777', '#0891b2', '#16a34a', '#dc2626'],
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 0.55,
                        stops: [0, 100]
                    },
                    colors: ['#2563eb', '#9333ea', '#06b6d4', '#22c55e', '#ef4444']
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: function (val) {
                            return val + " items active"
                        }
                    }
                },
                grid: {
                    borderColor: gridColors,
                    strokeDashArray: 4,
                    padding: {
                        left: 10,
                        right: 10,
                        bottom: 0
                    }
                }
            };

            var catalogChart = new ApexCharts(document.querySelector("#catalogDistributionChart"), catalogOptions);
            catalogChart.render();

            // Supportive Media & Engagement Donut Chart
            var engagementOptions = {
                series: [
                    {{ $stats['gallery_count'] }},
                    {{ $stats['testimonial_count'] }},
                    {{ $stats['faq_count'] }},
                    {{ $stats['banner_count'] }}
                ],
                chart: {
                    type: 'donut',
                    height: 250,
                    parentHeightOffset: 0
                },
                labels: ['Gallery Assets', 'Testimonials', 'FAQ Records', 'Home Banners'],
                colors: ['#2563eb', '#16a34a', '#eab308', '#dc2626'],
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '12px',
                                    fontWeight: 500,
                                    color: textColors
                                },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    fontWeight: 700,
                                    color: isDark ? '#ffffff' : '#0f172a',
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total Assets',
                                    fontSize: '11px',
                                    fontWeight: 500,
                                    color: textColors,
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                }
            };

            var engagementChart = new ApexCharts(document.querySelector("#engagementRadialChart"), engagementOptions);
            engagementChart.render();

            // Update charts colors on theme switch
            const themeBtn = document.querySelector('.theme-customization-sidebar__close');
            if (themeBtn) {
                themeBtn.addEventListener('click', function() {
                    setTimeout(() => {
                        const newDark = document.documentElement.getAttribute('data-theme') === 'dark';
                        const newTextColors = newDark ? '#94a3b8' : '#64748b';
                        const newGridColors = newDark ? '#334155' : '#f1f5f9';

                        catalogChart.updateOptions({
                            xaxis: {
                                labels: {
                                    style: {
                                        colors: newTextColors
                                    }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: newTextColors
                                    }
                                }
                            },
                            grid: {
                                borderColor: newGridColors
                            },
                            tooltip: {
                                theme: newDark ? 'dark' : 'light'
                            }
                        });

                        engagementChart.updateOptions({
                            plotOptions: {
                                pie: {
                                    donut: {
                                        labels: {
                                            name: {
                                                color: newTextColors
                                            },
                                            value: {
                                                color: newDark ? '#ffffff' : '#0f172a'
                                            },
                                            total: {
                                                color: newTextColors
                                            }
                                        }
                                    }
                                }
                            },
                            tooltip: {
                                theme: newDark ? 'dark' : 'light'
                            }
                        });
                    }, 150);
                });
            }
        });
    </script>
@endsection