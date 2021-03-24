<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\PermisosSubMenu;
use App\Helpers\JwtAuth;


class permisosSubMenuController extends Controller
{
    public function index()
    {
        $permisosSubMenu = PermisosSubMenu::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'permisosSubMenu' => $permisosSubMenu
        ], 200);
    }

    public function showByUser($id_menu)
    {
        $permisosSubMenu = PermisosSubMenu::where('id_menu', $id_menu)->get(); //load es la clase que se crea en el modelo

        var_dump($permisosSubMenu);
        die();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'permisosSubMenu' => $permisosSubMenu
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
                'id_submenu' => 'required|alpha_num',
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

                $permisoSubMenu = new PermisosSubMenu();
                $permisoSubMenu->id_tipo_usuario = $params_array['id_tipo_usuario'];
                $permisoSubMenu->id_submenu = $params_array['id_submenu'];
                $permisoSubMenu->orden = $params_array['orden'];
                $permisoSubMenu->acceso = $params_array['acceso'];
                $permisoSubMenu->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'permisoSubMenu' => $permisoSubMenu
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
                'id_submenu' => 'required|alpha_num',
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
                $permisoSubMenu = new PermisosSubMenu();
                $permisoSubMenu->id_tipo_usuario = $params_array['id_tipo_usuario'];
                $permisoSubMenu->id_submenu = $params_array['id_submenu'];
                $permisoSubMenu->orden = $params_array['orden'];
                $permisoSubMenu->acceso = $params_array['acceso'];
                // Actualizar usuario
                $id = $params_array['id_permisos_submenu'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = PermisosSubMenu::where('id_permisos_submenu', $id)->update($params_array);
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
        $permisosSubMenu = PermisosSubMenu::where('id_permisos_submenu', $id);

        // var_dump($grupo);
        // die();
        if (!empty($permisosSubMenu)) {
            // Borrarlo
            $permisosSubMenu->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'permisosSubMenu' => $permisosSubMenu
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
