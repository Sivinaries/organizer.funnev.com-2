<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    protected $fillable = [
        'user_id',
        'action',         // contoh: "create_issue", "upload_evidence"
        'description',    // contoh: "Menambahkan issue A.1 tentang SOP"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
