<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'productos';
    public $timestamps = true;
    protected $guarded = ['id'];


    //pertecene  a una categoria
    public function categoria(){
        return $this->belongsTo(Categoria::class,'categorias_id');
    }


    //tiene muchas imagenes
    public function imagen(){
        return $this->hasMany(Images::class,'images_id');
    }
}
