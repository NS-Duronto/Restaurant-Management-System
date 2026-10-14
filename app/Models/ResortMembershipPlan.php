<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResortMembershipPlan extends Model
{
    use HasFactory;

    protected $table = 'resort_membership_plans';

    protected $fillable = [
        'title',
        'price',
        'badge',
        'duration',
        'features',
        'btn_text',
        'btn_link',
        'is_featured',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'is_featured' => 'integer',
        'sort_order'  => 'integer',
        'status'      => 'integer',
    ];
}
