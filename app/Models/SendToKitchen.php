<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SendToKitchen extends Model
{
    use HasFactory;

    protected $table = 'send_to_kitchens';

    protected $fillable = [
        'send_no',
        'date',
        'user_id',
        'note',
        'total_items',
    ];

    protected $casts = [
        'id' => 'integer',
        'send_no' => 'string',
        'date' => 'date',
        'user_id' => 'integer',
        'note' => 'string',
        'total_items' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SendToKitchenItem::class, 'send_to_kitchen_id');
    }
}
