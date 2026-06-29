<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-4">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>
                {{-- <form class="navbar-search">
                    <input type="text" name="search" placeholder="Search">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form> --}}
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" data-theme-toggle
                    class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                
                <div class="dropdown">
                    <button
                        class="{{ $adminNotificationsCount > 0 ? 'has-indicator' : '' }} w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                        type="button" data-bs-toggle="dropdown" id="notificationBellButton">
                        <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                        <div
                            class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                            </div>
                            <span id="notificationBadge"
                                class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">{{ $adminNotificationsCount }}</span>
                        </div>

                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-4" id="notificationsListContainer">
                            @forelse ($adminNotifications as $notification)
                                <a href="{{ $notification->link ?? 'javascript:void(0)' }}"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span
                                            class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                            <iconify-icon icon="solar:bell-bing-bold-duotone" class="icon text-xxl">
                                            </iconify-icon>
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">{{ $notification->title }}</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-200-px">{{ $notification->message }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-secondary-light flex-shrink-0">{{ $notification->created_at->diffForHumans(null, true, true) }} ago</span>
                                </a>
                            @empty
                                <div class="text-center py-24 text-secondary-light">
                                    <p class="mb-0 text-sm">No new notifications</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="text-center py-12 px-16 border-top d-flex justify-content-between align-items-center gap-2">
                            <a href="{{ route('admin.notifications.index') }}" class="text-primary-600 fw-semibold text-xs">See All Notifications</a>
                            @if ($adminNotificationsCount > 0)
                                <div id="markAllReadWrapper">
                                    <a href="javascript:void(0)" id="markAllReadBtn" class="text-secondary-600 fw-semibold text-xs">Mark All as Read</a>
                                </div>
                            @endif
                        </div>

                    </div>
                </div><!-- Notification dropdown end -->

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const markAllReadBtn = document.getElementById('markAllReadBtn');
                    if (markAllReadBtn) {
                        markAllReadBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            fetch("{{ route('admin.notifications.mark-all-read') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json",
                                    "Accept": "application/json"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const badge = document.getElementById('notificationBadge');
                                    if (badge) badge.textContent = '0';
                                    
                                    const bellBtn = document.getElementById('notificationBellButton');
                                    if (bellBtn) bellBtn.classList.remove('has-indicator');
                                    
                                    const container = document.getElementById('notificationsListContainer');
                                    if (container) {
                                        container.innerHTML = `
                                            <div class="text-center py-24 text-secondary-light">
                                                <p class="mb-0 text-sm">No new notifications</p>
                                            </div>
                                        `;
                                    }
                                    
                                    const wrapper = document.getElementById('markAllReadWrapper');
                                    if (wrapper) {
                                        wrapper.style.display = 'none';
                                    }
                                }
                            })
                            .catch(error => {
                                console.error("Error marking notifications as read:", error);
                            });
                        });
                    }
                });
                </script>

                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button"
                        data-bs-toggle="dropdown">
                        @if (auth()->user()->image)
                            <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="image"
                                class="w-40-px h-40-px object-fit-cover rounded-circle">
                        @else
                            <span class="w-40-px h-40-px bg-primary-50 text-primary-600 rounded-circle d-flex justify-content-center align-items-center fw-semibold text-sm flex-shrink-0 border">
                                {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('') }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div
                            class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ auth()->user()->name }}</h6>
                                <span class="text-secondary-light fw-medium text-sm">{{ auth()->user()->email }}</span>
                            </div>
                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>
                        <ul class="to-top-list">
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="{{ route('admin.profile.edit') }}">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon>
                                    My Profile
                                </a>
                            </li>
                            {{-- <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="email.html">
                                    <iconify-icon icon="tabler:message-check" class="icon text-xl">
                                    </iconify-icon> Inbox
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="company.html">
                                    <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl">
                                    </iconify-icon> Setting
                                </a>
                            </li> --}}
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                                    href="{{ route('logout') }}">
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log
                                    Out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div><!-- Profile dropdown end -->
            </div>
        </div>
    </div>
</div>