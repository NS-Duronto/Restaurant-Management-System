<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemIngredient extends Model
{
    use HasFactory;

    protected $table = 'item_ingredients';

    protected $fillable = [
        'item_id',
        'kitchen_goods_id',
        'unit_id',
        'quantity',
    ];

    protected $casts = [
        'id' => 'integer',
        'item_id' => 'integer',
        'kitchen_goods_id' => 'integer',
        'unit_id' => 'integer',
        'quantity' => 'decimal:3',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function kitchenGoods(): BelongsTo
    {
        return $this->belongsTo(KitchenGoods::class, 'kitchen_goods_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
