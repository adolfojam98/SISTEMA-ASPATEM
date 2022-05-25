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


Route::group(['prefix' => '/session', 'as' => 'session.'], function () {
    Route::get('/status', 'Auth\SessionController@status')->name('status');
});

Route::group(['prefix' => '/usuario', 'as' => 'usuario.'/*, 'middleware' => ['auth']*/], function () {
    Route::post('/', 'UsuarioController@store')->name('store');
    Route::get('/', 'UsuarioController@create')->name('create');
    Route::put('/', 'UsuarioController@update')->name('update');
    Route::get('/{id}/cuotas', 'UsuarioController@getCuotas')->name('getCuotas');

    Route::get('/{id}/relacionables', 'UsuarioController@show_dif_id')->name('show_dif_id');
    Route::delete('/{id}', 'UsuarioController@destroy')->name('destroy');
    Route::get('/{id}/history', 'UsuarioController@getHistory')->name('getHistory');
    Route::get('/{id}/relaciones', 'UsuarioController@showRelacionesExitentes')->name('showRelacionesExitentes');

    Route::group(['prefix' => '/relacion', 'as' => 'relacion.'], function () {
        Route::post('/', 'RelacionController@store')->name('store');
        Route::delete('/{id}', 'RelacionController@destroy')->name('destroy');
        Route::get('/existe', 'RelacionController@existe')->name('existe');
    });
});

Route::group(['prefix' => '/pago', 'as' => 'cuota.', /*'middleware' => ['auth']*/], function () {
    Route::post('/store/{cuota_id}', 'PagoController@store')->name('store');
});

    Route::group(['prefix' => '/cuota', 'as' => 'cuota.', /*'middleware' => ['auth']*/], function () {
    Route::get('/', 'CuotaController@index')->name('index');    
    Route::post('/', 'CuotaController@store')->name('store');
    Route::put('/', 'CuotaController@update')->name('update');
    Route::post('/generarCuotasMasivas', 'CuotaController@generarCuotasMasivas')->name('generarCuotasMasivas');
    Route::get('/{id}', 'CuotaController@getCuotaById')->name('getCuotaById');
  
});


//relacionado con cuotas -- SUELTOS
Route::post('/cuotas', 'CuotaController@generarCuotasFaltantes')->middleware('auth')->name('generarCuotasFaltantes');
Route::post('/generarCuota', 'CuotaController@generarCuota')->middleware('auth')->name('generarCuota');
Route::put('/pagarCuota', 'CuotaController@pagar')->middleware('auth')->name('pagar');
//relacionado con cuotas -- SUELTOS

Route::group(['prefix' => '/configuraciones', 'as' => 'configuraciones.', 'middleware' => ['auth']], function () {
    Route::get('/', 'ConfiguracionController@show')->name('show');
    Route::put('/', 'ConfiguracionController@update')->name('update');

    Route::put('/automatizacion', 'ConfiguracionController@modificarAutomatizacion')->name('modificarAutomatizacion');
    Route::post('/cambiarEmail', 'ConfiguracionController@modificarMail')->name('modificarMail');
    Route::post('/traerEmail', 'ConfiguracionController@traerMail')->name('traerMail');
});

Route::group(['prefix' => '/torneo', 'as' => 'torneo.', 'middleware' => ['auth']], function () {
    Route::post('/', 'TorneoController@store')->name('store');

    Route::post('/fecha/guardar', 'FechaController@guardarFecha')->name('guardarFecha');
    Route::post('/puntos', 'TorneoController@updatePuntos')->name('updatePuntos');
    Route::post('/{torneo_id}/usuario/{usuario_id}', 'TorneoController@editPuntos')->name('editPuntos');
    Route::get('/{id}/fechas', 'TorneoController@getFechas')->name('getFechas');
    Route::get('/{id}/getInfoGraficasCategorias', 'TorneoController@getInfoGraficasCategorias')->name('getInfoGraficasCategorias');
    Route::get('/fecha/{id}', 'FechaController@getFecha')->name('getFecha');
});

Route::group(['prefix' => '/torneos', 'as' => 'torneos.', 'middleware' => ['auth']], function () {
    Route::get('/', 'TorneoController@create')->name('create');

    Route::get('/{id}/jugadores', 'TorneoController@getJugadores')->name('getJugadores');
    Route::get('/{id}/categorias', 'TorneoController@getCategorias')->name('getCategorias');
    Route::get('/nombreOcupado/{nombre}', 'TorneoController@getNameExists')->name('getNameExists');
});

Route::group(['prefix' => '/base', 'as' => 'base.', 'middleware' => ['auth']], function () {
    Route::get('/descargar', 'ConfiguracionController@downloadBackup')->name('downloadBackup');
    Route::post('/cargar', 'ConfiguracionController@uploadBackup')->name('uploadBackup');
});

Route::group(['prefix' => '/ingresos', 'as' => 'ingresos.', 'middleware' => ['auth']], function () {
    Route::post('/setMonto', 'IngresosExternosController@store')->name('store');
    Route::get('/{fecha_inicio?}/{fecha_fin?}/{tipo?}/{torneo_id?}/{fecha_id?}', 'IngresosExternosController@create')->name('create');
});

//relacionado con torneo -- SUELTOS
Route::get('/fechas', 'FechaController@create')->middleware('auth');
Route::get('/export-fecha/{id}', 'ExcelController@getFechaRankingExcel')->middleware('auth');

Route::post('/categorias', 'CategoriaController@storeCategorias')->middleware('auth');
Route::post('/jugadores', 'TorneoController@storeJugadores')->middleware('auth');
//relacionado con torneo -- SUELTOS

//relacionado con otros (email y home(????)) -- SUELTOS
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::post('/send-email', 'MailController@sendEmail');
//relacionado con otros (email y home(????)) -- SUELTOS

//relacionado con auth -- SUELTOS
Route::put('/contrasena', 'ChangePasswordController@store');
Auth::routes();
//relacionado con auth -- SUELTOS

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)')->middleware('auth');
