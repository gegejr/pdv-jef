<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // adicionado
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [  // <<< esta propriedade aqui, fora de métodos
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function hasValidSubscription()
    {
        return $this->subscription_ends_at 
            && Carbon::parse($this->subscription_ends_at)->isFuture();
    }
    
    /*public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }
    */
}
