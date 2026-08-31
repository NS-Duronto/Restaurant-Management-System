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
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'kitchen_goods_category_id' => 'integer',
        'unit_id' => 'integer',
        'current_stock' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'status' => 'integer',
    ];

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
}
