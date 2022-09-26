<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resetpasswords extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'user_id';
    protected $fillable=[
        'user_id',
        'code',
        'tel',
    ];
   
    public function users(){
        return $this->belongsTo(users::class);
    }
}
