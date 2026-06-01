@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Dashboard</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <span class="text-secondary-light">System Overview</span>
            </li>
        </ul>
    </div>

    <!-- Stats Cards Row -->
    <div class="row row-cols-xxl-6 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">
        <!-- Birthday Packages -->
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-1 h-100">
                <div class="card-body p-20">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Birthday Packages</p>
                            <h6 class="mb-0">{{ number_format($stats['birthday_package_count']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:gift-bold" class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0">
                        Active packages catalog
                    </p>
                </div>
            </div>
        </div>

        <!-- Events -->
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-2 h-100">
                <div class="card-body p-20">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Events & Parties</p>
                            <h6 class="mb-0">{{ number_format($stats['event_count']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:clapperboard-play-bold" class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0">
                        Organized events
                    </p>
                </div>
            </div>
        </div>

        <!-- Cafe Menus -->
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-3 h-100">
                <div class="card-body p-20">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Cafe Items</p>
                            <h6 class="mb-0">{{ number_format($stats['cafe_menu_count']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:cup-hot-bold" class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0">
                        Dishes & drinks
                    </p>
                </div>
            </div>
        </div>

        <!-- Cakes -->
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-4 h-100">
                <div class="card-body p-20">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Cake Designs</p>
                            <h6 class="mb-0">{{ number_format($stats['cake_count']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:crown-minimalistic-bold" class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0">
                        Custom cake models
                    </p>
                </div>
            </div>
        </div>

        <!-- Rental Items -->
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-5 h-100">
                <div class="card-body p-20">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Rental Items</p>
                            <h6 class="mb-0">{{ number_format($stats['rental_item_count']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-red rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:box-bold" class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0">
                        Party rentals & decor
                    </p>
                </div>
            </div>
        </div>

        <!-- Branches -->
        <div class="col">
            <div class="card shadow-none border bg-gradient-start-1 h-100">
                <div class="card-body p-20">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Active Branches</p>
                            <h6 class="mb-0">{{ number_format($stats['branch_count']) }}</h6>
                        </div>
                        <div class="w-50-px h-50-px bg-orange rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:map-point-bold" class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="fw-medium text-sm text-primary-light mt-12 mb-0">
                        Locations & parks
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="row gy-4 mt-4">
        <!-- Catalog Distribution Chart -->
        <div class="col-xxl-6 col-xl-12">
            <div class="card h-100 radius-8 border">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <h6 class="text-lg mb-0">Catalog Asset Breakdown</h6>
                        <span class="text-xs fw-semibold text-secondary-light">System Distribution</span>
                    </div>
                    <div id="catalogDistributionChart" class="pt-28"></div>
                </div>
            </div>
        </div>

        <!-- Gallery & Support Metrics -->
        <div class="col-xxl-6 col-xl-12">
            <div class="card h-100 radius-8 border">
                <div class="card-body p-24">
                    <h6 class="mb-2 fw-bold text-lg">System Metrics Quick-Glance</h6>
                    <p class="text-sm text-secondary-light mb-24">Overview of supportive settings and multimedia counts</p>
                    
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-16 border rounded bg-base d-flex align-items-center gap-3">
                                <div class="w-40-px h-40-px rounded bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="solar:camera-bold" class="text-xl"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $stats['gallery_count'] }}</h6>
                                    <span class="text-xs text-secondary-light">Gallery Assets</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-16 border rounded bg-base d-flex align-items-center gap-3">
                                <div class="w-40-px h-40-px rounded bg-success-50 text-success-main d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="solar:chat-square-like-bold" class="text-xl"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $stats['testimonial_count'] }}</h6>
                                    <span class="text-xs text-secondary-light">Customer Testimonials</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-16 border rounded bg-base d-flex align-items-center gap-3">
                                <div class="w-40-px h-40-px rounded bg-warning-50 text-warning-main d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="solar:question-square-bold" class="text-xl"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $stats['faq_count'] }}</h6>
                                    <span class="text-xs text-secondary-light">FAQ Entries</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-16 border rounded bg-base d-flex align-items-center gap-3">
                                <div class="w-40-px h-40-px rounded bg-danger-50 text-danger-main d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="solar:gallery-bold" class="text-xl"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $stats['banner_count'] }}</h6>
                                    <span class="text-xs text-secondary-light">Homepage Banners</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-24 p-16 bg-neutral-50 rounded border dark:bg-neutral-900">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <iconify-icon icon="solar:settings-bold" class="text-xl text-primary-600"></iconify-icon>
                                <span class="fw-medium text-primary-light">System Control Room</span>
                            </div>
                            <a href="{{ route('general-settings.edit') }}" class="btn btn-primary-600 btn-sm py-6 px-12">Edit Settings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables & Team Section -->
    <div class="row gy-4 mt-4">
        <!-- Recent Catalog Assets Tabs -->
        <div class="col-xxl-8 col-xl-12">
            <div class="card h-100 radius-8 border">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-16 gap-3">
                        <ul class="nav border-gradient-tab nav-pills mb-0" id="recentAssetsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center" id="birthday-tab" data-bs-toggle="pill" data-bs-target="#birthday-panel" type="button" role="tab" aria-controls="birthday-panel" aria-selected="true">
                                    Birthday Packages
                                    <span class="badge bg-neutral-500 rounded-pill text-white ms-8">{{ count($recent_birthday_packages) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="cafe-tab" data-bs-toggle="pill" data-bs-target="#cafe-panel" type="button" role="tab" aria-controls="cafe-panel" aria-selected="false">
                                    Cafe Menu
                                    <span class="badge bg-neutral-500 rounded-pill text-white ms-8">{{ count($recent_cafe_menus) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="events-tab" data-bs-toggle="pill" data-bs-target="#events-panel" type="button" role="tab" aria-controls="events-panel" aria-selected="false">
                                    Events
                                    <span class="badge bg-neutral-500 rounded-pill text-white ms-8">{{ count($recent_events) }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="recentAssetsTabContent">
                        <!-- Birthday Packages Panel -->
                        <div class="tab-pane fade show active" id="birthday-panel" role="tabpanel" aria-labelledby="birthday-tab">
                            <div class="table-responsive">
                                <table class="table bordered-table sm-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Branch</th>
                                            <th>Price</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recent_birthday_packages as $package)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($package->image)
                                                            <img src="{{ asset('storage/' . $package->image) }}" alt="" class="w-40-px h-40-px rounded flex-shrink-0 me-12 object-fit-cover">
                                                        @else
                                                            <div class="w-40-px h-40-px bg-neutral-100 rounded d-flex justify-content-center align-items-center me-12 flex-shrink-0">
                                                                <iconify-icon icon="solar:gift-bold" class="text-secondary-light"></iconify-icon>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="text-md mb-0 fw-medium">{{ $package->title }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $package->branch?->name ?? 'Global' }}</td>
                                                <td><span class="fw-semibold text-primary-600">{{ $package->price ? 'AED ' . $package->price : 'N/A' }}</span></td>
                                                <td class="text-center">
                                                    @if($package->status)
                                                        <span class="bg-success-focus text-success-main px-16 py-4 rounded-pill fw-medium text-xs">Active</span>
                                                    @else
                                                        <span class="bg-neutral-100 text-neutral-600 px-16 py-4 rounded-pill fw-medium text-xs">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-24 text-secondary-light">No birthday packages registered yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Cafe Panel -->
                        <div class="tab-pane fade" id="cafe-panel" role="tabpanel" aria-labelledby="cafe-tab">
                            <div class="table-responsive">
                                <table class="table bordered-table sm-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Dish / Drink</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recent_cafe_menus as $menu)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($menu->image)
                                                            <img src="{{ asset('storage/' . $menu->image) }}" alt="" class="w-40-px h-40-px rounded flex-shrink-0 me-12 object-fit-cover">
                                                        @else
                                                            <div class="w-40-px h-40-px bg-neutral-100 rounded d-flex justify-content-center align-items-center me-12 flex-shrink-0">
                                                                <iconify-icon icon="solar:cup-hot-bold" class="text-secondary-light"></iconify-icon>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="text-md mb-0 fw-medium">{{ $menu->title }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $menu->category?->name ?? 'General' }}</td>
                                                <td><span class="fw-semibold text-primary-600">{{ $menu->price ? 'AED ' . $menu->price : 'N/A' }}</span></td>
                                                <td class="text-center">
                                                    @if($menu->status)
                                                        <span class="bg-success-focus text-success-main px-16 py-4 rounded-pill fw-medium text-xs">Active</span>
                                                    @else
                                                        <span class="bg-neutral-100 text-neutral-600 px-16 py-4 rounded-pill fw-medium text-xs">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-24 text-secondary-light">No cafe items registered yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Events Panel -->
                        <div class="tab-pane fade" id="events-panel" role="tabpanel" aria-labelledby="events-tab">
                            <div class="table-responsive">
                                <table class="table bordered-table sm-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Event Name</th>
                                            <th>Created At</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recent_events as $event)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($event->image)
                                                            <img src="{{ asset('storage/' . $event->image) }}" alt="" class="w-40-px h-40-px rounded flex-shrink-0 me-12 object-fit-cover">
                                                        @else
                                                            <div class="w-40-px h-40-px bg-neutral-100 rounded d-flex justify-content-center align-items-center me-12 flex-shrink-0">
                                                                <iconify-icon icon="solar:clapperboard-play-bold" class="text-secondary-light"></iconify-icon>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="text-md mb-0 fw-medium">{{ $event->title }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $event->created_at?->format('d M Y') ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    @if($event->status)
                                                        <span class="bg-success-focus text-success-main px-16 py-4 rounded-pill fw-medium text-xs">Active</span>
                                                    @else
                                                        <span class="bg-neutral-100 text-neutral-600 px-16 py-4 rounded-pill fw-medium text-xs">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-24 text-secondary-light">No events registered yet.</td>
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
            <div class="card h-100 radius-8 border">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center justify-content-between mb-20">
                        <h6 class="fw-bold text-lg mb-0">Active Team Members</h6>
                        <a href="{{ route('staffs.index') }}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1 text-sm fw-medium">
                            Manage Staff
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>

                    <div class="d-flex flex-column gap-20">
                        @forelse ($staff_members as $member)
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center">
                                    <div class="w-40-px h-40-px bg-primary-50 rounded-circle d-flex justify-content-center align-items-center me-12 flex-shrink-0">
                                        <iconify-icon icon="solar:user-bold" class="text-primary-600 text-xl"></iconify-icon>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-medium">{{ $member->name }}</h6>
                                        <span class="text-xs text-secondary-light">{{ $member->email }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-primary-50 text-primary-600 text-xs px-12 py-6 rounded-pill">
                                    {{ $member->roles->first()?->name ?? 'Staff' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-secondary-light py-24">No registered staff found.</p>
                        @endforelse
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
            var options = {
                series: [{
                    name: 'Items Count',
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
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        horizontal: false,
                        columnWidth: '45%',
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
                    categories: ['Birthday Packages', 'Events', 'Cafe Menu', 'Cakes', 'Rental Items'],
                    labels: {
                        style: {
                            colors: '#888',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Record Counts',
                        style: {
                            color: '#888'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#888'
                        }
                    }
                },
                fill: {
                    opacity: 1,
                    colors: ['#2563eb']
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " records"
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                }
            };

            var chart = new ApexCharts(document.querySelector("#catalogDistributionChart"), options);
            chart.render();
        });
    </script>
@endsection