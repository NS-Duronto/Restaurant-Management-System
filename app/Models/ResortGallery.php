<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ResortGallery extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'resort_galleries';

    protected $fillable = [
        'title',
        'type',
        'category',
        'image_url',
        'video_url',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status'     => 'integer',
    ];

    protected $appends = ['image'];

    public function getImageAttribute(): ?string
    {
        if (!empty($this->getFirstMediaUrl('gallery'))) {
            return $this->getFirstMediaUrl('gallery');
        }
        return $this->attributes['image_url'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80';
    }
}
