<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    // relacion de uno a muchos
    public function pedidos()
    {
        return $this->belongsTo('App\Pedido', 'id_pedido');
    }
}