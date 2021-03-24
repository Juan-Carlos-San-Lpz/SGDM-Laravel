<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\GeneroGrupo;

class GeneroMusicaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy']]);
    }

    public function index()
    {
        $generos = GeneroGrupo::all();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'generos' => $generos
        ]);
    }

    public function show($id)
    {
        $params =  GeneroGrupo::where('id_genero_grupo', $id)->get();
        $generos = json_decode($params); // objeto
        // $generos = GeneroGrupo::find($id);

        if ($generos) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'generos' => $generos
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El grupo no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        // Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true); // array

        if (!empty($params_array)) {

            // Validar datos
            $validate = \Validator::make($params_array, [
                'nombre_genero' => 'required|alpha'
            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el genero musical'
                );
            } else {
                $genero = new GeneroGrupo();
                $genero->nombre_genero = $params_array['nombre_genero'];
                $genero->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'genero' => $genero
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'No se ha enviando ninguna categoria'
            );
        }
        // Devolver resultado
        return response()->json($data, $data['code']);
    }

    public function update(Request $request)
    {
        // recoger los datos del usuaro por post

        $json = $request->input('json', null);
        $params = json_decode($json); // objeto
        $params_array = json_decode($json, true); // array

        // var_dump($params_array);
        // die();

        if (!empty($params) && !empty($params_array)) {

            // Limpiar datos

            $params_array = array_map('trim', $params_array);

            // validar datos

            $validate = \Validator::make($params_array, [
                'id_genero_grupo' => 'required',
                'nombre_genero' => 'required'
            ]);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'El genero no se a actualizado',
                    'errors' => $validate->errors()
                );
            } else {

                // crear el genero
                $genero = new GeneroGrupo();


                $genero->id_genero_grupo = $params_array['id_genero_grupo'];
                $genero->no_integrantes = $params_array['nombre_genero'];

                // Actualizar usuario
                $id = $params_array['id_genero_grupo'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = GeneroGrupo::where('id_genero_grupo', $id)->update($params_array);
                // var_dump($grupo_update);
                // die();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'genero' => $genero,
                    'changes' => $params_array
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'Los datos enviados no son correctos'
            );
        }
        return response()->json($data, $data['code']);
    }


    public function destroy($id, Request $request)
    {

        // Conseguir el grupo
        $genero = GeneroGrupo::where('id_genero_grupo', $id);

        // var_dump($grupo);
        // die();
        if (!empty($genero)) {
            // Borrarlo
            $genero->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El genero se ha eliminado',
                'genero' => $genero
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'No se ha encontrado ningun genero'
            );
        }
        return response()->json($data, $data['code']);
    }
}
