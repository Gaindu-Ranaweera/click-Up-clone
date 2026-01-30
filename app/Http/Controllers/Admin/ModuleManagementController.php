<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Feature;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ModuleManagementController extends Controller
{
    protected $commonIcons = [
        'mdi mdi-grid-large',
        'mdi mdi-account-group',
        'mdi mdi-chart-line',
        'mdi mdi-package-variant',
        'mdi mdi-briefcase',
        'mdi mdi-cash-multiple',
        'mdi mdi-account-card-details',
        'mdi mdi-bell',
        'mdi mdi-history',
        'mdi mdi-settings',
        'mdi mdi-clipboard-text',
        'mdi mdi-cube-outline',
        'mdi mdi-database',
        'mdi mdi-email',
        'mdi mdi-folder',
        'mdi mdi-heart',
        'mdi mdi-inbox',
        'mdi mdi-layers',
        'mdi mdi-map',
        'mdi mdi-navigation',
        'mdi mdi-palette',
        'mdi mdi-qrcode',
        'mdi mdi-rss',
        'mdi mdi-shield',
        'mdi mdi-tag',
        'mdi mdi-upload',
        'mdi mdi-view-dashboard',
        'mdi mdi-wallet',
        'mdi mdi-xml',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = Feature::where('is_module', true)->get();
        $icons = $this->commonIcons;
        return view('admin.modules.index', compact('modules', 'icons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name, '_');
        $key = 'module_' . $slug;

        // Check if exists
        if (Feature::where('key', $key)->exists()) {
            return back()->with('error', 'A module with this name already exists.');
        }

        DB::beginTransaction();
        try {
            // 1. Create Feature record
            $feature = Feature::create([
                'name' => $request->name,
                'key' => $key,
                'icon' => $request->icon,
                'route_name' => $slug . '.index',
                'description' => $request->description,
                'is_module' => true,
            ]);

            // 2. Create Directory Structure
            $viewPath = resource_path("views/modules/{$slug}");
            if (!File::exists($viewPath)) {
                File::makeDirectory($viewPath, 0755, true);
                
                $files = ['index', 'create', 'edit', 'show'];
                foreach ($files as $file) {
                    $content = "<x-app-layout>\n    <div class=\"row\">\n        <div class=\"col-12\">\n            <h2>{$request->name} - " . ucfirst($file) . " View</h2>\n            <p>Welcome to the dynamic module: {$request->name}</p>\n        </div>\n    </div>\n</x-app-layout>";
                    File::put("{$viewPath}/{$file}.blade.php", $content);
                }
            }

            // 3. Create Route Entry
            $routeFile = base_path('routes/modules.php');
            $routeContent = "\nRoute::middleware(['feature:{$key}'])->prefix('{$slug}')->name('{$slug}.')->group(function () {\n    Route::get('/', fn() => view('modules.{$slug}.index'))->name('index');\n});\n";
            File::append($routeFile, $routeContent);

            // 4. Grant Access to Super Admins

            $superAdmins = User::whereHas('role', function($q) {
                $q->where('name', 'super_admin');
            })->get();

            foreach ($superAdmins as $admin) {
                $admin->features()->attach($feature->id, ['is_enabled' => true]);
            }

            DB::commit();
            return redirect()->route('admin.modules.index')->with('success', "Module '{$request->name}' created successfully! Check resources/views/modules/{$slug} for your new views.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating module: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $module = Feature::findOrFail($id);
        $icons = $this->commonIcons;
        return view('admin.modules.edit', compact('module', 'icons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $module = Feature::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $module->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.modules.index')->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $module = Feature::findOrFail($id);
        $module->delete();

        return redirect()->route('admin.modules.index')->with('success', 'Module removed from system records. Note: View files were not deleted for safety.');
    }
}

