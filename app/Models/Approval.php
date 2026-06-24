<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'no_telpon',
        'ktp',
        'event',
        'organizer',
        'location',
        'description',
        'start_time',
        'end_time',
        'img',
        'img2',
        'syarat',
        'status',
        'user_id',
        'kategori_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    
}
