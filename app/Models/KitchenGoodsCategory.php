<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Enums\CropPosition;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class KitchenGoodsCategory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'kitchen_goods_categories';

    protected $fillable = ['name', 'slug', 'description', 'status'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }
        });
    }

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'status' => 'integer',
    ];

    public function getThumbAttribute(): string
    {
        if (! empty($this->getFirstMediaUrl('kitchen-goods-category'))) {
            $category = $this->getMedia('kitchen-goods-category')->last();

            return $category->getUrl('thumb');
        }

        return asset('images/category/thumb.png');
    }

    public function getCoverAttribute(): string
    {
        if (! empty($this->getFirstMediaUrl('kitchen-goods-category'))) {
            $category = $this->getMedia('kitchen-goods-category')->last();

            return $category->getUrl('cover');
        }

        return asset('images/category/cover.png');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->crop(75, 48, CropPosition::Center)->keepOriginalImageFormat()->sharpen(10);
        $this->addMediaConversion('cover')->width(400)->keepOriginalImageFormat()->sharpen(10);
    }

    public function kitchenGoods(): HasMany
    {
        return $this->hasMany(KitchenGoods::class, 'kitchen_goods_category_id')->where(['status' => Status::ACTIVE]);
    }
}
