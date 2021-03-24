<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Helpers\JwtAuth;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['destroy', 'index', 'registro', 'login', 'updateByUser', 'update', 'upload', 'getImage', 'detalle']]);
    }
    public function pruebas(Request $request)
    {
        return "Accion de pruebas de suario controller";
    }
    public function index()
    {
        $usuarios = User::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'usuarios' => $usuarios
        ], 200);
    }
    public function registro(Request $request)
    {
        // recoger los datos del usuaro por post

        $json = $request->input('json', null);
        $params = json_decode($json); // objeto
        $params_array = json_decode($json, true); // array

        if (!empty($params) && !empty($params_array)) {

            // Limpiar datos

            $params_array = array_map('trim', $params_array);

            // validar datos

            $validate = \Validator::make($params_array, [
                'nombre_usuario' =>     'required|alpha',
                'id_tipo_usuario' =>    'required|alpha_num',
                'tel_usuario' =>        'required|alpha_num',
                'appat_usuario' =>      'required|alpha',
                'apmat_usuario' =>      'required|alpha',
                // 'id_direccion' =>       'required|alpha_num',
                // 'id_tarjeta' =>         'required|alpha_num',
                'email_usuario' =>      'required|email|unique:usuario',
                'password' =>           'required'
                // 'image' =>              'required|image|mimes:png,jpg,jpeg,gif'
            ]);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'El usuario no se ha registrado',
                    'errors' => $validate->errors()
                );
            } else {
                // cifrar la contraseña
                $pwd = hash('sha256', $params->password);

                // crear el usuario
                $user = new User();
                $user->nombre_usuario = $params_array['nombre_usuario'];
                $user->appat_usuario = $params_array['appat_usuario'];
                $user->apmat_usuario = $params_array['apmat_usuario'];
                $user->tel_usuario = $params_array['tel_usuario'];
                // $user->id_direccion = $params_array['id_direccion'];
                $user->id_tarjeta = $params_array['id_tarjeta'];
                $user->id_tipo_usuario = $params_array['id_tipo_usuario'];
                $user->email_usuario = $params_array['email_usuario'];
                $user->password = $pwd;
                // $user->image = $params_array['image'];


                // guardar usuario

                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'menssage' => 'El usuario se ha registrado correctamente'
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
    public function login(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        //Recibir datos por Post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        // Validar los datos
        $validate = \Validator::make($params_array, [
            'email_usuario' => 'required',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'menssage' => 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        } else {
            // cifrar el pasword
            $pwd = hash('sha256', $params->password);
            // volver token
            $signup = $jwtAuth->signup($params->email_usuario, $pwd);

            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->signup($params->email_usuario, $pwd, true);
            }
        }



        return response()->json($signup, 200);
    }

    public function updateByUser(Request $request)
    {
        // recoger los datos del usuaro por post

        $json = $request->input('json', null);
        $params = json_decode($json); // objeto
        $params_array = json_decode($json, true); // array

        // var_dump($params_array);

        if (!empty($params) && !empty($params_array)) {

            // Limpiar datos

            $params_array = array_map('trim', $params_array);

            // validar datos

            $validate = \Validator::make($params_array, [
                'nombre_usuario' => 'required|string',
                'id_tipo_usuario' => 'required|alpha_num',
                'appat_usuario' => 'required|alpha',
                'apmat_usuario' => 'required|alpha',
                'tel_usuario' => 'required|alpha_num',
                // 'id_direccion' => 'required|alpha_num',
                // 'id_tarjeta' => 'required|alpha_num',
                // 'password' => ''
                // 'email_usuario' => 'required|email|unique:usuario,' . $params_array['id_usuario']
                'image' => ''
            ]);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'El usuario no se a actualizado',
                    'errors' => $validate->errors()
                );
            } else {
                // cifrar la contraseña
                $pwd = hash('sha256', $params_array['password']);

                // var_dump($params_array);
                // die();
                // crear el usuario
                $user = new User();
                $user->id_usuario = $params_array['id_usuario'];
                $user->nombre_usuario = $params_array['nombre_usuario'];
                $user->appat_usuario = $params_array['appat_usuario'];
                $user->apmat_usuario = $params_array['apmat_usuario'];
                $user->tel_usuario = $params_array['tel_usuario'];
                // $user->id_direccion = $params_array['id_direccion'];
                $user->id_tarjeta = $params_array['id_tarjeta'];
                $user->id_tipo_usuario = $params_array['id_tipo_usuario'];
                $user->email_usuario = $params_array['email_usuario'];
                // $user->password = $params_array['tel_usuario'];
                $user->image = $params_array['image'];



                // Actualizar usuario

                $user_update = User::where('id_usuario', $user->id_usuario)->update($params_array);
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'usuario' => $user,
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

    public function update(Request $request)
    {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        // var_dump($params_array);
        // die();

        if ($checkToken && !empty($params_array)) {


            // sacar usuario identificado
            $usuario = $jwtAuth->checkToken($token, true);

            // Validar los datos
            $validate = \Validator::make($params_array, [
                'nombre_usuario' => 'required|string',
                'id_tipo_usuario' => 'required|alpha_num',
                'appat_usuario' => 'required|alpha',
                'apmat_usuario' => 'required|alpha',
                'tel_usuario' => 'required|alpha_num',
                'id_direccion' => 'required|alpha_num',
                'id_tarjeta' => 'required|alpha_num',
                'email_usuario' => 'required|email|unique:usuario,' . $usuario->sub,
                'image' => 'required|image|mimes:png,jpg,jpeg,gif',
                'password' => ''
            ]);
            // quitar los datos que no se quieren actualizar
            unset($params_array['id_usuario']);
            // unset($params_array['id_tipo_usuario']);
            // unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);
            // actualizar bd
            $user_update = User::where('id_usuario', $usuario->sub)->update($params_array);
            // devovlver array con resultado
            $data = array(
                'status' => 'success',
                'code' => 200,
                'usuario' => $usuario,
                'changes' => $params_array
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'El usuario No esta identificado'
            );
        }

        return response()->json(
            $data,
            $data['code']
        );
    }

    public function upload(Request $request)
    {
        // Recoger datos de la peticion
        $image = $request->file('file');

        if (!$image) {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'Error al subir imagen'
            );
        } else {

            $image_name = time() . $image->getClientOriginalName();
            \Storage::disk('imgUsarios')->put($image_name, \File::get($image));
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
        $isset = \Storage::disk('imgUsarios')->exists($filename);
        if ($isset) {

            $file = \Storage::disk('imgUsarios')->get($filename);
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

    public function detalle($id_usuario)
    {
        $params =  User::where('id_usuario', $id_usuario)->get();
        $usuario = json_decode($params); // objeto

        if ($usuario) {
            $data = array(
                'status' => 'success',
                'code' => 200,
                'usuario' => $usuario
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'menssage' => 'El usuario no existe'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request)
    {

        // Conseguir el grupo
        $usuario = User::where('id_usuario', $id);

        // var_dump($grupo);
        // die();
        if (!empty($usuario)) {
            // Borrarlo
            $usuario->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'EL Usuario se ha eliminado',
                'usuario' => $usuario
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'No se ha encontrado ningun usuario'
            );
        }
        return response()->json($data, $data['code']);
    }
}