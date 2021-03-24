<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    protected $table = 'tarjeta';
    // relacion de uno a muchos
    public function tarjetas()
    {
        return $this->belongsTo('App\Tarjeta', 'id_tarjeta');
    }
}