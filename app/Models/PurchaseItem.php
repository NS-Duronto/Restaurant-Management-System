<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'kitchen_goods_id',
        'quantity',
        'unit_id',
        'unit_cost',
        'total_cost',
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_id' => 'integer',
        'kitchen_goods_id' => 'integer',
        'quantity' => 'decimal:2',
        'unit_id' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
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
