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

    /* Clean Top-Border KPI Cards with Reduced Colors */
    .kpi-card-revenue { border-top: 4px solid #10b981 !important; }
    .kpi-card-bookings { border-top: 4px solid #3b82f6 !important; }
    .kpi-card-kids { border-top: 4px solid #8b5cf6 !important; }
    .kpi-card-contact { border-top: 4px solid #f59e0b !important; }
    .kpi-card-cake { border-top: 4px solid #f43f5e !important; }

    /* Left Accent Border Spectral Highlights for Secondary Cards */
    .color-border-left-primary { border-left: 4px solid #2563eb !important; }
    .color-border-left-purple { border-left: 4px solid #7c3aed !important; }
    .color-border-left-success { border-left: 4px solid #16a34a !important; }
    .color-border-left-info { border-left: 4px solid #0891b2 !important; }
    .color-border-left-warning { border-left: 4px solid #eab308 !important; }
    .color-border-left-danger { border-left: 4px solid #dc2626 !important; }

    /* Custom Unique Gradient Active Tabs */
    #bookings-tab.nav-link.active {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        color: white !important;
    }
    #birthday-tab.nav-link.active {
        background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%) !important;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.35);
        color: white !important;
    }
    #cafe-tab.nav-link.active {
        background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%) !important;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.35);
        color: white !important;
    }
    #cakes-tab.nav-link.active {
        background: linear-gradient(135deg, #db2777 0%, #ec4899 100%) !important;
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.35);
        color: white !important;
    }
    #events-tab.nav-link.active {
        background: linear-gradient(135deg, #ea580c 0%, #f97316 100%) !important;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
        color: white !important;
    }

    /* Individual Custom Quick Action Tiles Hovers */
    .tile-bookings:hover {
        background: rgba(99, 102, 241, 0.08) !important;
        border-color: rgba(99, 102, 241, 0.5) !important;
    }
    .tile-package:hover {
        background: rgba(139, 92, 246, 0.08) !important;
        border-color: rgba(139, 92, 246, 0.5) !important;
    }
    .tile-cafe:hover {
        background: rgba(6, 182, 212, 0.08) !important;
        border-color: rgba(6, 182, 212, 0.5) !important;
    }
    .tile-cake:hover {
        background: rgba(236, 72, 153, 0.08) !important;
        border-color: rgba(236, 72, 153, 0.5) !important;
    }
    .tile-event:hover {
        background: rgba(249, 115, 22, 0.08) !important;
        border-color: rgba(249, 115, 22, 0.5) !important;
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

    <!-- Business Performance (Primary KPIs) -->
    <div class="row gy-4 mb-28">
        <!-- KPI: Total Revenue -->
        <div class="col-xxl col-lg-4 col-sm-6 col-12">
            <div class="card glass-card kpi-card-revenue h-100 border-0">
                <div class="card-body p-24 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Total Sales / Revenue</span>
                            <div class="w-36-px h-36-px rounded-circle d-flex justify-content-center align-items-center bg-success-50 dark:bg-success-950 text-success-main">
                                <iconify-icon icon="solar:double-alt-arrow-up-bold-duotone" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <span  class="fw-bold text-xl mb-4 text-neutral-900 dark:text-white">AED {{ number_format($stats['total_revenue'], 2) }}</span>
                    </div>
                   
                </div>
            </div>
        </div>

        <!-- KPI: Total Bookings -->
        <div class="col-xxl col-lg-4 col-sm-6 col-12">
            <a href="{{ route('bookings.index') }}" class="card glass-card kpi-card-bookings h-100 border-0 text-decoration-none">
                <div class="card-body p-24 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Total Bookings</span>
                            <div class="w-36-px h-36-px rounded-circle d-flex justify-content-center align-items-center bg-primary-50 dark:bg-primary-950 text-primary-600">
                                <iconify-icon icon="solar:calendar-date-bold-duotone" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <span  class="fw-bold text-xl mb-4 text-neutral-900 dark:text-white">{{ number_format($stats['total_bookings']) }}</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- KPI: Kids Entertained -->
        <div class="col-xxl col-lg-4 col-sm-6 col-12">
            <div class="card glass-card kpi-card-kids h-100 border-0">
                <div class="card-body p-24 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Kids Entertained</span>
                            <div class="w-36-px h-36-px rounded-circle d-flex justify-content-center align-items-center bg-purple-50 dark:bg-purple-950 text-purple-600">
                                <iconify-icon icon="solar:user-bold-duotone" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <span  class="fw-bold text-xl mb-4 text-neutral-900 dark:text-white">{{ number_format($stats['total_kids']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI: Contact Enquiries -->
        <div class="col-xxl col-lg-6 col-sm-6 col-12">
            <a href="{{ route('contact-enquiries.index') }}" class="card glass-card kpi-card-contact h-100 border-0 text-decoration-none">
                <div class="card-body p-24 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Contact Enquiries</span>
                            <div class="w-36-px h-36-px rounded-circle d-flex justify-content-center align-items-center bg-warning-50 dark:bg-warning-950 text-warning-main">
                                <iconify-icon icon="solar:letter-bold-duotone" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <span  class="fw-bold text-xl mb-4 text-neutral-900 dark:text-white">{{ number_format($stats['contact_enquiries_count']) }}</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- KPI: Cake Enquiries -->
        <div class="col-xxl col-lg-6 col-sm-6 col-12">
            <a href="{{ route('cake-enquiries.index') }}" class="card glass-card kpi-card-cake h-100 border-0 text-decoration-none">
                <div class="card-body p-24 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Cake Enquiries</span>
                            <div class="w-36-px h-36-px rounded-circle d-flex justify-content-center align-items-center bg-danger-50 dark:bg-danger-950 text-danger-main">
                                <iconify-icon icon="solar:crown-minimalistic-bold-duotone" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <span  class="fw-bold text-xl mb-4 text-neutral-900 dark:text-white">{{ number_format($stats['cake_enquiries_count']) }}</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- KPI: Rental Enquiries -->
        <div class="col-xxl col-lg-6 col-sm-6 col-12">
            <a href="{{ route('rental-enquiries.index') }}" class="card glass-card kpi-card-rental h-100 border-0 text-decoration-none">
                <div class="card-body p-24 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <span class="text-xs fw-semibold text-secondary-light dark:text-neutral-300">Rental Enquiries</span>
                            <div class="w-36-px h-36-px rounded-circle d-flex justify-content-center align-items-center bg-info-50 dark:bg-info-950 text-info-main">
                                <iconify-icon icon="solar:building-bold-duotone" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                        <span  class="fw-bold text-xl mb-4 text-neutral-900 dark:text-white">{{ number_format($stats['rental_enquiries_count']) }}</span>
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
                        <div class="col-md col-sm-6">
                            <a href="{{ route('bookings.index') }}" class="action-tile tile-bookings p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
                                <iconify-icon icon="solar:calendar-date-bold" class="text-2xl text-indigo-600"></iconify-icon>
                                <span class="fw-semibold text-neutral-900 dark:text-white text-xs d-block">Bookings Overview</span>
                            </a>
                        </div>

                        @can('create_packages')
                        <div class="col-md col-sm-6">
                            <a href="{{ route('packages.create') }}" class="action-tile tile-package p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
                                <iconify-icon icon="solar:gift-bold" class="text-2xl text-primary-600"></iconify-icon>
                                <span class="fw-semibold text-neutral-900 dark:text-white text-xs d-block">Add Package</span>
                            </a>
                        </div>
                        @endcan

                        @can('create_cafe_menus')
                        <div class="col-md col-sm-6">
                            <a href="{{ route('cafe-menus.create') }}" class="action-tile tile-cafe p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
                                <iconify-icon icon="solar:cup-hot-bold" class="text-2xl text-cyan"></iconify-icon>
                                <span class="fw-semibold text-neutral-900 dark:text-white text-xs d-block">New Cafe Item</span>
                            </a>
                        </div>
                        @endcan

                        @can('create_cakes')
                        <div class="col-md col-sm-6">
                            <a href="{{ route('cakes.create') }}" class="action-tile tile-cake p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
                                <iconify-icon icon="solar:crown-minimalistic-bold" class="text-2xl text-success-main"></iconify-icon>
                                <span class="fw-semibold text-neutral-900 dark:text-white text-xs d-block">Add Custom Cake</span>
                            </a>
                        </div>
                        @endcan

                        @can('create_events')
                        <div class="col-md col-sm-6">
                            <a href="{{ route('events.create') }}" class="action-tile tile-event p-16 text-center d-flex flex-column align-items-center gap-2 h-100 justify-content-center text-decoration-none">
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
        <!-- Monthly Sales & Revenue Trends -->
        <div class="col-xxl-12 col-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between mb-24 flex-wrap gap-2">
                        <div>
                            <h6 class="text-lg fw-bold text-neutral-900 dark:text-white mb-4">Monthly Sales & Revenue</h6>
                            <span class="text-xs text-secondary-light dark:text-neutral-400">Revenue analysis for the year {{ $selected_year }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center gap-2">
                                <label for="revenue_chart_year" class="text-xs fw-semibold text-secondary-light dark:text-neutral-300 mb-0 flex-shrink-0">Filter Year:</label>
                                <select name="chart_year" id="revenue_chart_year" class="form-select form-select-sm py-4 px-12 text-xs" style="width: 100px; border-radius: 6px; cursor: pointer;" onchange="this.form.submit()">
                                    @foreach ($available_years as $year)
                                        <option value="{{ $year }}" {{ $year == $selected_year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <span class="badge bg-success-50 text-success-600 dark:bg-success-950 dark:text-success-400 text-2xs px-10 py-6 rounded-pill fw-semibold">AED Sales</span>
                        </div>
                    </div>
                    <div id="revenueTrendsChart" style="min-height: 310px; width: 90%; margin: 0 auto;"></div>
                </div>
            </div>
        </div>

        <!-- Monthly Booking Volume -->
        <div class="col-xxl-12 col-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between mb-24 flex-wrap gap-2">
                        <div>
                            <h6 class="text-lg fw-bold text-neutral-900 dark:text-white mb-4">Monthly Booking Volume</h6>
                            <span class="text-xs text-secondary-light dark:text-neutral-400">Booking count analysis for the year {{ $selected_year }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center gap-2">
                                <label for="bookings_chart_year" class="text-xs fw-semibold text-secondary-light dark:text-neutral-300 mb-0 flex-shrink-0">Filter Year:</label>
                                <select name="chart_year" id="bookings_chart_year" class="form-select form-select-sm py-4 px-12 text-xs" style="width: 100px; border-radius: 6px; cursor: pointer;" onchange="this.form.submit()">
                                    @foreach ($available_years as $year)
                                        <option value="{{ $year }}" {{ $year == $selected_year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <span class="badge bg-primary-50 text-primary-600 dark:bg-primary-950 dark:text-primary-400 text-2xs px-10 py-6 rounded-pill fw-semibold">Bookings</span>
                        </div>
                    </div>
                    <div id="bookingsVolumeChart" style="min-height: 310px; width: 90%; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Paid Bookings -->
    <div class="row gy-4">
        <div class="col-12">
            <div class="card glass-card h-100 border-0">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between mb-24 flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold text-lg text-neutral-900 mb-2">Latest Paid Bookings</h6>
                            <span class="text-xs text-secondary-light">Review the 10 most recent fully paid and confirmed bookings</span>
                        </div>
                        <a href="{{ route('bookings.index') }}" class="btn btn-primary-600 btn-sm py-6 px-16 rounded-8 text-xs">View All Bookings</a>
                    </div>

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table bordered-table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>Ref No.</th>
                                        <th>Customer Details</th>
                                        <th>Venue & Package</th>
                                        <th>Date & Guests</th>
                                        <th>Total Price</th>
                                        <th>Payment Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recent_bookings as $booking)
                                        <tr>
                                            <td>
                                                <a href="{{ route('bookings.show', $booking->id) }}" class="fw-bold text-primary-600 hover-text-primary text-xs">
                                                    {{ $booking->booking_reference }}
                                                </a>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="text-sm mb-0 fw-semibold text-neutral-900">{{ $booking->contact_name }}</h6>
                                                    <span class="text-2xs text-secondary-light d-block">{{ $booking->email }} | {{ $booking->phone }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="text-xs fw-semibold text-neutral-900 d-block">{{ $booking->branch?->name ?? 'Global Venue' }}</span>
                                                    <span class="text-2xs text-secondary-light d-block">{{ $booking->package?->title ?? 'Custom Package' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="text-xs fw-semibold text-neutral-900 d-block">
                                                        {{ $booking->booking_date ? $booking->booking_date->format('d M Y') : 'N/A' }}
                                                    </span>
                                                    <span class="text-2xs text-secondary-light d-block">
                                                        {{ $booking->adult_count }} Adults, {{ $booking->child_count }} Kids
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-neutral-900 text-sm">
                                                    AED {{ number_format($booking->total_amount, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-2xs">PAID</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-primary-600 btn-sm py-4 px-12 rounded-8 text-xs d-inline-flex align-items-center gap-1">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-36 text-secondary-light">No paid bookings registered yet.</td>
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

            // Monthly Sales & Revenue Trends Chart (Area)
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const textColors = isDark ? '#94a3b8' : '#64748b';
            const gridColors = isDark ? '#334155' : '#f1f5f9';

            const trendDates = {!! json_encode(array_column($trends, 'date')) !!};
            const trendCounts = {!! json_encode(array_column($trends, 'count')) !!};
            const trendRevenues = {!! json_encode(array_column($trends, 'revenue')) !!};

            var revenueOptions = {
                series: [{
                    name: 'Revenue (AED)',
                    data: trendRevenues
                }],
                chart: {
                    height: 310,
                    width: '100%',
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    parentHeightOffset: 0
                },
                stroke: {
                    width: 3,
                    curve: 'smooth'
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                labels: trendDates,
                markers: {
                    size: 4
                },
                xaxis: {
                    type: 'category',
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
                    title: {
                        text: 'Revenue (AED)',
                        offsetX: 10,
                        style: {
                            color: '#10b981',
                            fontWeight: 600
                        }
                    },
                    labels: {
                        // minWidth: 80,
                        style: {
                            colors: textColors,
                            fontSize: '11px'
                        },
                        formatter: function(val) {
                            return 'AED ' + val.toLocaleString();
                        }
                    }
                },
                colors: ['#10b981'],
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: function (y) {
                            if (typeof y !== "undefined") {
                                return 'AED ' + y.toLocaleString();
                            }
                            return y;
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
                },
                legend: {
                    show: false
                }
            };

            // Monthly Booking Volume Chart (Rounded Columns)
            var bookingsOptions = {
                series: [{
                    name: 'Bookings Count',
                    data: trendCounts
                }],
                chart: {
                    height: 310,
                    width: '100%',
                    type: 'bar',
                    toolbar: {
                        show: false
                    },
                    parentHeightOffset: 0
                },
                plotOptions: {
                    bar: {
                        columnWidth: '40%',
                        borderRadius: 6
                    }
                },
                fill: {
                    opacity: 0.85
                },
                labels: trendDates,
                xaxis: {
                    type: 'category',
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
                    title: {
                        text: 'Bookings Count',
                        offsetX: 8,
                        style: {
                            color: '#3b82f6',
                            fontWeight: 600
                        }
                    },
                    labels: {
                        // minWidth: 10,
                        style: {
                            colors: textColors,
                            fontSize: '11px'
                        },
                        formatter: function(val) {
                            return val.toFixed(0);
                        }
                    }
                },
                colors: ['#3b82f6'],
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: function (y) {
                            if (typeof y !== "undefined") {
                                return y.toLocaleString() + ' bookings';
                            }
                            return y;
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
                },
                legend: {
                    show: false
                }
            };

            var revenueTrendsChart = new ApexCharts(document.querySelector("#revenueTrendsChart"), revenueOptions);
            revenueTrendsChart.render();

            var bookingsVolumeChart = new ApexCharts(document.querySelector("#bookingsVolumeChart"), bookingsOptions);
            bookingsVolumeChart.render();

            // Update charts colors on theme switch
            const themeBtn = document.querySelector('.theme-customization-sidebar__close');
            if (themeBtn) {
                themeBtn.addEventListener('click', function() {
                    setTimeout(() => {
                        const newDark = document.documentElement.getAttribute('data-theme') === 'dark';
                        const newTextColors = newDark ? '#94a3b8' : '#64748b';
                        const newGridColors = newDark ? '#334155' : '#f1f5f9';

                        revenueTrendsChart.updateOptions({
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

                        bookingsVolumeChart.updateOptions({
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
                    }, 180000);
                });
            }
        });
    </script>
@endsection