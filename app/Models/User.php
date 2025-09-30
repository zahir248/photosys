<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Get the organizations that the user belongs to.
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the photos uploaded by the user.
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Get the albums created by the user.
     */
    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    /**
     * Get the organizations owned by the user.
     */
    public function ownedOrganizations()
    {
        return $this->hasMany(Organization::class, 'owner_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is a superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'admin']);
    }

    /**
     * Get the user's limits.
     */
    public function limits()
    {
        return $this->hasOne(UserLimit::class);
    }

    /**
     * Get or create user limits with default values.
     */
    public function getLimits()
    {
        if (!$this->limits) {
            // Get default values from system settings
            $defaults = [
                'max_photos' => \App\Models\SystemSetting::get('default_max_photos', 1000),
                'max_storage_mb' => \App\Models\SystemSetting::get('default_max_storage_mb', 1024),
                'max_albums' => \App\Models\SystemSetting::get('default_max_albums', 50),
                'max_organizations' => \App\Models\SystemSetting::get('default_max_organizations', 10),
            ];
            
            $this->limits()->create($defaults);
            $this->load('limits');
        }
        
        return $this->limits;
    }
}
