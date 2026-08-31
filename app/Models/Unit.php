<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';

    protected $fillable = ['name', 'code', 'status'];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'status' => 'integer',
    ];

    public function kitchenGoods(): HasMany
    {
        return $this->hasMany(KitchenGoods::class, 'unit_id');
    }
}
