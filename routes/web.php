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
Route::get('/session/status','Auth\SessionController@status');

Route::post('/usuario','UsuarioController@store')->middleware('auth');

Route::get('/usuario','UsuarioController@create')->middleware('auth');

Route::get('/usuario/{id}/relacionables','UsuarioController@show_dif_id')->middleware('auth');

Route::delete('/usuario/{id}','UsuarioController@destroy')->middleware('auth');

// Route::get('/usuario/buscar','UsuarioController@show')->middleware('auth');

Route::put('/usuario','UsuarioController@update')->middleware('auth');

Route::post('/usuario/relacion','RelacionController@store')->middleware('auth');

Route::delete('/usuario/relacion/{id}','RelacionController@destroy')->middleware('auth');

Route::get('/usuario/relacion/existe','RelacionController@existe')->middleware('auth');

Route::get('/usuario/{id}/relaciones','UsuarioController@showRelacionesExitentes')->middleware('auth');

Route::get('/usuario/{id}/cuotas', 'UsuarioController@obtenerCuotasUsuario')->middleware('auth');

Route::get('/usuario/{id}/history', 'UsuarioController@getHistory')->middleware('auth');

Route::post('/cuota','CuotaController@store')->middleware('auth');

Route::put('/cuota','CuotaController@update')->middleware('auth');

Route::post('/cuotas', 'CuotaController@generarCuotasFaltantes')->middleware('auth');

Route::get('/configuraciones','ConfiguracionController@show')->middleware('auth');

Route::put('/configuraciones','ConfiguracionController@update')->middleware('auth');

Route::put('/configuraciones/automatizacion','ConfiguracionController@modificarAutomatizacion')->middleware('auth');

Route::post('/configuraciones/cambiarEmail','ConfiguracionController@modificarMail')->middleware('auth');

Route::post('/configuraciones/traerEmail','ConfiguracionController@traerMail')->middleware('auth');

Route::post('/generarCuota','CuotaController@generarCuota')->middleware('auth');

Route::put('/pagarCuota','CuotaController@pagar')->middleware('auth');

Route::put('/configuraciones/automatizacion','ConfiguracionController@modificarAutomatizacion')->middleware('auth');

Route::post('/torneo', 'TorneoController@store')->middleware('auth');
//TODO cambiar las rutas, debería ser /torneo/categorias pq sino no se entiende un choto cuando lo llamas
Route::post('/categorias', 'CategoriaController@storeCategorias')->middleware('auth');

Route::post('/jugadores', 'TorneoController@storeJugadores')->middleware('auth');

Route::get('/torneos','TorneoController@create')->middleware('auth');

Route::get('/torneos/{id}/jugadores','TorneoController@getJugadores')->middleware('auth');

Route::get('/torneos/{id}/categorias','TorneoController@getCategorias')->middleware('auth');

Route::get('/torneos/nombreOcupado/{nombre}','TorneoController@getNameExists')->middleware('auth');

Route::post('/torneo/fecha/guardar','FechaController@guardarFecha')->middleware('auth');

Route::post('/torneo/{torneo_id}/usuario/{usuario_id}','TorneoController@editPuntos')->middleware('auth');

Route::get('/base/descargar','ConfiguracionController@downloadBackup')->middleware('auth');

Route::post('/base/cargar','ConfiguracionController@uploadBackup')->middleware('auth');

Route::post('/torneo/puntos','TorneoController@updatePuntos')->middleware('auth');

Route::get('/torneo/{id}/fechas','TorneoController@getFechas')->middleware('auth');

Route::get('/torneo/{id}/getInfoGraficasCategorias','TorneoController@getInfoGraficasCategorias')->middleware('auth');

Route::get('/torneo/fecha/{id}','FechaController@getFecha')->middleware('auth');

Route::get('/export-fecha/{id}', 'ExcelController@getFechaRankingExcel')->middleware('auth');

Route::post('/ingreso/setMonto', 'IngresosExternosController@store')->middleware('auth');

Route::get('/fechas','FechaController@create')->middleware('auth');

Route::get('/ingresos/{fecha_inicio?}/{fecha_fin?}/{tipo?}/{torneo_id?}/{fecha_id?}','IngresosExternosController@create')->middleware('auth');

Auth::routes();

Route::put('/contrasena', 'ChangePasswordController@store');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');


Route::post('/send-email','MailController@sendEmail');

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)')->middleware('auth');
