<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
       

        @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
        <li class="nav-item nav-category">Administration</li>
        <li class="nav-item {{ Route::is('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">User Management</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.feature-requests.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.feature-requests.index') }}">
                <i class="menu-icon mdi mdi-clipboard-text"></i>
                <span class="menu-title">Module Requests</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.modules.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.modules.index') }}">
                <i class="menu-icon mdi mdi-view-grid-plus"></i>
                <span class="menu-title">Module Management</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.activity-logs.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.activity-logs.index') }}">
                <i class="menu-icon mdi mdi-history"></i>
                <span class="menu-title">Activity Logs</span>
            </a>
        </li>
        @endif

        <li class="nav-item nav-category">Modules</li>

        @foreach(auth()->user()->features()->where('is_module', true)->where('user_features.is_enabled', true)->get() as $module)
        @if(Route::has($module->route_name))
        <li class="nav-item {{ Route::is(str_replace('.index', '.*', $module->route_name)) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route($module->route_name) }}">
                <i class="menu-icon {{ $module->icon }}"></i>
                <span class="menu-title">{{ $module->name }}</span>
            </a>
        </li>
        @endif
        @endforeach


        <li class="nav-item nav-category">System</li>
        
        @if(auth()->user()->hasFeature('module_notifications'))
        <li class="nav-item {{ Route::is('notifications.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('notifications.index') }}">
                <i class="menu-icon mdi mdi-bell"></i>
                <span class="menu-title">Notifications</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->hasFeature('module_audit_logs'))
        <li class="nav-item {{ Route::is('admin.activity-logs.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.activity-logs.index') }}">
                <i class="menu-icon mdi mdi-history"></i>
                <span class="menu-title">Audit Logs</span>
            </a>
        </li>
        @endif

    </ul>
</nav>
