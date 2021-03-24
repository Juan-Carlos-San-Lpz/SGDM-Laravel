<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GeneroGrupo;
use App\GrupoMusica;

class PruebasController extends Controller
{
    public function index()
    {
        $titulo = 'Animales';
        $animales = ['perro', 'gato', 'loro'];

        return view('pruebas.index', array(
            'titulo' => $titulo,
            'animales' => $animales
        ));
    }

    public function testOrm()
    {
        $grupos = GrupoMusica::all();
        foreach ($grupos as $grupo) {

            echo "<h1>" . $grupo->nombre_grupo_musica . "</h1>";
            echo "<h2>" . $grupo->no_integrantes . "</h2>";
            echo "<h3>{$grupo->generos->nombre_genero}</h3>";
        }

        die();
    }
}