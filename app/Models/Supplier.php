<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'company_name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'address' => 'string',
        'status' => 'integer',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }
}
