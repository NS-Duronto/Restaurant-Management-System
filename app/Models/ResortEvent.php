<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ResortEvent extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'resort_events';

    protected $fillable = [
        'title',
        'tag',
        'event_date',
        'image_url',
        'description',
        'social_link',
        'is_about_slider',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'is_about_slider' => 'integer',
        'sort_order'      => 'integer',
        'status'          => 'integer',
    ];

    protected $appends = ['image'];

    public function getImageAttribute(): ?string
    {
        if (!empty($this->getFirstMediaUrl('event'))) {
            return $this->getFirstMediaUrl('event');
        }
        return $this->attributes['image_url'] ?? 'https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?w=600&q=80';
    }
}
