<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationLimit extends Model
{
    protected $fillable = [
        'organization_id',
        'max_photos',
        'max_storage_mb',
        'max_albums',
        'max_members',
        'unlimited_photos',
        'unlimited_storage',
        'unlimited_albums',
        'unlimited_members',
    ];

    protected $casts = [
        'unlimited_photos' => 'boolean',
        'unlimited_storage' => 'boolean',
        'unlimited_albums' => 'boolean',
        'unlimited_members' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the organization's current photo count
     */
    public function getCurrentPhotosAttribute(): int
    {
        return $this->organization->photos()->count();
    }

    /**
     * Get the organization's current storage usage in MB
     */
    public function getCurrentStorageMbAttribute(): float
    {
        $totalSize = $this->organization->photos()->sum('size_bytes');
        return round($totalSize / (1024 * 1024), 2);
    }

    /**
     * Get the organization's current album count
     */
    public function getCurrentAlbumsAttribute(): int
    {
        return $this->organization->albums()->count();
    }

    /**
     * Get the organization's current member count
     */
    public function getCurrentMembersAttribute(): int
    {
        return $this->organization->users()->count();
    }

    /**
     * Check if organization can upload more photos
     */
    public function canUploadPhotos(): bool
    {
        if ($this->unlimited_photos) {
            return true;
        }
        return $this->current_photos < $this->max_photos;
    }

    /**
     * Check if organization has storage space for a specific file size
     */
    public function hasStorageSpace(int $fileSizeMb): bool
    {
        if ($this->unlimited_storage) {
            return true;
        }
        return ($this->current_storage_mb + $fileSizeMb) <= $this->max_storage_mb;
    }

    /**
     * Check if organization has any storage space left
     */
    public function hasAnyStorageSpace(): bool
    {
        if ($this->unlimited_storage) {
            return true;
        }
        return $this->current_storage_mb < $this->max_storage_mb;
    }

    /**
     * Check if organization can create more albums
     */
    public function canCreateAlbums(): bool
    {
        if ($this->unlimited_albums) {
            return true;
        }
        return $this->current_albums < $this->max_albums;
    }

    /**
     * Check if organization can add more members
     */
    public function canAddMembers(): bool
    {
        if ($this->unlimited_members) {
            return true;
        }
        return $this->current_members < $this->max_members;
    }
}