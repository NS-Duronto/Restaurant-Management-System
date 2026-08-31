<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_serial_no',
        'token',
        'user_id',
        'branch_id',
        'subtotal',
        'discount',
        'delivery_charge',
        'total_tax',
        'total',
        'order_type',
        'order_datetime',
        'delivery_time',
        'preparation_time',
        'is_advance_order',
        'address',
        'payment_method',
        'payment_status',
        'status',
        'dining_table_id',
        'source',
        'pos_payment_method',
        'pos_payment_note',
        'pos_received_amount',
        'change_return',
        'slip_type',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_serial_no' => 'string',
        'token' => 'string',
        'user_id' => 'integer',
        'branch_id' => 'integer',
        'subtotal' => 'decimal:6',
        'discount' => 'decimal:6',
        'delivery_charge' => 'decimal:6',
        'total_tax' => 'decimal:6',
        'total' => 'decimal:6',
        'order_type' => 'integer',
        'order_datetime' => 'datetime',
        'delivery_time' => 'string',
        'preparation_time' => 'integer',
        'is_advance_order' => 'integer',
        'payment_method' => 'integer',
        'payment_status' => 'integer',
        'status' => 'integer',
        'dining_table_id' => 'integer',
        'source' => 'integer',
        'pos_payment_method' => 'integer',
        'pos_payment_note' => 'string',
        'pos_received_amount' => 'decimal:6',
        'change_return' => 'decimal:6',
        'slip_type' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new BranchScope);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'order_items')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function address(): HasOne
    {
        return $this->hasOne(OrderAddress::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING);
    }

    public function scopePreparing($query)
    {
        return $query->where('status', OrderStatus::PREPARING);
    }

    public function scopePrepared($query)
    {
        return $query->where('status', OrderStatus::PREPARED);
    }

    public function scopeOutForDelivery($query)
    {
        return $query->where('status', OrderStatus::OUT_FOR_DELIVERY);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', OrderStatus::DELIVERED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', OrderStatus::CANCELED);
    }

    public function scopeReturned($query)
    {
        return $query->where('status', OrderStatus::RETURNED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', OrderStatus::REJECTED);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function diningTable(): BelongsTo
    {
        return $this->belongsTo(DiningTable::class);
    }
}
