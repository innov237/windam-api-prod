<?php

namespace App\Models;

use App\Models\Avis;
use App\Models\User;
use App\Models\Agents;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Demande extends Model
{
    use HasFactory;
    private $locale;

public function __construct($locale='en')
{
    $this->locale=$locale;
}

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
        $request=$this->locale;

        return Service::where('id', $this->service_id)->with(['translation' => function ($query) use ($request) {
            $query->where([['locale', $request], ['type', "SERVICE"]])->first();
        }])->first();
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
