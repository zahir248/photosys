<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\OrganizationLimit;

class SuperAdminOrganizationLimitsController extends Controller
{
    /**
     * Display a listing of organizations with their limits.
     */
    public function index()
    {
        $organizations = Organization::with(['limits', 'owner', 'users'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('superadmin.organization-limits.index', compact('organizations'));
    }

    /**
     * Display the specified organization's limits.
     */
    public function show(Organization $organization)
    {
        $organization->load(['limits', 'owner', 'users', 'photos', 'albums']);
        return view('superadmin.organization-limits.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified organization's limits.
     */
    public function edit(Organization $organization)
    {
        $organization->load('limits');
        return view('superadmin.organization-limits.edit', compact('organization'));
    }

    /**
     * Update the specified organization's limits.
     */
    public function update(Request $request, Organization $organization)
    {
        $request->validate([
            'max_photos' => 'required|integer|min:0',
            'max_storage_mb' => 'required|integer|min:0',
            'max_albums' => 'required|integer|min:0',
            'max_members' => 'required|integer|min:0',
            'unlimited_photos' => 'boolean',
            'unlimited_storage' => 'boolean',
            'unlimited_albums' => 'boolean',
            'unlimited_members' => 'boolean',
        ]);

        $limits = $organization->getLimits();
        
        $limits->update([
            'max_photos' => $request->max_photos,
            'max_storage_mb' => $request->max_storage_mb,
            'max_albums' => $request->max_albums,
            'max_members' => $request->max_members,
            'unlimited_photos' => $request->boolean('unlimited_photos'),
            'unlimited_storage' => $request->boolean('unlimited_storage'),
            'unlimited_albums' => $request->boolean('unlimited_albums'),
            'unlimited_members' => $request->boolean('unlimited_members'),
        ]);

        return redirect()->route('superadmin.organization-limits.show', $organization)
                        ->with('success', 'Organization limits updated successfully.');
    }

    /**
     * Reset organization limits to default values.
     */
    public function reset(Organization $organization)
    {
        $defaults = [
            'max_photos' => \App\Models\SystemSetting::get('default_org_max_photos', 10000),
            'max_storage_mb' => \App\Models\SystemSetting::get('default_org_max_storage_mb', 10240),
            'max_albums' => \App\Models\SystemSetting::get('default_org_max_albums', 500),
            'max_members' => \App\Models\SystemSetting::get('default_org_max_members', 100),
            'unlimited_photos' => false,
            'unlimited_storage' => false,
            'unlimited_albums' => false,
            'unlimited_members' => false,
        ];

        $limits = $organization->getLimits();
        $limits->update($defaults);

        return redirect()->route('superadmin.organization-limits.show', $organization)
                        ->with('success', 'Organization limits reset to default values.');
    }

    /**
     * Show organization limits settings.
     */
    public function settings()
    {
        $settings = [
            'default_org_max_photos' => \App\Models\SystemSetting::get('default_org_max_photos', 10000),
            'default_org_max_storage_mb' => \App\Models\SystemSetting::get('default_org_max_storage_mb', 10240),
            'default_org_max_albums' => \App\Models\SystemSetting::get('default_org_max_albums', 500),
            'default_org_max_members' => \App\Models\SystemSetting::get('default_org_max_members', 100),
        ];

        return view('superadmin.organization-limits.settings', compact('settings'));
    }

    /**
     * Update organization limits settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'default_org_max_photos' => 'required|integer|min:0',
            'default_org_max_storage_mb' => 'required|integer|min:0',
            'default_org_max_albums' => 'required|integer|min:0',
            'default_org_max_members' => 'required|integer|min:0',
        ]);

        \App\Models\SystemSetting::set('default_org_max_photos', $request->default_org_max_photos);
        \App\Models\SystemSetting::set('default_org_max_storage_mb', $request->default_org_max_storage_mb);
        \App\Models\SystemSetting::set('default_org_max_albums', $request->default_org_max_albums);
        \App\Models\SystemSetting::set('default_org_max_members', $request->default_org_max_members);

        return redirect()->route('superadmin.organization-limits.settings')
                        ->with('success', 'Organization limits settings updated successfully.');
    }
}