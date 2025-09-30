<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Organization;
use App\Models\User;
use App\Models\Invitation;
use App\Mail\OrganizationInvitation;
use App\Mail\OrganizationMemberRemoved;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $organizations = $user->organizations()->with(['owner', 'limits', 'photos', 'albums', 'users'])->get();

        // Ensure all organizations have limits
        foreach ($organizations as $organization) {
            $organization->getLimits();
        }

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created organization.
     */
    public function store(Request $request)
    {
        \Log::info('Organization store request received', [
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);
        
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            ]);

            $user = Auth::user()->load('limits');
            
            // Check user limits before proceeding
            $userLimits = $user->limits;
            if ($userLimits && !$userLimits->canJoinOrganizations()) {
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have reached your organization limit (' . $userLimits->max_organizations . ' organizations). Please contact an administrator to increase your limit.'
                    ], 403);
                }
                return back()->withErrors(['name' => 'You have reached your organization limit (' . $userLimits->max_organizations . ' organizations). Please contact an administrator to increase your limit.']);
            }

            // Handle cover image upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('organization-covers', 'public');
            }

            $organization = Organization::create([
                'name' => $request->name,
                'description' => $request->description,
                'owner_id' => $user->id,
                'cover_image' => $coverImagePath,
            ]);

            // Add the creator as owner
            $organization->users()->attach($user->id, ['role' => 'owner']);

            // Create default limits for the organization
            $organization->getLimits();

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Organization created successfully!',
                    'organization' => $organization
                ]);
            }

            return redirect()->route('organizations.show', $organization->name)->with('success', 'Organization created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error creating organization', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the organization: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Display the specified organization.
     */
    public function show($name)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Check if user has access to the organization
        if (!$organization->users->contains($user)) {
            abort(403, 'You do not have permission to view this organization.');
        }

        $organization->load(['users', 'albums.photos', 'photos', 'limits']);
        
        // Ensure organization has limits
        $organization->getLimits();
        
        $albums = $organization->albums()->with('photos')->paginate(10);
        $photos = $organization->photos()->with(['albums', 'user'])->paginate(20);

        return view('organizations.show', compact('organization', 'albums', 'photos'));
    }

    /**
     * Get organization data for modal editing.
     */
    public function editData($name)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Check if user is the owner
        if ($organization->owner_id !== $user->id) {
            abort(403, 'You do not have permission to edit this organization.');
        }

        $organization->load(['owner', 'users']);
        
        // Calculate total size of all photos uploaded by organization members
        $totalSize = 0;
        $memberIds = $organization->users->pluck('id');
        
        // Get total size from photos uploaded by organization members
        $photosSize = \App\Models\Photo::whereIn('user_id', $memberIds)
            ->where('organization_id', $organization->id)
            ->sum('size_bytes');
        
        $totalSize += $photosSize;
        
        return response()->json([
            'organization' => [
                'name' => $organization->name,
                'description' => $organization->description,
                'type' => $organization->type,
                'members_count' => $organization->users->count(),
                'total_size' => $totalSize,
                'owner' => $organization->owner,
                'created_at' => $organization->created_at,
                'cover_image_url' => $organization->cover_image_url,
                'has_cover_image' => $organization->hasCoverImage()
            ]
        ]);
    }

    /**
     * Remove cover image from organization.
     */
    public function removeCover(Request $request, $name)
    {
        try {
            $user = Auth::user();
            $organization = Organization::where('name', $name)->firstOrFail();
            
            // Check if user is the owner
            if ($organization->owner_id !== $user->id) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to modify this organization.'
                    ], 403);
                }
                abort(403, 'You do not have permission to modify this organization.');
            }

            // Delete cover image file if it exists
            if ($organization->cover_image && Storage::disk('public')->exists($organization->cover_image)) {
                Storage::disk('public')->delete($organization->cover_image);
            }

            // Update organization to remove cover image
            $organization->update(['cover_image' => null]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cover image removed successfully!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Cover image removed successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while removing the cover image: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit($name)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Check if user is the owner
        if ($organization->owner_id !== $user->id) {
            abort(403, 'You do not have permission to edit this organization.');
        }

        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization.
     */
    public function update(Request $request, $name)
    {
        \Log::info('Organization update request received', [
            'name' => $name,
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);
        
        try {
            $user = Auth::user();
            $organization = Organization::where('name', $name)->firstOrFail();
            
            // Check if user is the owner
            if ($organization->owner_id !== $user->id) {
                abort(403, 'You do not have permission to edit this organization.');
            }

            $request->validate([
                'name' => 'required|string|max:255|unique:organizations,name,' . $organization->id,
                'description' => 'nullable|string|max:1000',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            ]);

            // Handle cover image upload
            $updateData = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            if ($request->hasFile('cover_image')) {
                // Delete old cover image if it exists
                if ($organization->cover_image && Storage::disk('public')->exists($organization->cover_image)) {
                    Storage::disk('public')->delete($organization->cover_image);
                }
                
                // Store new cover image
                $coverImagePath = $request->file('cover_image')->store('organization-covers', 'public');
                $updateData['cover_image'] = $coverImagePath;
            }

            $organization->update($updateData);

            \Log::info('Organization updated successfully', [
                'organization_id' => $organization->id,
                'new_name' => $organization->name
            ]);

            // Check if this is an AJAX request (from modal)
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                \Log::info('Returning JSON response for AJAX request');
                return response()->json([
                    'success' => true,
                    'message' => 'Organization updated successfully!',
                    'organization' => $organization
                ]);
            }

            return redirect()->route('organizations.show', $organization->name)->with('success', 'Organization updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in organization update', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error updating organization', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the organization: ' . $e->getMessage()
                ], 500);
            }
            
            throw $e;
        }
    }

    /**
     * Show the form for inviting a user to the organization.
     */
    public function showInvite($name)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Check if user is the owner
        if ($organization->owner_id !== $user->id) {
            abort(403, 'You do not have permission to invite users to this organization.');
        }

        return view('organizations.invite', compact('organization'));
    }

    /**
     * Invite a user to the organization.
     */
    public function invite(Request $request, $name)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Check if user is the owner
        if ($organization->owner_id !== $user->id) {
            abort(403, 'You do not have permission to invite users to this organization.');
        }

        // Check organization member limits
        $orgLimits = $organization->getLimits();
        if ($orgLimits && !$orgLimits->canAddMembers()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This organization has reached its member limit (' . $orgLimits->max_members . ' members). Please contact an administrator to increase the limit.'
                ], 403);
            }
            return back()->withErrors(['email' => 'This organization has reached its member limit (' . $orgLimits->max_members . ' members). Please contact an administrator to increase the limit.']);
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;
        
        // Check if user exists in the database
        $existingUser = User::where('email', $email)->first();
        
        if (!$existingUser) {
            // User doesn't exist in the system
            \Log::info('Invitation blocked - user not registered', [
                'email' => $email,
                'organization_id' => $organization->id
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No user found with this email address. Please ask them to register in the system first.'
                ], 422);
            }
            return back()->withErrors(['email' => 'No user found with this email address. Please ask them to register in the system first.']);
        }
        
        // Check if this specific user is already a member
        if ($organization->users->contains($existingUser)) {
            \Log::info('Invitation blocked - user already a member', [
                'email' => $email,
                'user_id' => $existingUser->id,
                'organization_id' => $organization->id
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A user with this email address is already a member of this organization.'
                ], 422);
            }
            return back()->withErrors(['email' => 'A user with this email address is already a member of this organization.']);
        }
        
        // Check if there are any other users with the same email who might be members
        // This handles cases where there might be duplicate email addresses in the system
        $allUsersWithEmail = User::where('email', $email)->get();
        $isAnyUserMember = $allUsersWithEmail->some(function ($user) use ($organization) {
            return $organization->users->contains($user);
        });
        
        if ($isAnyUserMember) {
            \Log::info('Invitation blocked - another user with same email is a member', [
                'email' => $email,
                'user_ids' => $allUsersWithEmail->pluck('id'),
                'organization_id' => $organization->id
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A user with this email address is already a member of this organization.'
                ], 422);
            }
            return back()->withErrors(['email' => 'A user with this email address is already a member of this organization.']);
        }

        // Clean up any expired invitations for this email and organization first
        $expiredInvitations = Invitation::where('email', $email)
            ->where('organization_id', $organization->id)
            ->whereNull('accepted_at')
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredInvitations->count() > 0) {
            \Log::info('Cleaning up expired invitations', [
                'email' => $email,
                'organization_id' => $organization->id,
                'expired_count' => $expiredInvitations->count(),
                'expired_ids' => $expiredInvitations->pluck('id')
            ]);
            $expiredInvitations->each->delete();
        }

        // Check if there's still a valid pending invitation
        $existingInvitation = Invitation::where('email', $email)
            ->where('organization_id', $organization->id)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            \Log::info('Duplicate invitation attempt blocked - valid invitation exists', [
                'email' => $email,
                'organization_id' => $organization->id,
                'existing_invitation_id' => $existingInvitation->id,
                'expires_at' => $existingInvitation->expires_at
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An invitation has already been sent to this email address.'
                ], 422);
            }
            return back()->withErrors(['email' => 'An invitation has already been sent to this email address.']);
        }

        // Create invitation with additional safety check
        try {
            $invitation = Invitation::createInvitation($email, $organization->id, $user->id, 'member');
            
            // Determine if this is a re-invitation
            $isReInvitation = $existingUser && !$organization->users->contains($existingUser);
            
            \Log::info('Invitation created successfully', [
                'email' => $email,
                'organization_id' => $organization->id,
                'invitation_id' => $invitation->id,
                'is_re_invitation' => $isReInvitation
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            \Log::warning('Duplicate invitation detected during creation', [
                'email' => $email,
                'organization_id' => $organization->id,
                'error' => $e->getMessage()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An invitation has already been sent to this email address.'
                ], 422);
            }
            return back()->withErrors(['email' => 'An invitation has already been sent to this email address.']);
        }

        // Send invitation email
        try {
            Mail::to($email)->send(new OrganizationInvitation($invitation));
        } catch (\Exception $e) {
            \Log::error('Failed to send invitation email', [
                'email' => $email,
                'organization' => $organization->name,
                'error' => $e->getMessage()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invitation created but failed to send email. Please try again.'
                ], 500);
            }
            return back()->withErrors(['email' => 'Invitation created but failed to send email. Please try again.']);
        }

        // Determine success message based on whether this is a re-invitation
        $successMessage = $isReInvitation 
            ? 'Invitation sent successfully! The user can rejoin the organization.'
            : 'Invitation sent successfully!';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
        }

        return redirect()->route('organizations.show', $organization->name)->with('success', $successMessage);
    }

    /**
     * Remove a user from the organization.
     */
    public function removeUser($name, $userId)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Find the user to remove
        $userToRemove = User::find($userId);
        if (!$userToRemove) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }
            return back()->withErrors(['user' => 'User not found.']);
        }
        
        // Debug logging
        \Log::info('removeUser called', [
            'organization' => $organization->name,
            'userId' => $userId,
            'userToRemove' => $userToRemove->toArray(),
            'userToRemoveEmail' => $userToRemove->email
        ]);
        
        // Check if user is the owner
        if ($organization->owner_id !== $user->id) {
            abort(403, 'You do not have permission to remove users from this organization.');
        }

        // Cannot remove the owner
        if ($userToRemove->id === $organization->owner_id) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove the organization owner.'
                ], 422);
            }
            return back()->withErrors(['user' => 'Cannot remove the organization owner.']);
        }

        $organization->users()->detach($userToRemove->id);

        // Send email notification to the removed user
        try {
            if ($userToRemove && $userToRemove->email) {
                Mail::to($userToRemove->email)->send(new OrganizationMemberRemoved($organization, $userToRemove, $user));
                \Log::info('Member removal email sent successfully', [
                    'email' => $userToRemove->email,
                    'organization' => $organization->name
                ]);
            } else {
                \Log::warning('Cannot send email - userToRemove is null or has no email', [
                    'userToRemove' => $userToRemove,
                    'email' => $userToRemove ? $userToRemove->email : 'null'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send member removal email', [
                'email' => $userToRemove ? $userToRemove->email : 'null',
                'organization' => $organization->name,
                'error' => $e->getMessage()
            ]);
            // Continue with the removal even if email fails
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User removed successfully!'
            ]);
        }

        return redirect()->route('organizations.show', $organization->name)->with('success', 'User removed successfully!');
    }

    /**
     * Leave the organization.
     */
    public function leave($name)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Check if user is a member
        if (!$organization->users->contains($user)) {
            abort(403, 'You are not a member of this organization.');
        }

        // Cannot leave if you're the owner
        if ($organization->owner_id === $user->id) {
            return back()->withErrors(['organization' => 'Organization owner cannot leave. Transfer ownership first.']);
        }

        $organization->users()->detach($user->id);

        return redirect()->route('organizations.index')->with('success', 'You have left the organization.');
    }

    /**
     * Remove the specified organization.
     */
    /**
     * Display unorganized photos for the organization.
     */
    public function unorganized($name)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Check if user has access to the organization
        if (!$organization->users->contains($user)) {
            abort(403, 'You do not have access to this organization.');
        }

        // Get unorganized photos with pagination
        $photos = $organization->photos()
            ->doesntHave('albums')
            ->with(['user'])
            ->latest()
            ->paginate(12);

        return view('organizations.unorganized', compact('organization', 'photos'));
    }

    public function destroy($name)
    {
        $user = Auth::user();
        $organization = Organization::where('name', $name)->firstOrFail();
        
        // Check if user is the owner
        if ($organization->owner_id !== $user->id) {
            abort(403, 'You do not have permission to delete this organization.');
        }

        $organization->delete();

        // Check if this is an AJAX request (from modal)
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Organization deleted successfully!'
            ]);
        }

        return redirect()->route('organizations.index')->with('success', 'Organization deleted successfully!');
    }
}
