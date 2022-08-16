<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;


    const ADMIN_ID = 6;
    protected $table = 'chats';

    protected $primaryKey = 'id';
    protected $fillable = [
        'invoices_id',
        'sender_id',
        'message_id',
        'receiver_id',
        'message',
        'status'
    ];

    protected $appends = ["invoices", "files"];


    public function getFilesAttribute()
    {
        return Files::where('chat_id', $this->id)->first();
    }


    public function getInvoicesAttribute()
    {
        return Facture::where('id', $this->invoices_id)->first();
    }
    /*
   public function getuserAttribute()
   {
       return User::where('id', $this->user_id)->first();
   }
   public function getAgentAttribute()
   {
       if ($this->agent_id > 0) {
           return Agents::where('id', $this->agent_id)->first();
       } else {
           return [];
       }
   } */

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function sender(){
        return $this->belongsTo(User::class,'sender_id','id');
    }

    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id','id');
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

}
