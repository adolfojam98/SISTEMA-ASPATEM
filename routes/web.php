<?php



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

Route::post('/usuario','UsuarioController@store');

Route::get('/usuario','UsuarioController@create');

Route::get('/usuario/{id}/relacionables','UsuarioController@show_dif_id');

Route::delete('/usuario/{id}','UsuarioController@destroy');

// Route::get('/usuario/buscar','UsuarioController@show');

Route::put('/usuario','UsuarioController@update');

Route::post('/usuario/relacion','RelacionController@store');

Route::delete('/usuario/relacion/{id}','RelacionController@destroy');

Route::get('/usuario/relacion/existe','RelacionController@existe');

Route::get('/usuario/{id}/relaciones','UsuarioController@showRelacionesExitentes');

Route::get('/usuario/{id}/cuotas', 'UsuarioController@obtenerCuotasUsuario');

Route::post('/cuota','CuotaController@store');

Route::put('/cuota','CuotaController@update');

Route::post('/cuotas', 'CuotaController@generarCuotasFaltantes');

Route::get('/configuraciones','ConfiguracionController@show');

Route::put('/configuraciones','ConfiguracionController@update');

Route::post('/generarCuota','CuotaController@generarCuota');

Route::put('/pagarCuota','CuotaController@pagar');



Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');