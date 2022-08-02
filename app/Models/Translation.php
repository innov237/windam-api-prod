<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'service_id',
        'label',
        'description',
        'locale'
    ];
    protected $table = 'translation';


    public function categories()
    {
        return $this->belongsTo(Category::class, 'id', 'table_id');
    }
    
   public function services(){
        return $this->belongsTo(Service::class, 'id','table_id');
    }


}
