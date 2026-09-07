<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KitchenGoods extends Model
{
    use HasFactory;

    protected $table = 'kitchen_goods';

    protected $fillable = [
        'name',
        'kitchen_goods_category_id',
        'unit_id',
        'current_stock',
        'cost_per_unit',
        'alert_quantity',
        'status',
    ];

    protected $appends = [
        'is_low_stock',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'kitchen_goods_category_id' => 'integer',
        'unit_id' => 'integer',
        'current_stock' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'alert_quantity' => 'decimal:2',
        'status' => 'integer',
    ];

    public function getIsLowStockAttribute(): bool
    {
        return (float) $this->alert_quantity > 0 && (float) $this->current_stock <= (float) $this->alert_quantity;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(KitchenGoodsCategory::class, 'kitchen_goods_category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'kitchen_goods_id');
    }

    public function sendToKitchenItems(): HasMany
    {
        return $this->hasMany(SendToKitchenItem::class, 'kitchen_goods_id');
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(ItemIngredient::class, 'kitchen_goods_id');
    }

    public function wastageItems(): HasMany
    {
        return $this->hasMany(WastageItem::class, 'kitchen_goods_id');
    }
}
