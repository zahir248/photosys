<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLimit;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SuperAdminLimitsController extends Controller
{
    /**
     * Display system limits dashboard
     */
    public function index(Request $request)
    {
        $query = User::with('limits');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Preserve search and filter parameters in pagination links
        $users->appends($request->query());

        $systemSettings = SystemSetting::all()->keyBy('key');
        
        $stats = [
            'total_users' => User::count(),
            'users_with_limits' => UserLimit::count(),
            'total_storage_used' => $this->getTotalStorageUsed(),
            'average_storage_per_user' => $this->getAverageStoragePerUser(),
        ];

        return view('superadmin.limits.index', compact('users', 'systemSettings', 'stats'));
    }

    /**
     * Show user limits
     */
    public function show(User $user)
    {
        $limits = $user->getLimits();
        return view('superadmin.limits.show', compact('user', 'limits'));
    }

    /**
     * Edit user limits
     */
    public function edit(User $user)
    {
        $limits = $user->getLimits();
        return view('superadmin.limits.edit', compact('user', 'limits'));
    }

    /**
     * Update user limits
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'max_photos' => 'required|integer|min:0',
            'max_storage_mb' => 'required|integer|min:0',
            'max_albums' => 'required|integer|min:0',
            'max_organizations' => 'required|integer|min:0',
            'unlimited_photos' => 'boolean',
            'unlimited_storage' => 'boolean',
            'unlimited_albums' => 'boolean',
            'unlimited_organizations' => 'boolean',
        ]);

        $limits = $user->getLimits();
        
        // Handle boolean fields properly - unchecked checkboxes don't send values
        $updateData = [
            'max_photos' => $request->max_photos,
            'max_storage_mb' => $request->max_storage_mb,
            'max_albums' => $request->max_albums,
            'max_organizations' => $request->max_organizations,
            'unlimited_photos' => $request->boolean('unlimited_photos'),
            'unlimited_storage' => $request->boolean('unlimited_storage'),
            'unlimited_albums' => $request->boolean('unlimited_albums'),
            'unlimited_organizations' => $request->boolean('unlimited_organizations'),
        ];
        
        $limits->update($updateData);

        return redirect()->route('superadmin.limits.show', $user)
                        ->with('success', 'User limits updated successfully.');
    }

    /**
     * Show system settings
     */
    public function settings()
    {
        $settings = SystemSetting::all()->keyBy('key');
        return view('superadmin.limits.settings', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'default_max_photos' => 'required|integer|min:0',
            'default_max_storage_mb' => 'required|integer|min:0',
            'default_max_albums' => 'required|integer|min:0',
            'default_max_organizations' => 'required|integer|min:0',
        ]);

        SystemSetting::set('default_max_photos', $request->default_max_photos, 'Default maximum photos per user');
        SystemSetting::set('default_max_storage_mb', $request->default_max_storage_mb, 'Default maximum storage in MB per user');
        SystemSetting::set('default_max_albums', $request->default_max_albums, 'Default maximum albums per user');
        SystemSetting::set('default_max_organizations', $request->default_max_organizations, 'Default maximum organizations per user');

        return redirect()->route('superadmin.limits.settings')
                        ->with('success', 'System settings updated successfully.');
    }

    /**
     * Reset user limits to defaults
     */
    public function reset(User $user)
    {
        $defaults = [
            'max_photos' => SystemSetting::get('default_max_photos', 1000),
            'max_storage_mb' => SystemSetting::get('default_max_storage_mb', 1024),
            'max_albums' => SystemSetting::get('default_max_albums', 50),
            'max_organizations' => SystemSetting::get('default_max_organizations', 10),
            'unlimited_photos' => false,
            'unlimited_storage' => false,
            'unlimited_albums' => false,
            'unlimited_organizations' => false,
        ];

        $limits = $user->getLimits();
        $limits->update($defaults);

        return redirect()->route('superadmin.limits.show', $user)
                        ->with('success', 'User limits reset to defaults.');
    }

    /**
     * Get total storage used by all users
     */
    private function getTotalStorageUsed(): float
    {
        // Use the size_bytes field from the database for consistency
        $totalSize = \App\Models\Photo::whereNull('organization_id')->sum('size_bytes');
        return round($totalSize / (1024 * 1024), 2);
    }

    /**
     * Get average storage per user
     */
    private function getAverageStoragePerUser(): float
    {
        $totalStorage = $this->getTotalStorageUsed();
        $userCount = User::count();
        
        return $userCount > 0 ? round($totalStorage / $userCount, 2) : 0;
    }
}