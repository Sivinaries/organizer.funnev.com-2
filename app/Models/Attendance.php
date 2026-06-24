<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'tick_qr_id',
        'event_id',
        'transaction_id',
        'scanned_at',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function tickQr()
    {
        return $this->belongsTo(TickQr::class);
    }
}
