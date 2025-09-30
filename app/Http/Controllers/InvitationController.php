<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;
use App\Models\User;

class InvitationController extends Controller
{
    /**
     * Show the invitation acceptance page.
     */
    public function show($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        
        // Check if invitation is expired
        if ($invitation->isExpired()) {
            return view('invitations.expired', compact('invitation'));
        }
        
        // Check if invitation is already accepted
        if ($invitation->isAccepted()) {
            return view('invitations.already-accepted', compact('invitation'));
        }
        
        return view('invitations.accept', compact('invitation'));
    }
    
    /**
     * Accept the invitation.
     */
    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        
        // Check if invitation is expired
        if ($invitation->isExpired()) {
            return redirect()->route('invitations.show', $token)
                ->withErrors(['error' => 'This invitation has expired.']);
        }
        
        // Check if invitation is already accepted
        if ($invitation->isAccepted()) {
            return redirect()->route('invitations.show', $token)
                ->withErrors(['error' => 'This invitation has already been accepted.']);
        }
        
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please log in to accept this invitation.');
        }
        
        // Check if the authenticated user's email matches the invitation email
        if (Auth::user()->email !== $invitation->email) {
            return redirect()->route('invitations.show', $token)
                ->withErrors(['error' => 'This invitation is not for your email address.']);
        }
        
        // Check if user is already a member
        if ($invitation->organization->users->contains(Auth::user())) {
            return redirect()->route('invitations.show', $token)
                ->withErrors(['error' => 'You are already a member of this organization.']);
        }
        
        // Check organization member limits
        $orgLimits = $invitation->organization->getLimits();
        if ($orgLimits && !$orgLimits->canAddMembers()) {
            return redirect()->route('invitations.show', $token)
                ->withErrors(['error' => 'This organization has reached its member limit (' . $orgLimits->max_members . ' members). Please contact an administrator to increase the limit.']);
        }
        
        // Add user to organization
        $invitation->organization->users()->attach(Auth::user()->id, ['role' => $invitation->role]);
        
        // Mark invitation as accepted
        $invitation->update(['accepted_at' => now()]);
        
        return redirect()->route('organizations.show', $invitation->organization->name)
            ->with('success', 'You have successfully joined ' . $invitation->organization->name . '!');
    }
}
