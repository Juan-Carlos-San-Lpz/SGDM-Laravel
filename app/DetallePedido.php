<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    // relacion de uno a muchos
    public function detallePedidos()
    {
        return $this->belongsTo('App\DetallePedido', 'id_detalle_pedido');
    }
}