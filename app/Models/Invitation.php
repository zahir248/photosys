<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Invitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'organization_id',
        'invited_by',
        'role',
        'accepted_at',
        'expires_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Generate a unique token for the invitation.
     */
    public static function generateToken()
    {
        do {
            $token = Str::random(32);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    /**
     * Create a new invitation.
     */
    public static function createInvitation($email, $organizationId, $invitedBy, $role = 'member')
    {
        return static::create([
            'email' => $email,
            'token' => static::generateToken(),
            'organization_id' => $organizationId,
            'invited_by' => $invitedBy,
            'role' => $role,
            'expires_at' => Carbon::now()->addDays(7), // Expires in 7 days
        ]);
    }

    /**
     * Check if the invitation is expired.
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the invitation is accepted.
     */
    public function isAccepted()
    {
        return !is_null($this->accepted_at);
    }

    /**
     * Get the organization that the invitation is for.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user who sent the invitation.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
