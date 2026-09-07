<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wastage extends Model
{
    use HasFactory;

    protected $table = 'wastages';

    protected $fillable = [
        'wastage_no',
        'date',
        'user_id',
        'total_loss_amount',
        'note',
    ];

    protected $casts = [
        'id' => 'integer',
        'wastage_no' => 'string',
        'date' => 'string',
        'user_id' => 'integer',
        'total_loss_amount' => 'decimal:2',
        'note' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(WastageItem::class, 'wastage_id');
    }
}
