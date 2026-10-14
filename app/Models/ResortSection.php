<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResortSection extends Model
{
    use HasFactory;

    protected $table = 'resort_sections';

    protected $fillable = [
        'name',
        'section_key',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status'     => 'integer',
    ];
}
