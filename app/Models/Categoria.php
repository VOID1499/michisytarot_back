<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;


    protected $primaryKey = 'id';
    protected $table = 'categorias';
    public $timestamps = false;
    protected $guarded = ['id'];

    //protected $connection = '';


    //tiene muchos productos
    public function producto(){
        return $this->hasMany(Producto::class);
    }

}
