<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ResortTeam extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'resort_teams';

    protected $fillable = [
        'name',
        'designation',
        'image_url',
        'bio',
        'facebook',
        'linkedin',
        'twitter',
        'instagram',
        'email',
        'phone',
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
        if (!empty($this->getFirstMediaUrl('team'))) {
            return $this->getFirstMediaUrl('team');
        }
        return $this->attributes['image_url'] ?? 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80';
    }
}
