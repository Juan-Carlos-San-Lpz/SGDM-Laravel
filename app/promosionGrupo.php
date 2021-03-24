<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromosionGrupo extends Model
{
    protected $table = 'promosion_grupo';
    // relacion de uno a muchos
    public function promosionGrupos()
    {
        return $this->belongsTo('App\PromosionGrupo', 'id_promosion_grupo ');
    }
}