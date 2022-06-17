<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'images';
    public $timestamps = false;
    protected $guarded = ['id'];


    //pertenece a un producto
    public function producto(){
        return $this->belongsTo(Producto::class);
    }


}
