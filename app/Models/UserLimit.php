<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLimit extends Model
{
    protected $fillable = [
        'user_id',
        'max_photos',
        'max_storage_mb',
        'max_albums',
        'max_organizations',
        'unlimited_photos',
        'unlimited_storage',
        'unlimited_albums',
        'unlimited_organizations',
    ];

    protected $casts = [
        'unlimited_photos' => 'boolean',
        'unlimited_storage' => 'boolean',
        'unlimited_albums' => 'boolean',
        'unlimited_organizations' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user's current photo count (personal photos only, excluding organization photos)
     */
    public function getCurrentPhotosAttribute(): int
    {
        return $this->user->photos()->whereNull('organization_id')->count();
    }

    /**
     * Get the user's current storage usage in MB (personal photos only, excluding organization photos)
     */
    public function getCurrentStorageMbAttribute(): float
    {
        // Use the size_bytes field from the database instead of reading files
        // Only include personal photos (where organization_id is null)
        $totalSize = $this->user->photos()->whereNull('organization_id')->sum('size_bytes');
        return round($totalSize / (1024 * 1024), 2);
    }

    /**
     * Get the user's current album count (personal albums only, excluding organization albums)
     */
    public function getCurrentAlbumsAttribute(): int
    {
        return $this->user->albums()->whereNull('organization_id')->count();
    }

    /**
     * Get the user's current organization count
     */
    public function getCurrentOrganizationsAttribute(): int
    {
        return $this->user->organizations()->count();
    }

    /**
     * Check if user can upload more photos
     */
    public function canUploadPhotos(): bool
    {
        if ($this->unlimited_photos) {
            return true;
        }
        return $this->current_photos < $this->max_photos;
    }

    /**
     * Check if user has storage space for a specific file size
     */
    public function hasStorageSpace(int $fileSizeMb): bool
    {
        if ($this->unlimited_storage) {
            return true;
        }
        return ($this->current_storage_mb + $fileSizeMb) <= $this->max_storage_mb;
    }

    /**
     * Check if user has any storage space left (for general checks)
     */
    public function hasAnyStorageSpace(): bool
    {
        if ($this->unlimited_storage) {
            return true;
        }
        return $this->current_storage_mb < $this->max_storage_mb;
    }

    /**
     * Check if user can create more albums
     */
    public function canCreateAlbums(): bool
    {
        if ($this->unlimited_albums) {
            return true;
        }
        return $this->current_albums < $this->max_albums;
    }

    /**
     * Check if user can join more organizations
     */
    public function canJoinOrganizations(): bool
    {
        if ($this->unlimited_organizations) {
            return true;
        }
        return $this->current_organizations < $this->max_organizations;
    }
}
