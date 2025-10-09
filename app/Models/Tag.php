<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * Get the photos that have this tag.
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photo_tag', 'tag_id', 'photo_id')
                    ->withTimestamps();
    }
}
