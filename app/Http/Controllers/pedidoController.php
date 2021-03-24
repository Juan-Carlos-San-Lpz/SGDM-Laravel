<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Pedido;
use App\Helpers\JwtAuth;

class pedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy', 'showByUser', 'store']]);
    }
    public function index()
    {
        $pedido = Pedido::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'pedido' => $pedido
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
                'id_usuario' => 'required|alpha_num',
                'fecha_registro' => 'required|string',
                'fecha_expiracion' => 'required|string',
                'id_fecha_pago' => 'required|alpha_num',
                'id_status_pedido' => 'required|alpha_num',
                'total' => 'required|string',
            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el  menu'
                );
            } else {

                $Pedido = new Pedido();
                $Pedido->id_usuario  = $params_array['id_usuario'];
                $Pedido->fecha_registro = $params_array['fecha_registro'];
                $Pedido->fecha_expiracion = $params_array['fecha_expiracion'];
                $Pedido->id_fecha_pago  = $params_array['id_fecha_pago'];
                $Pedido->id_status_pedido   = $params_array['id_status_pedido'];
                $Pedido->total   = $params_array['total'];
                $Pedido->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'Pedido' => $Pedido
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
                'id_usuario' => 'required|alpha_num',
                'fecha_registro' => 'required|string',
                'fecha_expiracion' => 'required|string',
                'id_fecha_pago' => 'required|alpha_num',
                'id_status_pedido' => 'required|alpha_num',
                'total' => 'required|string',
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

                $Pedido = new Pedido();
                $Pedido->id_usuario  = $params_array['id_usuario'];
                $Pedido->fecha_registro = $params_array['fecha_registro'];
                $Pedido->fecha_expiracion = $params_array['fecha_expiracion'];
                $Pedido->id_fecha_pago  = $params_array['id_fecha_pago'];
                $Pedido->id_status_pedido   = $params_array['id_status_pedido'];
                $Pedido->total   = $params_array['total'];
                // Actualizar usuario
                $id = $params_array['id_pedido'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = Pedido::where('id_pedido', $id)->update($params_array);
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
        $pedido = Pedido::where('id_pedido', $id);

        // var_dump($grupo);
        // die();
        if (!empty($pedido)) {
            // Borrarlo
            $pedido->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'pedido' => $pedido
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