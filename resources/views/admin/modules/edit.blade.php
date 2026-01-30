<x-app-layout>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Module: {{ $module->name }}</h2>
                <a href="{{ route('admin.modules.index') }}" class="btn btn-light border">
                    <i class="mdi mdi-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.modules.update', $module) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Module Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $module->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Module Key (Read-only)</label>
                            <input type="text" class="form-control bg-light" value="{{ $module->key }}" readonly disabled>
                            <div class="form-text text-muted small">Keys cannot be changed after creation to maintain system integrity.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Select Icon</label>
                            <div class="icon-picker-tray p-2 border rounded bg-light" style="max-height: 200px; overflow-y: auto;">
                                <div class="row g-2">
                                    @foreach($icons as $icon)
                                        <div class="col-3 text-center">
                                            <div class="icon-option p-2 rounded cursor-pointer border {{ $module->icon === $icon ? 'active' : '' }}" data-icon="{{ $icon }}" style="cursor: pointer;">
                                                <i class="{{ $icon }} fs-3"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" name="icon" id="selected_icon" value="{{ $module->icon }}" required>
                            <div id="icon_display" class="mt-2 text-primary font-weight-bold">
                                Selected: <i id="current_icon" class="{{ $module->icon }}"></i> <span id="icon_name">{{ $module->icon }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $module->description }}</textarea>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary w-100 text-white shadow">
                                <i class="mdi mdi-content-save me-1"></i> Update Module
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
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconOptions = document.querySelectorAll('.icon-option');
            const iconInput = document.getElementById('selected_icon');
            const currentIcon = document.getElementById('current_icon');
            const iconName = document.getElementById('icon_name');

            iconOptions.forEach(option => {
                option.addEventListener('click', function() {
                    iconOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    const iconClass = this.getAttribute('data-icon');
                    iconInput.value = iconClass;
                    
                    currentIcon.className = iconClass;
                    iconName.textContent = iconClass;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
