<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResortBooking extends Model
{
    use HasFactory;

    protected $table = 'resort_bookings';

    protected $fillable = [
        'type',
        'name',
        'phone',
        'email',
        'booking_date',
        'check_in',
        'check_out',
        'room_type',
        'event_type',
        'guests_count',
        'notes',
        'status',
    ];

    protected $casts = [
        'guests_count' => 'integer',
        'status'       => 'integer',
    ];
}
