<?php

namespace App\Models;

use App\Models\Agents;
use App\Models\Demande;
use App\Models\Category;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'nom_service',
        'description',
        'img',
        'banner',
        'montant_min',
        'category_id'
    ];
    protected $appends = ['category'];

   
    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
    public function agent()
    {
        return $this->hasMany(Agents::class);
    }

    public function getCategoryAttribute()
    {
       
    //   return $this->categories()->get();
        return Category::where('id', $this->category_id)->first();
    }

    public function demande()
    {
        return $this->hasMany(Demande::class);
    }

    public function translation(){
        return $this->hasMany(Translation::class, 'table_id','id');
    }

 
}