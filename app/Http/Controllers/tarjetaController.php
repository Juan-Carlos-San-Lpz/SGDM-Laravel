<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Tarjeta;
use App\Helpers\JwtAuth;

class tarjetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy', 'showByUser', 'store']]);
    }
    public function index()
    {
        $tarjeta = Tarjeta::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'tarjeta' => $tarjeta
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
                'propeietarios_tarjeta' => 'required|string',
                'no_tarjeta' => 'required|alpha_num',
                'fecha_expiracion' => 'required|string'
            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el  menu'
                );
            } else {

                $tarjeta = new Tarjeta();
                $tarjeta->propeietarios_tarjeta = $params_array['propeietarios_tarjeta'];
                $tarjeta->no_tarjeta = $params_array['no_tarjeta'];
                $tarjeta->fecha_expiracion = $params_array['fecha_expiracion'];

                $tarjeta->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'tarjeta' => $tarjeta
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
                'propeietarios_tarjeta' => 'required|string',
                'no_tarjeta' => 'required|alpha_num',
                'fecha_expiracion' => 'required|string'
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
                $tarjeta = new Tarjeta();
                $tarjeta->propeietarios_tarjeta = $params_array['propeietarios_tarjeta'];
                $tarjeta->no_tarjeta = $params_array['no_tarjeta'];
                $tarjeta->fecha_expiracion = $params_array['fecha_expiracion'];
                // Actualizar usuario
                $id = $params_array['id_tarjeta'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = Tarjeta::where('id_tarjeta', $id)->update($params_array);
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
        $tarjeta = Tarjeta::where('id_tarjeta', $id);

        // var_dump($grupo);
        // die();
        if (!empty($tarjeta)) {
            // Borrarlo
            $tarjeta->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'tarjeta' => $tarjeta
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