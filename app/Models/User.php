<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\Role;
use App\Models\Demande;
use App\Models\Resetpasswords;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable;


    const ADMIN_ID = 2;
    protected $table = "users";
    protected $primaryKey = 'id';
    protected $fillable = [
        'nom',
        'prenom',
        'ville',
        'email',
        'prenom',
        'sexe',
        'tel',
        'email',
        'password',
        'role_id',
        'active',
    ];

  protected $appends = ['role',"nbunread"];
    protected $hidden =['password'];

    public function getNbunreadAttribute()
    {
      $count= Chat::where([['status', 0],['receiver_id',$this->id]])->get();
        return count($count);
    }
    
    public function getRoleAttribute()
    {

        //   return $this->categories()->get();
        return Role::where('id', $this->role_id)->first();
    }
 
    public function role()
    {
        return $this->hasOne(Role::class);
    }

    public function demande()
    {
        return $this->hasMany(Demande::class);
    }
    public function chats()
    {
        return $this->hasMany(Chat::class,'sender_id','id');
    }

    public function resetpasswords()
    {
        return $this->hasOne(Resetpasswords::class);
    }

  


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
