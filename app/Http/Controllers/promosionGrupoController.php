<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\PromosionGrupo;
use App\Helpers\JwtAuth;

class promosionGrupoController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy', 'showByUser', 'store']]);
    }
    public function index()
    {
        $promosionGrupo = PromosionGrupo::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'promosionGrupo' => $promosionGrupo
        ], 200);
    }

    public function store(Request $request)
    {
        // Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true); // array
        // var_dump($params_array);
        // die();

        if (!empty($params_array)) {

            // Validar datos
            $validate = \Validator::make($params_array, [
                'id_grupo_musica' => 'required|alpha_num',
                'horas_previas' => 'required|alpha_num',
                'descuento' => 'required|alpha_num'
            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el  menu'
                );
            } else {

                $promosionGrupo = new PromosionGrupo();
                $promosionGrupo->id_grupo_musica = $params_array['id_grupo_musica'];
                $promosionGrupo->horas_previas = $params_array['horas_previas'];
                $promosionGrupo->descuento = $params_array['descuento'];

                $promosionGrupo->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'promosionGrupo' => $promosionGrupo
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'No se ha enviando ninguna menu'
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
                'id_grupo_musica' => 'required|alpha_num',
                'horas_previas' => 'required|alpha_num',
                'descuento' => 'required|alpha_num'
            ]);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'El menu no se a actualizado',
                    'errors' => $validate->errors()
                );
            } else {

                // crear el genero
                $promosionGrupo = new PromosionGrupo();
                $promosionGrupo->id_grupo_musica = $params_array['id_grupo_musica'];
                $promosionGrupo->horas_previas = $params_array['horas_previas'];
                $promosionGrupo->descuento = $params_array['descuento'];
                // Actualizar usuario
                $id = $params_array['id_promosion_grupo'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = PromosionGrupo::where('id_promosion_grupo', $id)->update($params_array);
                // var_dump($grupo_update);
                // die();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
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
        $promosionGrupo = PromosionGrupo::where('id_promosion_grupo', $id);

        // var_dump($grupo);
        // die();
        if (!empty($promosionGrupo)) {
            // Borrarlo
            $promosionGrupo->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'promosionGrupo' => $promosionGrupo
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'No se ha encontrado ningun menu'
            );
        }
        return response()->json($data, $data['code']);
    }
}
