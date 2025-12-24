<nav id="sidebar" class="sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0 text-primary fw-bold">GYM<span class="text-dark">Admin</span></h4>
        <button type="button" class="btn btn-link p-0 text-muted sidebar-toggle" data-bs-toggle="tooltip" title="Toggle Sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('organizations.*') ? 'active' : '' }}">
            <a href="{{ route('organizations.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Organizations">
                <i class="bi bi-building me-2"></i> <span>Organizations</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('languages.*') ? 'active' : '' }}">
            <a href="{{ route('languages.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Languages">
                <i class="bi bi-translate me-2"></i> <span>Languages</span>
            </a>
        </li>

        <li class="sidebar-label mt-4 mb-2 small fw-bold text-muted px-3 text-uppercase">Access Control</li>
        <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Users">
                <i class="bi bi-people me-2"></i> <span>Users</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <a href="{{ route('roles.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Roles">
                <i class="bi bi-shield-check me-2"></i> <span>Roles</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}">
            <a href="{{ route('permissions.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Permissions">
                <i class="bi bi-key me-2"></i> <span>Permissions</span>
            </a>
        </li>
    </ul>
</nav>
