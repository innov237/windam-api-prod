<?php

namespace App\Models;

use Chats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;



    protected $table = 'invoices';

    protected $primaryKey = 'id';
    protected $fillable = [
        'status'
    ];
    protected $appends=['paid','demande', 'totalAmount']; 

    public function getPaidAttribute(){
        $get= Transaction::where('invoices_id',$this->id)->first();
        if ($get->paid_date) {
            return true;
        }
        return false;
    }
    
      public function getTotalAmountAttribute()
    {
        
       /* if ($this->tva == 0) {
            return $this->montant;
        }*/

        $tva = ($this->montant * $this->tva) / 100;
        $total = $this->montant + $tva;
        return $total;
    }
  
    public function getDemandeAttribute()
    {
        return Demande::where('id', $this->demande_id)->first();
    }
  

    public function demande()
    {
        return $this->hasOne(demande::class);
    }

    public function chats()
    {
        return $this->hasMany(Chats::class);
    }

}


