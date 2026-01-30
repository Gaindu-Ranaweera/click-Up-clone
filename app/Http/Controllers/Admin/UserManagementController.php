<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'features'])->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $features = Feature::all();
        return view('admin.users.create', compact('roles', 'features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        // Attach selected features with granular permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $featureId => $perms) {
                if (isset($perms['enabled'])) {
                    $user->features()->attach($featureId, [
                        'is_enabled' => true,
                        'can_edit' => isset($perms['edit']),
                        'can_delete' => isset($perms['delete']),
                    ]);
                }
            }
        }


        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $features = Feature::all();
        $userFeatures = $user->features->pluck('id')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'features', 'userFeatures'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        // Sync features with granular permissions
        $user->features()->detach();
        if ($request->has('permissions')) {
            foreach ($request->permissions as $featureId => $perms) {
                if (isset($perms['enabled'])) {
                    $user->features()->attach($featureId, [
                        'is_enabled' => true,
                        'can_edit' => isset($perms['edit']),
                        'can_delete' => isset($perms['delete']),
                    ]);
                }
            }
        }


        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleFeature(Request $request, User $user, Feature $feature)
    {
        $pivot = $user->features()->where('feature_id', $feature->id)->first();
        
        if ($pivot) {
            // Toggle the enabled status
            $newStatus = !$pivot->pivot->is_enabled;
            $user->features()->updateExistingPivot($feature->id, ['is_enabled' => $newStatus]);
        } else {
            // Attach with enabled status
            $user->features()->attach($feature->id, ['is_enabled' => true]);
        }

        return back()->with('success', 'Feature access updated.');
    }
}
