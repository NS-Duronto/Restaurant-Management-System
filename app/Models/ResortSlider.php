<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ResortSlider extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'resort_sliders';

    protected $fillable = [
        'title',
        'subtitle',
        'btn_text1',
        'btn_link1',
        'btn_text2',
        'btn_link2',
        'image_url',
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
        if (!empty($this->getFirstMediaUrl('slider'))) {
            return $this->getFirstMediaUrl('slider');
        }
        return $this->attributes['image_url'] ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1600&q=80';
    }
}
