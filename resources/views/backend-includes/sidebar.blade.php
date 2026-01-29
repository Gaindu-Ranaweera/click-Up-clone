<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
        <li class="nav-item nav-category">Administration</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">User Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.feature-requests.index') }}">
                <i class="menu-icon mdi mdi-bell-question"></i>
                <span class="menu-title">Module Requests</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.activity-logs.index') }}">
                <i class="menu-icon mdi mdi-history"></i>
                <span class="menu-title">Activity Logs</span>
            </a>
        </li>
        @endif

        <li class="nav-item nav-category">Modules</li>

        @if(auth()->user()->hasFeature('module_projects'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('projects.index') }}">
                <i class="menu-icon mdi mdi-briefcase"></i>
                <span class="menu-title">Projects</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasFeature('module_client_coordination'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('client-coordination.index') }}">
                <i class="menu-icon mdi mdi-account-group"></i>
                <span class="menu-title">Client Coordination</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasFeature('module_clients'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('clients.index') }}">
                <i class="menu-icon mdi mdi-account-group"></i>
                <span class="menu-title">Clients</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasFeature('module_hr'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('hr.index') }}">
                <i class="menu-icon mdi mdi-account-card-details"></i>
                <span class="menu-title">HR Management</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasFeature('module_payroll'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('payroll.index') }}">
                <i class="menu-icon mdi mdi-cash-multiple"></i>
                <span class="menu-title">Salary & Payroll</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasFeature('module_finance'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('finance.index') }}">
                <i class="menu-icon mdi mdi-chart-line"></i>
                <span class="menu-title">Finance & Revenue</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasFeature('module_notifications'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('notifications.index') }}">
                <i class="menu-icon mdi mdi-bell"></i>
                <span class="menu-title">Notifications</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasFeature('module_audit_logs'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('audit-logs.index') }}">
                <i class="menu-icon mdi mdi-history"></i>
                <span class="menu-title">Audit Logs</span>
            </a>
        </li>
        @endif
    </ul>
</nav>
