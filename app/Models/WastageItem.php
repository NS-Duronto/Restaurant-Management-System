<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WastageItem extends Model
{
    use HasFactory;

    protected $table = 'wastage_items';

    protected $fillable = [
        'wastage_id',
        'kitchen_goods_id',
        'unit_id',
        'quantity',
        'cost_per_unit',
        'total_cost',
        'reason',
    ];

    protected $casts = [
        'id' => 'integer',
        'wastage_id' => 'integer',
        'kitchen_goods_id' => 'integer',
        'unit_id' => 'integer',
        'quantity' => 'decimal:3',
        'cost_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'reason' => 'string',
    ];

    public function wastage(): BelongsTo
    {
        return $this->belongsTo(Wastage::class, 'wastage_id');
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
