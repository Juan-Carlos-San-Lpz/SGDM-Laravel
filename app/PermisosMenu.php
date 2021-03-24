<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisosMenu extends Model
{
    protected $table = 'permisos';
    // relacion de uno a muchos
    public function permiso()
    {
        return $this->belongsTo('App\PermisosMenu', 'id_permisos');
    }
}