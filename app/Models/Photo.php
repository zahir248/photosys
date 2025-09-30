<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Photo extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'filename',
        'title',
        'description',
        'storage_path',
        'mime',
        'size_bytes',
        'visibility',
        'share_token',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($photo) {
            if ($photo->visibility === 'public' && !$photo->share_token) {
                $photo->share_token = Str::random(32);
            }
        });
    }

    /**
     * Get the albums that contain this photo.
     */
    public function albums()
    {
        return $this->belongsToMany(Album::class)
                    ->withTimestamps()
                    ->orderBy('album_photo.created_at', 'desc');
    }

    /**
     * Get the organization that owns the photo.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user who uploaded the photo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the URL for the photo.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->storage_path);
    }

    /**
     * Check if the photo is accessible by a user.
     */
    public function isAccessibleBy(User $user = null)
    {
        // Uploader always has access
        if ($user && $this->user_id === $user->id) {
            return true;
        }

        // Public photos are accessible to everyone
        if ($this->visibility === 'public') {
            return true;
        }

        // Organization photos are accessible to organization members
        if ($this->visibility === 'org' && $user && $this->organization_id) {
            return $this->organization->users->contains($user);
        }

        // Private photos are only accessible to uploader
        return false;
    }
}
