<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Role extends Model
{
    use HasFactory;

    protected $table = "role";
    protected $fillable = [
        'role',
        'title'
    ];


    public function user()
    {
        return $this->hasMany(User::class);
    }
}
