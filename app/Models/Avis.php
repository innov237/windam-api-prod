<?php

namespace App\Models;

use App\Models\Demande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Avis extends Model
{
    use HasFactory;

    protected $table='avis';

    protected $fillable=[
        'idDemande',
        'note',
        'comments'
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    } 
}
