<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusPedido extends Model
{
    protected $table = 'status_pedido';
    // relacion de uno a muchos
    public function statusPedidos()
    {
        return $this->belongsTo('App\StatusPedido', 'id_status_pedido');
    }
}