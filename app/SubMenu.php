<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    protected $table = 'submenu';
    // relacion de uno a muchos
    public function submenus()
    {
        return $this->belongsTo('App\SubMenu', 'id_submenu');
    }
}