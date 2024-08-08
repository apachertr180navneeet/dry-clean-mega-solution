<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{route('dashboard')}}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <!-- <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg"> -->
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0" />
                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0" />
                </svg>
            </span>
            <img src="{{url('theam/logo/logo.png')}}" style="width: 130px;" class="mt-3">
        </a>

        <!-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a> -->
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 mt-5">
        <li class="menu-item">
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <div data-i18n="Layouts">Dashboard</div>
            </a>
        </li>
        <!-- Page -->
        @if ($userData->hasAnyPermission(['blog-categories-create','blog-categories-list','blog-categories-edit','blog-categories-delete']))
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <div data-i18n="Layouts">Blog Category</div>
            </a>
            <ul class="menu-sub">
                @if ($userData->hasAnyPermission(['blog-categories-create']))
                <li class="menu-item">
                    <a href="{{route('create.category')}}" class="menu-link">
                        <div data-i18n="Content navbar">Add</div>
                    </a>
                </li>
                @endif
                @if ($userData->hasAnyPermission(['blog-categories-list']))
                <li class="menu-item">
                    <a href="{{route('list.category')}}" class="menu-link">
                        <div data-i18n="Collapsed menu">View</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        <!-- Page -->
        @if ($userData->hasAnyPermission(['blog-create','blog-edit','blog-list','blog-delete']))
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <!-- <i class="ti ti-arrow-big-right ti-fade-right"></i> -->
                <div data-i18n="Layouts">Blog</div>
            </a>
            <ul class="menu-sub">
                @if ($userData->hasAnyPermission(['blog-create']))
                <li class="menu-item">
                    <a href="{{route('create.blog')}}" class="menu-link">
                        <div data-i18n="Content navbar">Add</div>
                    </a>
                </li>
                @endif
                @if ($userData->hasAnyPermission(['blog-list']))
                <li class="menu-item">
                    <a href="{{route('list.blog')}}" class="menu-link">
                        <div data-i18n="Collapsed menu">View</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if ($userData->hasAnyPermission(['current-opening-create','current-opening-edit','current-opening-list','current-opening-delete']))
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg> -->
                <!-- <i class="ti ti-arrow-big-right ti-fade-right"></i> -->
                <div data-i18n="Layouts">Current Openings</div>
            </a>
            <ul class="menu-sub">
                @if ($userData->hasAnyPermission(['current-opening-create']))
                <li class="menu-item">
                    <a href="{{route('trainee.accocite.get')}}" class="menu-link">
                        <div data-i18n="Content navbar">Add</div>
                    </a>
                </li>
                @endif
                @if ($userData->hasAnyPermission(['current-opening-list']))
                <li class="menu-item">
                    <a href="{{route('trainee.accocite.list')}}" class="menu-link">
                        <div data-i18n="Collapsed menu">View</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if ($userData->hasAnyPermission(['user-create','user-list','user-edit','user-delete']))
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg> -->
                <!-- <i class="ti ti-arrow-big-right ti-fade-right"></i> -->
                <div data-i18n="Layouts">Users</div>
            </a>
            <ul class="menu-sub">
                @if ($userData->hasAnyPermission(['user-create']))
                <li class="menu-item">
                    <a href="{{route('register.user')}}" class="menu-link">
                        <div data-i18n="Content navbar">Add</div>
                    </a>
                </li>
                @endif
                @if ($userData->hasAnyPermission(['user-list']))
                <li class="menu-item">
                    <a href="{{route('list.register')}}" class="menu-link">
                        <div data-i18n="Collapsed menu">View</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if ($userData->hasAnyPermission(['role-create','role-list','role-edit','role-delete']))
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg> -->
                <!-- <i class="ti ti-arrow-big-right ti-fade-right"></i> -->
                <div data-i18n="Layouts">Role</div>
            </a>
            <ul class="menu-sub">
                @if ($userData->hasAnyPermission(['role-create']))
                <li class="menu-item">
                    <a href="{{route('role.add')}}" class="menu-link">
                        <div data-i18n="Content navbar">Add</div>
                    </a>
                </li>
                @endif
                @if ($userData->hasAnyPermission(['role-list']))
                <li class="menu-item">
                    <a href="{{route('role.list')}}" class="menu-link">
                        <div data-i18n="Collapsed menu">View</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg> -->
                <!-- <i class="ti ti-arrow-big-right ti-fade-right"></i> -->
                <div data-i18n="Layouts">Permission SetUp</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{route('permission.add')}}" class="menu-link">
                        <div data-i18n="Content navbar">add</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('permission.list')}}" class="menu-link">
                        <div data-i18n="Collapsed menu">View</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                <i class="ti ti-sm"></i>
            </a>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{url('theam/assets/img/avatars/1.png')}}" alt class="h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{url('theam/assets/img/avatars/1.png')}}" alt class="h-auto rounded-circle" />
                                    </div>
                                </div>
                                @php
                                $loginname = Auth::user()->name;
                                @endphp
                                <div class="flex-grow-1 mt-3">
                                    <span class="fw-semibold d-block">{{ !empty($loginname) ? $loginname : '' }}</span>
                                    <!-- <small class="text-muted">Admin</small> -->
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('myProfile')}}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('change.password')}}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">Change Password</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('logout')}}">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
