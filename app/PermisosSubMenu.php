<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisosSubMenu extends Model
{
    protected $table = 'permisos_submenu';
    // relacion de uno a muchos
    public function permisoSubMenu()
    {
        return $this->belongsTo('App\PermisosSubMenu', 'id_permisos_submenu');
    }
}