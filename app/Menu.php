<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    // relacion de uno a muchos
    public function menus()
    {
        return $this->belongsTo('App\Menu', 'id_menu');
    }
}