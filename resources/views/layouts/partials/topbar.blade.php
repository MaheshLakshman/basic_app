<nav class="navbar navbar-expand-lg border-bottom px-3">
    <div class="container-fluid">
        <button type="button" class="btn btn-primary d-lg-none sidebar-toggle">
            <i class="bi bi-list"></i>
        </button>
        <div class="ms-auto d-flex align-items-center">
            <!-- Theme Toggle -->
            <button type="button" class="btn btn-link text-muted me-2" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Light/Dark Mode">
                <i class="bi bi-moon-stars fs-5"></i>
            </button>

            <!-- Notifications -->
            <div class="dropdown me-3">
                <a class="nav-link text-muted position-relative" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.25rem 0.4rem;">
                        3
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-0" style="width: 300px;">
                    <li><div class="p-3 border-bottom fw-bold">Notifications</div></li>
                    <li>
                        <a class="dropdown-item p-3 d-flex align-items-start border-bottom" href="#">
                            <div class="bg-primary text-white rounded-circle p-2 me-3">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div>
                                <div class="small fw-bold">New user registered</div>
                                <div class="text-muted small">2 minutes ago</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item p-3 d-flex align-items-start border-bottom" href="#">
                            <div class="bg-success text-white rounded-circle p-2 me-3">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <div class="small fw-bold">Organization updated</div>
                                <div class="text-muted small">1 hour ago</div>
                            </div>
                        </a>
                    </li>
                    <li><a class="dropdown-item p-2 text-center small text-primary" href="#">View all notifications</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <div class="avatar-sm me-2 d-none d-sm-block">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=667eea&color=fff" class="rounded-circle" width="32" height="32" alt="{{ auth()->user()->name }}">
                    </div>
                    <span>{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><h6 class="dropdown-header">Manage Account</h6></li>
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
