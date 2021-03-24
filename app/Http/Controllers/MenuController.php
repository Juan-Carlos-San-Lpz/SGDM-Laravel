<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Menu;
use App\Helpers\JwtAuth;

class MenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy', 'showByUser']]);
    }
    public function index()
    {
        $menus = Menu::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'Menu' => $menus
        ], 200);
    }

    public function showByUser($id_tipo_usuario)
    {
        $menus = Menu::where('id_tipo_usuario', $id_tipo_usuario)->Join('permisos', 'menu.id_menu', '=', 'permisos.id_menu')->get(); //load es la clase que se crea en el modelo

        // var_dump($menus);
        // die();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'Menu' => $menus
        ], 200);
    }

    public function show($id)
    {
        $params =  Menu::where('id_menu', $id)->get();
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
                'nombre_menu' => 'required|string',
                'icono' => 'required|string',
                'url_menu' => 'required|string',
                'posicion_menu' => 'required|alpha_num',
            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el  menu'
                );
            } else {
                $menu = new Menu();
                $menu->nombre_menu = $params_array['nombre_menu'];
                $menu->url_menu = $params_array['url_menu'];
                $menu->icono = $params_array['icono'];
                $menu->posicion_menu = $params_array['posicion_menu'];
                $menu->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'menu' => $menu
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
                'nombre_menu' => 'string',
                'url_menu' => 'string',
                'icono' => 'string',
                'posicion_menu' => 'alpha_num'
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
                $menu = new Menu();


                $menu->id_menu = $params_array['id_menu'];
                $menu->nombre_menu = $params_array['nombre_menu'];
                $menu->url_menu = $params_array['url_menu'];
                $menu->icono = $params_array['icono'];
                $menu->posicion_menu = $params_array['posicion_menu'];

                // Actualizar usuario
                $id = $params_array['id_menu'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = Menu::where('id_menu', $id)->update($params_array);
                // var_dump($grupo_update);
                // die();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'menu' => $menu,
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
        $genero = Menu::where('id_menu', $id);

        // var_dump($grupo);
        // die();
        if (!empty($genero)) {
            // Borrarlo
            $genero->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'genero' => $genero
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