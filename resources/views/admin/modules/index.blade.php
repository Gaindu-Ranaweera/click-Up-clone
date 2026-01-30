<x-app-layout>
    <div class="row">
        <!-- List Modules -->
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Module Management</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Module Name</th>
                                    <th>Key</th>
                                    <th>Route</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($modules as $module)
                                <tr>
                                    <td>
                                        <div class="p-2 rounded bg-light d-inline-block text-primary">
                                            <i class="{{ $module->icon }} fs-4"></i>
                                        </div>
                                    </td>
                                    <td><strong>{{ $module->name }}</strong></td>
                                    <td><code>{{ $module->key }}</code></td>
                                    <td><small>{{ $module->route_name }}</small></td>
                                    <td>
                                        <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-warning btn-sm text-white">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.modules.destroy', $module) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm text-white" onclick="return confirm('Note: This will remove the module record and sidebar link. View files will NOT be deleted. Proceed?')">
                                                <i class="mdi mdi-delete-outline"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No functional modules registered yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Module Form -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Create New Module</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.modules.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Module Name</label>
                            <input type="text" name="name" id="module_name" class="form-control" placeholder="e.g. Inventory Management" required>
                            <div class="form-text text-muted">Suggested Key: <code id="suggested_key">module_...</code></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Select Icon</label>
                            <div class="icon-picker-tray p-2 border rounded bg-light" style="max-height: 200px; overflow-y: auto;">
                                <div class="row g-2">
                                    @foreach($icons as $icon)
                                        <div class="col-3 text-center">
                                            <div class="icon-option p-2 rounded cursor-pointer border" data-icon="{{ $icon }}" style="cursor: pointer;">
                                                <i class="{{ $icon }} fs-3"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" name="icon" id="selected_icon" required>
                            <div id="icon_display" class="mt-2 text-primary font-weight-bold d-none">
                                Selected: <i id="current_icon"></i> <span id="icon_name"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="What does this module do?"></textarea>
                        </div>

                        <div class="mt-4 border-top pt-3">
                            <p class="text-info small mb-3">
                                <i class="mdi mdi-information-outline"></i> 
                                This will automatically create your view folder and standard blade files.
                            </p>
                            <button type="submit" class="btn btn-primary w-100 text-white shadow">
                                <i class="mdi mdi-rocket-launch me-1"></i> Launch Module
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .icon-option:hover {
            background-color: #f0f0f0;
            border-color: #0d6efd !important;
        }
        .icon-option.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd !important;
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('module_name');
            const keyDisplay = document.getElementById('suggested_key');
            const iconOptions = document.querySelectorAll('.icon-option');
            const iconInput = document.getElementById('selected_icon');
            const iconDisplay = document.getElementById('icon_display');
            const currentIcon = document.getElementById('current_icon');
            const iconName = document.getElementById('icon_name');

            // Slug generator
            nameInput.addEventListener('input', function() {
                const slug = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '_')
                    .replace(/^-+|-+$/g, '');
                keyDisplay.textContent = slug ? 'module_' + slug : 'module_...';
            });

            // Icon picker
            iconOptions.forEach(option => {
                option.addEventListener('click', function() {
                    iconOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    const iconClass = this.getAttribute('data-icon');
                    iconInput.value = iconClass;
                    
                    iconDisplay.classList.remove('d-none');
                    currentIcon.className = iconClass;
                    iconName.textContent = iconClass;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
