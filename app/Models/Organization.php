<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'cover_image',
    ];

    /**
     * Get the owner of the organization.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users that belong to the organization.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the albums for the organization.
     */
    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    /**
     * Get the photos for the organization.
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Get the invitations for the organization.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Get the organization's limits.
     */
    public function limits()
    {
        return $this->hasOne(OrganizationLimit::class);
    }

    /**
     * Get or create organization limits with default values.
     */
    public function getLimits()
    {
        if (!$this->limits) {
            // Get default values from system settings
            $defaults = [
                'max_photos' => \App\Models\SystemSetting::get('default_org_max_photos', 10000),
                'max_storage_mb' => \App\Models\SystemSetting::get('default_org_max_storage_mb', 10240),
                'max_albums' => \App\Models\SystemSetting::get('default_org_max_albums', 500),
                'max_members' => \App\Models\SystemSetting::get('default_org_max_members', 100),
            ];
            
            $this->limits()->create($defaults);
            $this->load('limits');
        }
        
        return $this->limits;
    }

    /**
     * Get the cover image URL.
     */
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        
        return null;
    }

    /**
     * Check if organization has a cover image.
     */
    public function hasCoverImage()
    {
        return !empty($this->cover_image);
    }

    /**
     * Get the cover image path for storage.
     */
    public function getCoverImagePathAttribute()
    {
        if ($this->cover_image) {
            return storage_path('app/public/' . $this->cover_image);
        }
        return null;
    }
}
