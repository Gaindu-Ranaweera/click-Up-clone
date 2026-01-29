<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Feature;
use App\Models\UserFeature;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $roles = [
            'super_admin' => Role::create(['name' => 'super_admin']),
            'admin'       => Role::create(['name' => 'admin']),
            'user'        => Role::create(['name' => 'user']),
        ];

        // 2. Create Core Features
        $featuresList = [
            ['name' => 'Project Management', 'key' => 'module_projects', 'description' => 'Manage projects and tasks'],
            ['name' => 'Client Management',  'key' => 'module_clients',  'description' => 'Manage client database'],
            ['name' => 'HR',                 'key' => 'module_hr',       'description' => 'Human Resources management'],
            ['name' => 'Salary & Payroll',   'key' => 'module_payroll',  'description' => 'Payroll processing'],
            ['name' => 'Finance & Revenue',  'key' => 'module_finance',  'description' => 'Finance tracking'],
            ['name' => 'Client Coordination', 'key' => 'module_client_coordination', 'description' => 'Client Follow-ups & Coordination'],
            ['name' => 'Notifications',      'key' => 'module_notifications', 'description' => 'System notifications'],
            ['name' => 'Audit Logs',         'key' => 'module_audit_logs', 'description' => 'View system logs'],
        ];

        $createdFeatures = [];
        foreach ($featuresList as $f) {
            $createdFeatures[$f['key']] = Feature::create($f);
        }

        // 3. Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['super_admin']->id,
            'is_active' => true,
        ]);

        // Grant ALL features to Super Admin
        foreach ($createdFeatures as $feature) {
            $superAdmin->features()->attach($feature->id, ['is_enabled' => true]);
        }

        // 4. Create an Admin
        $admin = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['admin']->id,
            'is_active' => true,
        ]);

        // Grant core features (excluding Audit Logs maybe?)
        foreach ($createdFeatures as $key => $feature) {
            if ($key !== 'module_audit_logs') {
                $admin->features()->attach($feature->id, ['is_enabled' => true]);
            }
        }

        // 5. Create a Standard User
        $user = User::create([
            'name' => 'Standard Employee',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['user']->id,
            'is_active' => true,
        ]);

        // Grant basic features
        $userFeatures = ['module_projects', 'module_notifications'];
        foreach ($userFeatures as $key) {
             if (isset($createdFeatures[$key])) {
                $user->features()->attach($createdFeatures[$key]->id, ['is_enabled' => true]);
             }
        }
    }
}
