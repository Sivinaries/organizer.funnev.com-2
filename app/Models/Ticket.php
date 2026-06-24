<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'desc',
        'price',
        'pcs',
        'event_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'trans_ticks')
            ->withPivot('qty', 'subtotal')
            ->withTimestamps();
    }

    public function tickQrs()
    {
        return $this->hasMany(TickQr::class);
    }
}
