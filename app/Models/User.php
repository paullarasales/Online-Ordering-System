<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    
    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'verified',
        'is_blocked',
        'address',
        'contact_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked_until' => 'datetime', 
        ];
    }

    public function isAdmin()
    {
        return $this->usertype === 'admin';
    }

    public function isUser()
    {
        return $this->usertype === 'user';
    }

    public function verification()
    {
        return $this->hasOne(Verification::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function checkBlockedStatus()
    {
        if ($this->is_blocked && $this->blocked_until) {
            if (now()->isAfter($this->blocked_until)) {
                $this->is_blocked = false;
                $this->blocked_until = null; 
                $this->save();
            } else {
                $timeLeft = $this->blocked_until->diffForHumans();
            }
        }
    }


}
