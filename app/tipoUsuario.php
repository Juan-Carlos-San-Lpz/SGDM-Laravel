<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    protected $table = 'tipo_usuario';
    // relacion de uno a muchos
    public function tarjetas()
    {
        return $this->belongsTo('App\TipoUsuario', 'id_tipo_usuario ');
    }
}