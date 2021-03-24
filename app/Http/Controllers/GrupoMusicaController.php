<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\GrupoMusica;
use App\Helpers\JwtAuth;
use Symfony\Component\VarDumper\VarDumper;

class GrupoMusicaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'getImage', 'getGrupoByGenero', 'getGrupoByUsuario', 'store', 'getGrupo', 'update', 'destroy']]);
    }
    public function pruebas(Request $request)
    {
        return "Accion de pruebas de Grupo controller";
    }
    public function index()
    {
        // $grupos = GrupoMusica::all()->load('generos'); //load es la clase que se crea en el modelo
        $grupos = GrupoMusica::Join('genero_grupo', 'genero_grupo.id_genero_grupo', '=', 'grupo_musica.id_genero_grupo')->get();;

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'grupos' => $grupos
        ], 200);
    }

    public function show($id)
    {
        $params =  GrupoMusica::where('id_grupo_musica', $id)->get()->load('generos');
        $grupos = json_decode($params); // objeto
        // $grupos = GrupoMusica::where('id_grupo_musica',$id);

        if ($grupos) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'grupos' => $grupos
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El grupo no existe'
            ];
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {

        // Recoger los datos por post
        $json = $request->input('json', null);
        $params = json_decode($json); // objeto
        $params_array = json_decode($json, true); // array


        if (!empty($params_array)) {
            // Conseguir usuario identificado
            $user = $this->getIdentity($request);
            // Validar datos
            $validate = \Validator::make($params_array, [
                'nombre_grupo_musica' => 'required',
                'no_integrantes' => 'required',
                'id_genero_grupo' => 'required',
                'historia_grupo' => 'required',
                'precio_grupo' => 'required',
                'imagen_grupo' => 'string'

            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el Grupo musical'
                );
            } else {

                $grupo = new GrupoMusica();
                // var_dump($params_array);
                // die();
                $grupo->nombre_grupo_musica = $params_array['nombre_grupo_musica'];
                $grupo->no_integrantes = $params_array['no_integrantes'];
                $grupo->id_genero_grupo = $params_array['id_genero_grupo'];
                $grupo->historia_grupo = $params_array['historia_grupo'];
                $grupo->precio_grupo = $params_array['precio_grupo'];
                $grupo->imagen_grupo = $params_array['imagen_grupo'];
                $grupo->id_usuario = $user->sub;
                $grupo->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'genero' => $grupo
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'No se ha enviando ningun grupo musical'
            );
        }
        // Devolver resultado
        return response()->json($data);
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
                'nombre_grupo_musica' => 'required',
                'no_integrantes' => 'required',
                'id_genero_grupo' => 'required',
                'historia_grupo' => 'required',
                'precio_grupo' => 'required',
                'imagen_grupo' => 'string'

            ]);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'El grupo no se a actualizado',
                    'errors' => $validate->errors()
                );
            } else {

                // var_dump($params_array);
                // die();
                // crear el grupo
                $grupo = new GrupoMusica();
                // var_dump($params_array);
                // die();
                $grupo->nombre_grupo_musica = $params_array['nombre_grupo_musica'];
                $grupo->no_integrantes = $params_array['no_integrantes'];
                $grupo->id_genero_grupo = $params_array['id_genero_grupo'];
                $grupo->historia_grupo = $params_array['historia_grupo'];
                $grupo->precio_grupo = $params_array['precio_grupo'];
                $grupo->imagen_grupo = $params_array['imagen_grupo'];
                $grupo->id_usuario = $params_array['id_usuario'];

                // Actualizar usuario
                $id = $params_array['id_grupo_musica'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = GrupoMusica::where('id_grupo_musica', $id)->update($params_array);
                // var_dump($grupo_update);
                // die();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'usuario' => $grupo,
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
        $grupo = GrupoMusica::where('id_grupo_musica', $id);

        // var_dump($grupo);
        // die();
        if (!empty($grupo)) {
            // Borrarlo
            $grupo->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'EL grupo se ha eliminado',
                'grupo' => $grupo
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'No se ha encontrado ningun grupo'
            );
        }
        return response()->json($data, $data['code']);
    }

    private function getIdentity($request)
    {
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }

    public function upload(Request $request)
    {
        // Recoger datos de la peticion
        $image = $request->file('file');

        // guardar la image
        if (!$image) {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'Error al subir imagen'
            );
        } else {

            $image_name = time() . $image->getClientOriginalName();
            \Storage::disk('imgGrupos')->put($image_name, \File::get($image));
            $data = array(
                'status' => 'success',
                'code' => 200,
                'image' => $image_name
            );
        }
        // devolver resultados

        return response()->json($data, $data['code']);
    }

    public function getImage($filename)
    {
        $isset = \Storage::disk('imgGrupos')->exists($filename);
        if ($isset) {

            $file = \Storage::disk('imgGrupos')->get($filename);
            return new Response($file, 200);
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'menssage' => 'La imagen no existe'
            );
            return response()->json($data, $data['code']);
        }
    }

    public function getGrupoByGenero($id)
    {
        $grupos =  GrupoMusica::where('id_genero_grupo', $id)->get();

        // var_dump($grupos);
        // die();

        return response()->json([
            'status' => 'success',
            'grupos' => $grupos
        ], 200);
    }

    public function getGrupoByUsuario($id)
    {
        $grupos =  GrupoMusica::where('id_usuario', $id)->get();

        return response()->json([
            'status' => 'success',
            'grupos' => $grupos
        ], 200);
    }

    public function getGrupo($id)
    {
        // $grupos =  GrupoMusica::where('id_grupo_musica', $id)->get();
        $grupos =  GrupoMusica::where('id_grupo_musica', $id)->Join('genero_grupo', 'genero_grupo.id_genero_grupo', '=', 'grupo_musica.id_genero_grupo')->get();
        // var_dump($grupos);
        // die();

        return response()->json([
            'status' => 'success',
            'info' => 'Esto es lo que biene de la consulta con los generos',
            'grupos' => $grupos
        ], 200);
    }
}