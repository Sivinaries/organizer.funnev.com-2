<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransTick extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'ticket_id',
        'qty',
        'service',
        'subtotal',
    ];
}
