<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupoMusica extends Model
{
    protected $table = 'grupo_musica';
    // relacion de uno a muchos
    public function generos()
    {
        return $this->belongsTo('App\GeneroGrupo', 'id_grupo_musica', 'id_genero_grupo');
    }
}
