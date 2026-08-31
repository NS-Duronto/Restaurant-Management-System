<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Expense extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'expenses';

    protected $fillable = [
        'expense_category_id',
        'title',
        'amount',
        'date',
        'payment_method',
        'payee_name',
        'note',
        'user_id',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($expense) {
            if (empty($expense->title)) {
                $expense->title = $expense->note ?? 'Expense';
            }
        });
    }

    protected $casts = [
        'id' => 'integer',
        'expense_category_id' => 'integer',
        'title' => 'string',
        'amount' => 'decimal:2',
        'date' => 'date',
        'payment_method' => 'integer',
        'payee_name' => 'string',
        'note' => 'string',
        'user_id' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFileAttribute(): ?string
    {
        if (! empty($this->getFirstMediaUrl('expense_file'))) {
            return $this->getFirstMediaUrl('expense_file');
        }

        return null;
    }
}
