<?php

namespace App\Models;

use App\Models\Demande;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agents extends Model
{
    use HasFactory;



    protected $table='agents';

    protected $fillable=[
        'nom_agent',
        'email',
        'tel',
        'description',
        'service_id',
        'image',
    ];
    protected $appends = ['service'];
    public function getServiceAttribute()
    {
    //   return $this->categories()->get();
      return Service::where('id',$this->service_id)->first();
    }

    public function demande()
    {
        return $this->hasMany(Demande::class);
    }
    public function service()
    {
        return $this->hasMany(Service::class);
    } 
   
   
}
