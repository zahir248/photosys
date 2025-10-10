<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Album extends Model
{
    protected $fillable = [
        'user_id',
        'organization_id',
        'name',
        'description',
        'cover_image',
    ];

    /**
     * Get the user that owns the album.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organization that owns the album.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the photos in this album.
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'album_media', 'album_id', 'media_id')
                    ->withPivot('created_at', 'updated_at')
                    ->withTimestamps()
                    ->orderBy('album_media.created_at', 'desc');
    }

    /**
     * Get the cover image URL.
     */
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            // Use the Laravel route to serve storage files
            return route('storage.serve', ['path' => $this->cover_image]);
        }
        
        return null;
    }

    /**
     * Set the first photo as cover image if no cover is set.
     */
    public function setFirstPhotoAsCover()
    {
        if (!$this->cover_image) {
            $firstPhoto = $this->photos()->where('media_type', 'image')->first();
            if ($firstPhoto) {
                // Copy the first image photo to be the cover image
                $coverPath = 'album-covers/' . uniqid() . '_' . $firstPhoto->filename;
                $sourcePath = storage_path('app/public/' . $firstPhoto->storage_path);
                $destinationPath = storage_path('app/public/' . $coverPath);
                
                if (file_exists($sourcePath)) {
                    copy($sourcePath, $destinationPath);
                    $this->update(['cover_image' => $coverPath]);
                }
            }
        }
    }

    /**
     * Check if album has a cover image.
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
