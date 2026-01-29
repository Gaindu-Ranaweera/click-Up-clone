<x-app-layout>
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary"><i class="mdi mdi-account-edit me-2"></i>Edit User: {{ $user->name }}</h4>
                    <p class="card-description"> Modify user details, roles, and module permissions. </p>
                    
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="forms-sample">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Name -->
                        <div class="form-group row mb-4">
                            <label for="name" class="col-sm-3 col-form-label fw-bold">Full Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="John Doe" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row mb-4">
                            <label for="email" class="col-sm-3 col-form-label fw-bold">Email Address</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="john@example.com" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="form-group row mb-4">
                            <label for="phone" class="col-sm-3 col-form-label fw-bold">Mobile Number</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="+1 234 567 890" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group row mb-4">
                            <label for="address" class="col-sm-3 col-form-label fw-bold">Address</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Enter full address">{{ old('address', $user->address) }}</textarea>
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
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password (Optional) -->
                        <div class="form-group row mb-4 border-top pt-4">
                            <label for="password" class="col-sm-3 col-form-label fw-bold">New Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Leave blank to keep current">
                                <small class="text-muted">Only fill this if you want to change the user's password.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Module Permissions -->
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label fw-bold">Enable Modules</label>
                            <div class="col-sm-9 mt-2">
                                <div class="row">
                                    @foreach($features as $feature)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check form-check-flat form-check-primary">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="features[]" value="{{ $feature->id }}" 
                                                       {{ in_array($feature->id, $userFeatures) ? 'checked' : '' }}
                                                       class="form-check-input">
                                                {{ $feature->name }}
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Active Toggle -->
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label fw-bold">Account Status</label>
                            <div class="col-sm-9">
                                <div class="form-check form-check-flat form-check-success mt-2">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="is_active" class="form-check-input" 
                                               {{ $user->is_active ? 'checked' : '' }} value="1">
                                        Active Account
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="mdi mdi-check me-1"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
