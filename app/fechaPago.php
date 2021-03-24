<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FechaPago extends Model
{
    protected $table = 'fecha_pago';
    // relacion de uno a muchos
    public function fechaPagos()
    {
        return $this->belongsTo('App\FechaPago', 'id_fecha_pago');
    }
}