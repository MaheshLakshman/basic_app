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
        <li class="{{ request()->routeIs('branches.*') ? 'active' : '' }}">
            <a href="{{ route('branches.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Branches">
                <i class="bi bi-diagram-3 me-2"></i> <span>Branches</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}">
            <a href="{{ route('attendances.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Attendance">
                <i class="bi bi-clock-history me-2"></i> <span>Attendance</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
            <a href="{{ route('services.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Services">
                <i class="bi bi-activity me-2"></i> <span>Services</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('plans.*') ? 'active' : '' }}">
            <a href="{{ route('plans.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Plans">
                <i class="bi bi-journal-text me-2"></i> <span>Plans</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('members.*') ? 'active' : '' }}">
            <a href="{{ route('members.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Members">
                <i class="bi bi-people me-2"></i> <span>Members</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('trainers.*') ? 'active' : '' }}">
            <a href="{{ route('trainers.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Trainers">
                <i class="bi bi-person-badge me-2"></i> <span>Trainers</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
            <a href="{{ route('subscriptions.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Subscriptions">
                <i class="bi bi-card-checklist me-2"></i> <span>Subscriptions</span>
            </a>
        </li>

        <li class="sidebar-label mt-4 mb-2 small fw-bold text-muted px-3 text-uppercase">Price Management</li>
        <li class="{{ request()->routeIs('admin.price_plans.*') ? 'active' : '' }}">
            <a href="{{ route('admin.price_plans.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Price Plans">
                <i class="bi bi-tag me-2"></i> <span>Price Plans</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.member_price_assignments.*') ? 'active' : '' }}">
            <a href="{{ route('admin.member_price_assignments.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Assignments">
                <i class="bi bi-person-check me-2"></i> <span>Assignments</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.account_locks.*') ? 'active' : '' }}">
            <a href="{{ route('admin.account_locks.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Account Locks">
                <i class="bi bi-lock me-2"></i> <span>Account Locks</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.price_adjustments.*') ? 'active' : '' }}">
            <a href="{{ route('admin.price_adjustments.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Adjustments">
                <i class="bi bi-calculator me-2"></i> <span>Adjustments</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
            <a href="{{ route('admin.invoices.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Invoices">
                <i class="bi bi-receipt me-2"></i> <span>Invoices</span>
            </a>
        </li>
    </ul>
</nav>
