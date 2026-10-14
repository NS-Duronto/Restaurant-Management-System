<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ResortPartner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'resort_partners';

    protected $fillable = [
        'name',
        'image_url',
        'website_url',
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
        if (!empty($this->getFirstMediaUrl('partner'))) {
            return $this->getFirstMediaUrl('partner');
        }
        return $this->attributes['image_url'] ?? 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=300&q=80';
    }
}
