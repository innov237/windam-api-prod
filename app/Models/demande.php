<?php

namespace App\Models;

use App\Models\Avis;
use App\Models\User;
use App\Models\Agents;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Demande extends Model
{
    use HasFactory;



    protected $table = 'demandes';

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'agent_id',
        'service_id',
        'user_id',
        'tel',
        'city',
        'description',
        'date',
        'file',
        'heure',
        'status',
    ];

    protected $appends = ['services', "user", "agent"];
    
    public function getservicesAttribute()
    {
        return Service::where('id', $this->service_id)->first();
    }

    public function getuserAttribute()
    {
        return User::where('id', $this->user_id)->first();
    }
    
    public function getAgentAttribute()
    {
        if ($this->agent_id > 0) {
            return Agents::where('id', $this->agent_id)->first();
        } else {
            return null;
        }
    }

    public function services()
    {
        return $this->hasOne(Service::class);
    }

    public function agents()
    {
        return $this->hasOne(Agents::class);
    }
    public function users()
    {
        return $this->hasOne(User::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }
}
