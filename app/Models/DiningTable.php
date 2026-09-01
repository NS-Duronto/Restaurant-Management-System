<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiningTable extends Model
{
    use HasFactory;

    protected $table = 'dining_tables';

    protected $fillable = ['serial_no', 'name', 'slug', 'size', 'capacity', 'status', 'table_status', 'current_order_id', 'branch_id', 'qr_code'];

    protected $casts = [
        'id' => 'integer',
        'serial_no' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'qr_code' => 'string',
        'size' => 'integer',
        'capacity' => 'integer',
        'branch_id' => 'integer',
        'status' => 'integer',
        'table_status' => 'integer',
        'current_order_id' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new BranchScope);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function currentOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'current_order_id');
    }

    public function getDiningTableStatusAttribute(): int
    {
        return (int) ($this->attributes['table_status'] ?? $this->attributes['dining_table_status'] ?? 1);
    }

    public function getQrAttribute(): ?string
    {
        if (! empty($this->qr_code)) {
            return asset($this->qr_code);
        }

        return null;
    }
}
