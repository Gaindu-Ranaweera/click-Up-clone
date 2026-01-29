<div class="bg-dark text-white" id="sidebar-wrapper" style="min-height: 100vh; width: 250px;">
    <div class="sidebar-heading p-3 border-bottom border-secondary">
        <h5 class="mb-0">{{ config('app.name') }}</h5>
        <small class="text-muted">{{ auth()->user()->role->name ?? 'User' }}</small>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        @if(auth()->user()->hasFeature('module_projects'))
        <a href="{{ route('projects.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-kanban me-2"></i> Projects
        </a>
        @endif

        @if(auth()->user()->hasFeature('module_clients'))
        <a href="{{ route('clients.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-people me-2"></i> Clients
        </a>
        @endif

        @if(auth()->user()->hasFeature('module_hr'))
        <a href="{{ route('hr.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-person-badge me-2"></i> HR
        </a>
        @endif

        @if(auth()->user()->hasFeature('module_payroll'))
        <a href="{{ route('payroll.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-cash-coin me-2"></i> Payroll
        </a>
        @endif

        @if(auth()->user()->hasFeature('module_finance'))
        <a href="{{ route('finance.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-graph-up me-2"></i> Finance
        </a>
        @endif

        @if(auth()->user()->hasFeature('module_notifications'))
        <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-bell me-2"></i> Notifications
        </a>
        @endif

        @if(auth()->user()->hasFeature('module_audit_logs'))
        <a href="{{ route('audit-logs.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-clipboard-data me-2"></i> Audit Logs
        </a>
        @endif

        @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
        <hr class="border-secondary my-2">
        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-secondary">
            <i class="bi bi-gear me-2"></i> User Management
        </a>
        @endif
    </div>
</div>
