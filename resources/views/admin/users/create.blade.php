<x-app-layout>
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary"><i class="mdi mdi-account-plus me-2"></i>Create New User</h4>
                    <p class="card-description"> Register a new user and assign roles and module access. </p>
                    
                    <form action="{{ route('admin.users.store') }}" method="POST" class="forms-sample">
                        @csrf
                        
                        <!-- Name -->
                        <div class="form-group row mb-4">
                            <label for="name" class="col-sm-3 col-form-label fw-bold">Full Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="John Doe" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row mb-4">
                            <label for="email" class="col-sm-3 col-form-label fw-bold">Email Address</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="john@example.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="form-group row mb-4">
                            <label for="phone" class="col-sm-3 col-form-label fw-bold">Mobile Number</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="+1 234 567 890" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group row mb-4">
                            <label for="address" class="col-sm-3 col-form-label fw-bold">Address</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Enter full address">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="form-group row mb-4">
                            <label for="role_id" class="col-sm-3 col-form-label fw-bold">System Role</label>
                            <div class="col-sm-9">
                                <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                    <option value="">Select a role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group row mb-4">
                            <label for="password" class="col-sm-3 col-form-label fw-bold">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group row mb-4">
                            <label for="password_confirmation" class="col-sm-3 col-form-label fw-bold">Re-enter Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Module Permissions -->
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label fw-bold @error('permissions') text-danger @enderror">Module Permissions</label>
                            <div class="col-sm-9">
                                <div class="table-responsive border rounded bg-light p-2">
                                    <table class="table table-sm table-borderless align-middle mb-0">
                                        <thead class="text-muted small border-bottom">
                                            <tr>
                                                <th style="width: 40%">Module Name</th>
                                                <th class="text-center">Access</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($features as $feature)
                                            <tr class="module-row">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="{{ $feature->icon ?? 'mdi mdi-cube-outline' }} me-2 text-primary"></i>
                                                        <span class="fw-bold">{{ $feature->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input type="checkbox" name="permissions[{{ $feature->id }}][enabled]" 
                                                               class="form-check-input access-toggle" value="1">
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input type="checkbox" name="permissions[{{ $feature->id }}][edit]" 
                                                               class="form-check-input permission-toggle" value="1">
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input type="checkbox" name="permissions[{{ $feature->id }}][delete]" 
                                                               class="form-check-input permission-toggle" value="1">
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="mdi mdi-information-outline me-1"></i>
                                    Enabling <b>Edit</b> or <b>Delete</b> will automatically grant <b>Access</b> to the module.
                                </small>
                            </div>
                        </div>


                        <!-- Active Toggle -->
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label fw-bold">Account Status</label>
                            <div class="col-sm-9">
                                <div class="form-check form-check-flat form-check-success mt-2">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="is_active" class="form-check-input" checked value="1">
                                        Active Account
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="mdi mdi-check me-1"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.module-row');
            
            rows.forEach(row => {
                const accessToggle = row.querySelector('.access-toggle');
                const permissionToggles = row.querySelectorAll('.permission-toggle');
                
                permissionToggles.forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        if (this.checked) {
                            accessToggle.checked = true;
                        }
                    });
                });

                accessToggle.addEventListener('change', function() {
                    if (!this.checked) {
                        permissionToggles.forEach(toggle => toggle.checked = false);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>

