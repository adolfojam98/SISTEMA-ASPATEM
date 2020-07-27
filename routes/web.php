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

Route::post('/usuario/guardar','UsuarioController@store');

Route::get('usuario/mostrar','UsuarioController@create');

Route::get('/usuario/{id}/relaciones','UsuarioController@show_dif_id');

Route::delete('/usuario/borrar/{id}','UsuarioController@destroy');

// Route::get('/usuario/buscar','UsuarioController@show');

Route::put('/usuario/actualizar','UsuarioController@update');

Route::post('/usuario/relacion','RelacionadoController@store');

Route::get('/usuario/relacion/existe','RelacionadoController@existe');



Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');