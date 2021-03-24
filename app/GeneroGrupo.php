<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneroGrupo extends Model
{
    protected $table = 'genero_grupo';

    public function grupos()
    {
        return $this->belongsTo('App\GrupoMusica', 'id_genero_grupo', 'id_grupo_musica');
    }
}