<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TickQr extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_ticket',
        'transaction_id',
        'ticket_id',
        'qr_code',
        'status',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }
}
