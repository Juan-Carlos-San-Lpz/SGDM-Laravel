<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\DetallePedido;
use App\Helpers\JwtAuth;

class detallePedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'update', 'destroy', 'showByUser', 'store']]);
    }
    public function index()
    {
        $detallePedido = DetallePedido::all(); //load es la clase que se crea en el modelo

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'detallePedido' => $detallePedido
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
                'id_pedido' => 'required|alpha_num',
                'id_grupo_musica' => 'required|alpha_num',
                'precio_total' => 'required|string',
                'fecha_evento' => 'required|string'
            ]);
            // Guardar Genero
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'menssage' => 'No se ha guardado el  menu'
                );
            } else {

                $DetallePedido = new DetallePedido();
                $DetallePedido->id_pedido   = $params_array['id_pedido'];
                $DetallePedido->id_grupo_musica  = $params_array['id_grupo_musica'];
                $DetallePedido->precio_total = $params_array['precio_total'];
                $DetallePedido->fecha_evento  = $params_array['fecha_evento'];
                $DetallePedido->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'DetallePedido' => $DetallePedido
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
                'id_pedido' => 'required|alpha_num',
                'id_grupo_musica' => 'required|alpha_num',
                'precio_total' => 'required|string',
                'fecha_evento' => 'required|string'
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


                $DetallePedido = new DetallePedido();
                $DetallePedido->id_pedido   = $params_array['id_pedido'];
                $DetallePedido->id_grupo_musica  = $params_array['id_grupo_musica'];
                $DetallePedido->precio_total = $params_array['precio_total'];
                $DetallePedido->fecha_evento  = $params_array['fecha_evento'];
                // Actualizar usuario
                $id = $params_array['id_detalle_pedido'];
                // var_dump($params_array['id_grupo_musica']);
                // die();

                $grupo_update = DetallePedido::where('id_detalle_pedido', $id)->update($params_array);
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
        $detallePedido = DetallePedido::where('id_detalle_pedido', $id);

        // var_dump($grupo);
        // die();
        if (!empty($detallePedido)) {
            // Borrarlo
            $detallePedido->delete();
            // Devolver respuesta
            $data = array(
                'status' => 'success',
                'code' => 200,
                'menssage' => 'El menu se ha eliminado',
                'detallePedido' => $detallePedido
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