<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <img src="{{ $generalSettings?->logo ? asset('storage/' . $generalSettings->logo) : asset('assets/images/logo.png') }}"
                alt="{{ $generalSettings?->site_name ?: 'site logo' }}" class="light-logo">
            <img src="{{ $generalSettings?->logo ? asset('storage/' . $generalSettings->logo) : asset('assets/images/logo.png') }}"
                alt="{{ $generalSettings?->site_name ?: 'site logo' }}" class="dark-logo">
            <img src="{{ $generalSettings?->logo ? asset('storage/' . $generalSettings->logo) : asset('assets/images/logo.png') }}"
                alt="{{ $generalSettings?->site_name ?: 'site logo' }}" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            @can('view_branches')
            <li class="nav-item">
                <a href="{{ route('branches.index') }}"
                class="nav-link">
                    <i class="ri-map-pin-line text-xl me-14 d-flex w-auto"></i>
                    <span>Branches</span>
                </a>
            </li>
            @endcan

            @can('view_cakes')
            <li>
                <a href="{{ route('cakes.index') }}">
                    <iconify-icon
                        icon="mdi:cake-variant" class="menu-icon">
                    </iconify-icon>
                    Cakes
                </a>
            </li>
            @endcan

            <li class="nav-item dropdown">
                @php
                    $cafeMenuPermission =
                        auth()->user()->can('view_cafe_menu_categories') ||
                        auth()->user()->can('view_cafe_menus');
                @endphp

                @if($cafeMenuPermission)
                    <a href="javascript:void(0)"
                        class="nav-link dropdown-toggle {{ request()->routeIs('cafe-menu-categories.*') || request()->routeIs('cafe-menus.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#cafeMenu"
                        aria-expanded="false">
                        <i class="ri-restaurant-2-line text-xl me-14 d-flex w-auto"></i>
                        <span>Cafe Menu</span>
                    </a>

                    <ul class="collapse submenu {{ request()->routeIs('cafe-menu-categories.*') || request()->routeIs('cafe-menus.*') ? 'show' : '' }}"
                        id="cafeMenu">
                        @can('view_cafe_menu_categories')
                            <li class="submenu-item">
                                <a href="{{ route('cafe-menu-categories.index') }}">
                                    <i class="ri-folder-line me-2"></i>
                                    Categories
                                </a>
                            </li>
                        @endcan

                        @can('view_cafe_menus')
                            <li class="submenu-item">
                                <a href="{{ route('cafe-menus.index') }}">
                                    <i class="ri-restaurant-line me-2"></i>
                                    Menus
                                </a>
                            </li>
                        @endcan
                    </ul>
                @endif
            </li>

            <li class="nav-item dropdown">
                @php
                    $RentalPermission =
                        auth()->user()->can('view_rental_categories') ||
                        auth()->user()->can('view_rental_items');
                @endphp

                @if($RentalPermission)
                    <a href="javascript:void(0)"
                        class="nav-link dropdown-toggle {{ request()->routeIs('rental-categories.*') || request()->routeIs('rental-items.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#rentals"
                        aria-expanded="false">
                        <i class="ri-building-4-line text-xl me-14 d-flex w-auto"></i>
                        <span>Rentals</span>
                    </a>

                    <ul class="collapse submenu {{ request()->routeIs('rental-categories.*') || request()->routeIs('rental-items.*') ? 'show' : '' }}"
                        id="rentals">
                        @can('view_rental_categories')
                            <li class="submenu-item">
                                <a href="{{ route('rental-categories.index') }}">
                                    <i class="ri-folder-2-line me-2"></i>
                                    Categories
                                </a>
                            </li>
                        @endcan

                        @can('view_rental_items')
                            <li class="submenu-item">
                                <a href="{{ route('rental-items.index') }}">
                                    <i class="ri-home-gear-line me-2"></i>
                                    Items
                                </a>
                            </li>
                        @endcan
                    </ul>
                @endif
            </li>

            <li class="nav-item dropdown">
                @php
                    $birthdayPackagePermission =
                        auth()->user()->can('view_balloon_decorations') ||
                        auth()->user()->can('view_birthday_packages');
                @endphp

                @if($birthdayPackagePermission)
                    <a href="javascript:void(0)"
                        class="nav-link dropdown-toggle {{ request()->routeIs('balloon-decorations.*') || request()->routeIs('birthday-packages.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#birthdayPackagesMenu"
                        aria-expanded="false">
                        {{-- MAIN ICON --}}
                        <i class="ri-cake-3-line text-xl me-14 d-flex w-auto"></i>
                        <span>Birthday Party</span>
                    </a>

                    <ul class="collapse submenu {{ request()->routeIs('balloon-decorations.*') || request()->routeIs('birthday-packages.*') ? 'show' : '' }}"
                        id="birthdayPackagesMenu">
                        @can('view_balloon_decorations')
                            <li class="submenu-item">
                                <a href="{{ route('balloon-decorations.index') }}">
                                    {{-- BALLOON ICON --}}
                                    <i class="ri-bubble-chart-line me-2"></i>
                                    Balloon Decorations
                                </a>
                            </li>
                        @endcan

                        @can('view_birthday_packages')
                            <li class="submenu-item">
                                <a href="{{ route('birthday-packages.index') }}">
                                    {{-- PACKAGE ICON --}}
                                    <i class="ri-gift-2-line me-2"></i>
                                    Packages
                                </a>
                            </li>
                        @endcan
                        @can('view_food_menus')
                            <li class="submenu-item">
                                <a href="{{ route('food-menus.index') }}">
                                    <i class="ri-restaurant-line text-xl me-14 d-flex w-auto"></i>
                                    <span>Food Menus</span>
                                </a>
                            </li>
                        @endcan
                        @can('view_party_extras')
                            <li class="submenu-item">
                                <a href="{{ route('party-extras.index') }}">
                                    <i class="ri-magic-line text-xl me-14 d-flex w-auto"></i>
                                    <span>Party Extras</span>
                                </a>
                            </li>
                        @endcan
            
                    </ul>
                @endif
            </li>
            @can('view_events')
                <li>
                    <a href="{{ route('events.index') }}">
                        <i class="ri-question-answer-line text-xl me-14 d-flex w-auto"></i>
                        <span>Events</span>
                    </a>
                </li>
            @endcan

         
            @can('view_general_access')
                <li>
                    <a href="{{ route('general-access.index') }}">
                        <i class="ri-ticket-2-line text-xl me-14 d-flex w-auto"></i>
                        <span>General Access</span>
                    </a>
                </li>
            @endcan

            @can('view_packages')
                <li>
                    <a href="{{ route('packages.index') }}">
                        <i class="ri-gift-line text-xl me-14 d-flex w-auto"></i>
                        <span>Packages</span>
                    </a>
                </li>
            @endcan

            @if(auth()->user()->can('view_bookings'))
                <li>
                    <a href="{{ route('bookings.index') }}">
                        <i class="ri-calendar-check-line text-xl me-14 d-flex w-auto"></i>
                        <span>Bookings</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->can('view_news_updates'))
                <li>
                    <a href="{{ route('news-updates.index') }}">
                        <i class="ri-newspaper-line text-xl me-14 d-flex w-auto"></i>
                        <span>News & Updates</span>
                    </a>
                </li>
            @endif
            @can('view_contact_enquiries')
                <li>
                    <a href="{{ route('contact-enquiries.index') }}" class="{{ request()->routeIs('contact-enquiries.*') ? 'active-page' : '' }}">
                        <i class="ri-mail-line text-xl me-14 d-flex w-auto"></i>
                        <span>Contact Enquiries</span>
                    </a>
                </li>
            @endcan

            @can('view_newsletter_subscriptions')
                <li>
                    <a href="{{ route('newsletter-subscriptions.index') }}" class="{{ request()->routeIs('newsletter-subscriptions.*') ? 'active-page' : '' }}">
                        <i class="ri-mail-open-line text-xl me-14 d-flex w-auto"></i>
                        <span>Newsletter Subscribers</span>
                    </a>
                </li>
            @endcan

            @can('view_attractions')
                <li>
                    <a href="{{ route('attractions.index') }}" class="{{ request()->routeIs('attractions.*') ? 'active-page' : '' }}">
                        <i class="ri-water-flash-line text-xl me-14 d-flex w-auto"></i>
                        <span>Waterpark Attractions & Adventures</span>
                    </a>
                </li>
            @endcan
            
            <li class="sidebar-menu-group-title">Settings</li>
            @can('view_general_settings')
                <li>
                    <a href="{{ route('general-settings.edit') }}">
                        <i class="ri-settings-3-line text-xl me-14 d-flex w-auto"></i>
                        <span>General Settings</span>
                    </a>
                </li>
            @endcan
            @can('view_header_menus')
                <li>
                    <a href="{{ route('header-menus.index') }}" class="{{ request()->routeIs('header-menus.*') ? 'active-page' : '' }}">
                        <i class="ri-menu-line text-xl me-14 d-flex w-auto"></i>
                        <span>Header Menu</span>
                    </a>
                </li>
            @endcan
            @can('view_pages')
                @php
                    $footerPage = \App\Models\Page::where('slug', 'footer')->first();
                @endphp
                @if($footerPage)
                    <li>
                        <a href="{{ route('pages.edit', $footerPage->id) }}" class="{{ request()->is("admin/pages/{$footerPage->id}/edit") ? 'active-page' : '' }}">
                            <i class="ri-layout-bottom-line text-xl me-14 d-flex w-auto"></i>
                            <span>Footer Menu</span>
                        </a>
                    </li>
                @endif
            @endcan
            @can('view_pages')

                <li>
                    <a href="{{ route('pages.index') }}" class="{{ request()->routeIs('pages.*') && !($footerPage && request()->is("admin/pages/{$footerPage->id}*")) ? 'active-page' : '' }}">
                        <i class="ri-pages-line text-xl me-14 d-flex w-auto"></i>
                        <span>Page Management</span>
                    </a>
                </li>
            @endcan
            @can('view_banners')
                <li>
                    <a href="{{ route('banners.index') }}">
                        <i class="ri-image-line text-xl me-14 d-flex w-auto"></i>
                        <span>Banners</span>
                    </a>
                </li>
            @endcan
            @can('view_client_logos')
                <li>
                    <a href="{{ route('client-logos.index') }}">
                        <i class="ri-layout-grid-line text-xl me-14 d-flex w-auto"></i>
                        <span>Client Logos</span>
                    </a>
                </li>
            @endcan

            @can('view_faqs')
                <li>
                    <a href="{{ route('faqs.index') }}">
                        <i class="ri-question-answer-line text-xl me-14 d-flex w-auto"></i>
                        <span>FAQs</span>
                    </a>
                </li>
            @endcan

            @can('view_testimonials')
                <li>
                    <a href="{{ route('testimonials.index') }}">
                        <i class="ri-chat-quote-line text-xl me-14 d-flex w-auto"></i>
                        <span>Testimonials</span>
                    </a>
                </li>
            @endcan

            <li class="nav-item dropdown">

                @php
                    $galleryPermission =
                        auth()->user()->can('view_image_gallery') ||
                        auth()->user()->can('view_video_gallery') ||
                        auth()->user()->can('view_outdoor_events');
                @endphp

                @if($galleryPermission)

                    <a href="javascript:void(0)"
                        class="nav-link dropdown-toggle {{ request()->routeIs('image-gallery.*') || request()->routeIs('video-gallery.*') || request()->routeIs('outdoor-events.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#galleryMenu"
                        aria-expanded="false">

                        <i class="ri-image-2-line text-xl me-14 d-flex w-auto"></i>

                        <span>Gallery</span>

                    </a>

                    <ul class="collapse submenu {{ request()->routeIs('image-gallery.*') || request()->routeIs('video-gallery.*') || request()->routeIs('outdoor-events.*') ? 'show' : '' }}"
                        id="galleryMenu">

                        @can('view_image_gallery')

                            <li class="submenu-item">

                                <a href="{{ route('image-gallery.index') }}">

                                    <i class="ri-image-line me-2"></i>

                                    Image Gallery

                                </a>

                            </li>

                        @endcan

                        @can('view_video_gallery')

                            <li class="submenu-item">

                                <a href="{{ route('video-gallery.index') }}">

                                    <i class="ri-video-line me-2"></i>

                                    Video Gallery

                                </a>

                            </li>

                        @endcan

                        @can('view_outdoor_events')

                            <li class="submenu-item">

                                <a href="{{ route('outdoor-events.index') }}">

                                    <i class="ri-landscape-line me-2"></i>

                                    Outdoor Events

                                </a>

                            </li>

                        @endcan

                    </ul>

                @endif

            </li>

            <li class="sidebar-menu-group-title">Staff Management</li>
            @can('view_staff')
                <li>
                    <a href="{{ route('staffs.index') }}">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Staffs</span>
                    </a>
                </li>
            @endcan
            @can('roles.view')
                <li>
                    <a href="{{ route('roles.index') }}">
                        <i class="ri-user-settings-line text-xl me-14 d-flex w-auto"></i>
                        <span>Roles & Permissions</span>
                    </a>
                </li>
            @endcan


            {{-- <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Invoice</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="invoice-list.html"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            List</a>
                    </li>
                    <li>
                        <a href="invoice-preview.html"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                            Preview</a>
                    </li>
                    <li>
                        <a href="invoice-add.html"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add
                            new</a>
                    </li>
                    <li>
                        <a href="invoice-edit.html"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                            Edit</a>
                    </li>
                </ul>
            </li> --}}


        </ul>
    </div>
</aside>
