<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\BD;
use App\User;

class JwtAuth
{
    public $key;

    public function __construct()
    {
        $this->key = 'esta_es_la_clave_de_carlos-123456';
    }
    public function signup($email_usuario, $password, $getToken = null)
    {

        //Buscar si existe el usuario con sus credenciales
        $user = User::Where([
            'email_usuario' => $email_usuario,
            'password' => $password
        ])->first();

        // comprobar si son correctas
        $signup = false;

        if (is_object($user)) {
            $signup = true;
        }
        // generar un token con los datos del usuario

        if ($signup) {
            $token = array(
                'sub' => $user->id_usuario,
                'email_usuario' => $user->email_usuario,
                'appat_usuario' => $user->appat_usuario,
                'apmat_usuario' => $user->apmat_usuario,
                'nombre_usuario' => $user->nombre_usuario,
                'id_tipo_usuario' => $user->id_tipo_usuario,
                'image' => $user->image,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            );

            // devolver los datos codificados o el token en funcion de un parametro
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
                'status' => 'error',
                'menssage' => 'El login ha fallado'
            );
        }


        return $data;
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $jwt = str_replace('"', '', $jwt);

            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }
}