<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminStat extends Model
{
    use HasFactory;
    protected $table = "users";
    protected $appends = ['termine','attente','annule','user'];

    public function getTermineAttribute()
    {
        return Demande::where('status', 3)->count();
    }

    public function getAttenteAttribute()
    {
        return Demande::where('status', 4)->count();
    }

    public function getAnnuleAttribute()
    {
        return Demande::where('status', 2)->count();
    }
    public function getUserAttribute()
    {
        return User::All()->count();
    }
}
