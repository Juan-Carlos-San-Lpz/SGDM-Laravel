<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\PermisosMenu;
use App\Helpers\JwtAuth;

use Illuminate\Http\Request;

class PermisosMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy', 'showByUser', 'store']]);
    }

    public function index()
    {
        $permisos = PermisosMenu::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'permisos' => $permisos
        ], 200);
    }

    public function showByUser($id_tipo_usuario)
    {
        $permisos = PermisosMenu::where('id_tipo_usuario', $id_tipo_usuario)->Join('menu', 'menu.id_menu', '=', 'permisos.id_menu')->get(); //load es la clase que se crea en el modelo

        var_dump($permisos);
        die();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'permisos' => $permisos
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
                'id_tipo_usuario' => 'required|alpha_num',
                'id_menu' => 'required|alpha_num',
                'orden' => 'required|alpha_num',
                'acceso' => 'required|alpha_num'
            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el  menu'
                );
            } else {

                $permiso = new PermisosMenu();
                $permiso->id_tipo_usuario = $params_array['id_tipo_usuario'];
                $permiso->id_menu = $params_array['id_menu'];
                $permiso->orden = $params_array['orden'];
                $permiso->acceso = $params_array['acceso'];
                $permiso->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'permisos' => $permiso
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
                'id_tipo_usuario' => 'required|alpha_num',
                'id_menu' => 'required|alpha_num',
                'orden' => 'required|alpha_num',
                'acceso' => 'required|alpha_num'
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
                $permiso = new PermisosMenu();


                $permiso->id_tipo_usuario = $params_array['id_tipo_usuario'];
                $permiso->id_menu = $params_array['id_menu'];
                $permiso->orden = $params_array['orden'];
                $permiso->acceso = $params_array['acceso'];

                // Actualizar usuario
                $id = $params_array['id_permisos'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = PermisosMenu::where('id_permisos', $id)->update($params_array);
                // var_dump($grupo_update);
                // die();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'permisos' => $permiso,
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
        $permisos = PermisosMenu::where('id_permisos', $id);

        // var_dump($grupo);
        // die();
        if (!empty($permisos)) {
            // Borrarlo
            $permisos->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'permisos' => $permisos
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