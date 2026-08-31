<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SendToKitchenItem extends Model
{
    use HasFactory;

    protected $table = 'send_to_kitchen_items';

    protected $fillable = [
        'send_to_kitchen_id',
        'kitchen_goods_id',
        'quantity',
        'unit_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'send_to_kitchen_id' => 'integer',
        'kitchen_goods_id' => 'integer',
        'quantity' => 'decimal:2',
        'unit_id' => 'integer',
    ];

    public function sendToKitchen(): BelongsTo
    {
        return $this->belongsTo(SendToKitchen::class, 'send_to_kitchen_id');
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
