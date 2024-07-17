<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'valid_id1',
        'verified',
        'notified'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
