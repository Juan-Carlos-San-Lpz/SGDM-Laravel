<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\SubMenu;
use App\Helpers\JwtAuth;

class SubMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy', 'showByUser', 'store']]);
    }

    public function index()
    {
        $submenus = SubMenu::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'SubMenu' => $submenus
        ], 200);
    }

    public function showSubMenuByUser($id_menu)
    {
        $submenus = SubMenu::where('id_menu', $id_menu)->Join('permisos_submenu', 'submenu.id_submenu', '=', 'permisos_submenu.id_submenu')->get(); //load es la clase que se crea en el modelo

        var_dump($submenus);
        die();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'Menu' => $submenus
        ], 200);
    }

    public function show($id)
    {
        $params =  SubMenu::where('id_menu', $id)->get();
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
        // var_dump($params_array);
        // die();

        if (!empty($params_array)) {

            // Validar datos
            $validate = \Validator::make($params_array, [
                'id_menu' => 'required|alpha_num',
                'nombre_submenu' => 'required|string',
                'url_submenu' => 'required|string',
                'posicion_submenu' => 'required|alpha_num'
            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el  menu'
                );
            } else {
                $submenu = new SubMenu();
                $submenu->id_menu  = $params_array['id_menu'];
                $submenu->nombre_submenu = $params_array['nombre_submenu'];
                $submenu->url_submenu = $params_array['url_submenu'];
                $submenu->posicion_submenu = $params_array['posicion_submenu'];
                $submenu->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'submenu' => $submenu
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
                'id_menu' => 'required|alpha_num',
                'nombre_submenu' => 'required|string',
                'url_submenu' => 'required|string',
                'posicion_submenu' => 'required|alpha_num'
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
                $submenu = new SubMenu();
                $submenu->id_menu  = $params_array['id_menu'];
                $submenu->nombre_submenu = $params_array['nombre_submenu'];
                $submenu->url_submenu = $params_array['url_submenu'];
                $submenu->posicion_submenu = $params_array['posicion_submenu'];

                // Actualizar usuario
                $id = $params_array['id_submenu'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = SubMenu::where('id_submenu', $id)->update($params_array);
                // var_dump($grupo_update);
                // die();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'submenu' => $submenu,
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
        $submenu = SubMenu::where('id_submenu', $id);

        // var_dump($grupo);
        // die();
        if (!empty($submenu)) {
            // Borrarlo
            $submenu->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'submenu' => $submenu
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