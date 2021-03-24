<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Direccion;
use App\Helpers\JwtAuth;

class direccionController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy', 'showByUser', 'store']]);
    }
    public function index()
    {
        $direccion = Direccion::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'direccion' => $direccion
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
                'estado' => 'string',
                'municipio' => 'string',
                'colonia' => 'string',
                'cp' => 'string',
                'direccion' => 'string',
                'id_usuario' => 'alpha'
            ]);
            // Guardar Genero
            if (!$validate->fails()) {

                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado la direccion',
                    'error' => $validate->fails()
                );
            } else {

                $direccion = new Direccion();
                $direccion->estado   = $params_array['estado'];
                $direccion->municipio   = $params_array['municipio'];
                $direccion->colonia  = $params_array['colonia'];
                $direccion->cp = $params_array['cp'];
                $direccion->direccion  = $params_array['direccion'];
                $direccion->id_usuario  = $params_array['id_usuario'];
                $direccion->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'direccion' => $direccion
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
                'estado' => 'required|string',
                'municipio' => 'required|string',
                'colonia' => 'required|string',
                'cp' => 'required|string',
                'direccion' => 'required|string',
                'id_usuario' => 'required|string'
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


                $direccion = new Direccion();
                $direccion->estado   = $params_array['estado'];
                $direccion->municipio   = $params_array['municipio'];
                $direccion->colonia  = $params_array['colonia'];
                $direccion->cp = $params_array['cp'];
                $direccion->direccion  = $params_array['direccion'];
                $direccion->id_usuario  = $params_array['id_usuario'];
                // Actualizar usuario
                $id = $params_array['id_direccion'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = Direccion::where('id_direccion', $id)->update($params_array);
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
        $direccion = Direccion::where('id_direccion', $id);

        // var_dump($grupo);
        // die();
        if (!empty($direccion)) {
            // Borrarlo
            $direccion->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'direccion' => $direccion
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