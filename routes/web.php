<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// cargando clases
use App\Http\Middleware\apliAuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pruebas/{nombre?}', function ($nombre = null) {
    $texto = '<h1>Testo desde una ruta   </h1>';
    return view('pruebas', array(
        'texto' => $texto
    ));
});

Route::get('/animales', 'PruebasController@index');
Route::get('/testorm', 'PruebasController@testOrm');


// Rutas de prueba
// Route::get('/genero', 'GrupoMusicaController@pruebas@pruebas');
// Route::get('/grupo', 'GrupoMusicaController@pruebas');

// Rutas API
Route::post('/usuario/registro', 'UsuarioController@registro');
Route::post('/usuarios', 'UsuarioController@index');
Route::post('/usuario/login', 'UsuarioController@login');
Route::put('/usuario/update', 'UsuarioController@update');
Route::put('/usuario/updateByUser', 'UsuarioController@updateByUser');
Route::post('/usuario/upload', 'UsuarioController@upload')->middleware(apliAuthMiddleware::class);
Route::get('/usuario/avatar/{filename}', 'UsuarioController@getImage');
Route::get('/usuario/detalle/{id}', 'UsuarioController@detalle');
Route::delete('/usuario/delete/{id}', 'UsuarioController@destroy');

// rutas de controlador de genero
Route::resource('/genero', 'GeneroMusicaController');
Route::put('/genero/update', 'GeneroMusicaController@update');
Route::delete('/genero/delete/{id}', 'GeneroMusicaController@destroy');

// Rutas de controlador de Grupos
Route::resource('/grupo', 'GrupoMusicaController');
Route::post('/grupo/store', 'GrupoMusicaController@store');
Route::post('/grupo/upload', 'GrupoMusicaController@upload')->middleware(apliAuthMiddleware::class);
Route::get('/grupo/cartel/{filename}', 'GrupoMusicaController@getImage');
Route::get('/grupo/genero/{id}', 'GrupoMusicaController@getGrupoByGenero');
Route::get('/grupo/usuario/{id}', 'GrupoMusicaController@getGrupoByUsuario');
Route::get('/detalle/grupo/{id}', 'GrupoMusicaController@getGrupo');
Route::put('/grupo/update', 'GrupoMusicaController@update');
Route::delete('/grupo/delete/{id}', 'GrupoMusicaController@destroy');

// Rutas de controlador de Menu
Route::get('/menu', 'MenuController@index');
Route::post('/menu/user/{id}', 'MenuController@showByUser');
Route::post('/menu/store', 'MenuController@store');
Route::put('/menu/update', 'MenuController@update');
Route::delete('/menu/delete/{id}', 'MenuController@destroy');

// Rutas Permisos menu
Route::post('/permisos', 'PermisosMenuController@index');
Route::post('/permisos/user/{id}', 'PermisosMenuController@showByUser');
Route::post('/permisos/store', 'PermisosMenuController@store');
Route::put('/permisos/update', 'PermisosMenuController@update');
Route::delete('/permisos/delete/{id}', 'PermisosMenuController@destroy');

// Rutas Sub menu
Route::post('/submenu', 'SubMenuController@index');
Route::post('/submenu/store', 'SubMenuController@store');
Route::put('/submenu/update', 'SubMenuController@update');
Route::delete('/submenu/delete/{id}', 'SubMenuController@destroy');

// Rutas permisos sub menu
Route::post('/permisos/submenu', 'permisosSubMenuController@index');
Route::post('/permisos/submenu/user', 'permisosSubMenuController@showByUser');
Route::post('/permisosSubMenu/store', 'permisosSubMenuController@store');
Route::put('/permisosSubMenu/update', 'permisosSubMenuController@update');
Route::delete('/permisosSubMenu/delete/{id}', 'permisosSubMenuController@destroy');

// Rutas Pedidos
Route::post('/pedido', 'pedidoController@index');
Route::post('/pedido/store', 'pedidoController@store');
Route::put('/pedido/update', 'pedidoController@update');
Route::delete('/pedido/delete/{id}', 'pedidoController@destroy');

// Rutas Dtalle Pedidos
Route::post('/detalle/pedido', 'detallePedidoController@index');
Route::post('/detalle/pedido/store', 'detallePedidoController@store');
Route::put('/detalle/pedido/update', 'detallePedidoController@update');
Route::delete('/detalle/pedido/delete/{id}', 'detallePedidoController@destroy');

// Rutas Dtalle Pedidos
Route::post('/status/pedido', 'statusPedidoController@index');
Route::post('/status/pedido/store', 'statusPedidoController@store');
Route::put('/status/pedido/update', 'statusPedidoController@update');
Route::delete('/status/pedido/delete/{id}', 'statusPedidoController@destroy');

// Rutas Promosion Grupo
Route::post('/promosion/grupo', 'promosionGrupoController@index');
Route::post('/promosion/grupo/store', 'promosionGrupoController@store');
Route::put('/promosion/grupo/update', 'promosionGrupoController@update');
Route::delete('/promosion/grupo/delete/{id}', 'promosionGrupoController@destroy');

// Rutas Tarjeta
Route::post('/tarjeta', 'tarjetaController@index');
Route::post('/tarjeta/store', 'tarjetaController@store');
Route::put('/tarjeta/update', 'tarjetaController@update');
Route::delete('/tarjeta/delete/{id}', 'tarjetaController@destroy');

// Rutas Tipo Usuario
Route::post('/tipo/usuario', 'tipoUsuarioController@index');
Route::post('/tipo/usuario/store', 'tipoUsuarioController@store');
Route::put('/tipo/usuario/update', 'tipoUsuarioController@update');
Route::delete('/tipo/usuario/delete/{id}', 'tipoUsuarioController@destroy');

// Rutas fecha Pago
Route::post('/fecha/pago', 'fechaPagoController@index');
Route::post('/fecha/pago/store', 'fechaPagoController@store');
Route::put('/fecha/pago/update', 'fechaPagoController@update');
Route::delete('/fecha/pago/delete/{id}', 'fechaPagoController@destroy');

// Rutas direccion
Route::post('/dirreccion', 'direccionController@index');
Route::post('/dirreccion/store', 'direccionController@store');
Route::put('/dirreccion/update', 'direccionController@update');
Route::delete('/dirreccion/delete/{id}', 'direccionController@destroy');