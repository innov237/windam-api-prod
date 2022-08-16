<?php

namespace App\Models;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminUserChat extends Model
{

    use  HasFactory;

    protected $table = "users";
    protected $primaryKey = 'id';
    protected $fillable = [
        'nom',
        'ville',
        'prenom',
        'email',
        'sexe',
        'tel',
        'password',
        'role_id',
        'active',
    ];

    protected $appends = ['chat',"nbunread"];
   // protected $appends = ["nbunread"];



    public function getNbunreadAttribute()
    {
      $count= Chat::where([['status', 0],['receiver_id',$this->id]])->get();
        return count($count);
    }

    public function getChatAttribute()
    {
        //return $this->id;
        return Chat::where('receiver_id', $this->id)->orwhere("sender_id", $this->id)->get();
    }

    public function chat()
    {
        return $this->hasMany(Chat::class,'receiver_id','id');
    }
}
