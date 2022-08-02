<?php

namespace App\Models;

use App\Models\Service;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table='categories';
    protected $primaryKey='id';
    protected $fillable=[
        'nom_category',
        'description',
        'img',
    ];
   
    public function services(){
        return $this->hasMany(Service::class);
    }

    
    public function translation(){
        return $this->hasMany(Translation::class, 'table_id','id');
    }
}
