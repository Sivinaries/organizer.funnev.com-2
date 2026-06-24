<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_order',
        'user_id',
        'total_amount',
        'total_service',
        'net_income',
        'event_id',
        'status',
        'notes',
        'snap_token',
        'midtrans_synced_at',
        'expired_at',
    ];

    protected $casts =
    [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'trans_ticks')
            ->as('pivot') // Alias the pivot relationship
            ->withPivot('qty', 'subtotal', 'service')
            ->withTimestamps();
    }

    public function tickQrs()
    {
        return $this->hasMany(TickQr::class);
    }
}
