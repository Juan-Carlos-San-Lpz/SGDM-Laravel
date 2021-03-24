<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direccion';
    // relacion de uno a muchos
    public function direccion()
    {
        return $this->belongsTo('App\Direccion', 'id_direccion');
    }
}