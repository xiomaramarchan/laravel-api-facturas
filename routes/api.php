<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\ItemController;
Route::get('items', [ItemController::class, 'obtenerTodosItems']);
Route::post('login', 'UserController@login');
Route::post('signup', 'UserController@signUp');

Route::middleware('auth:api')->group( function () {
    Route::get('logout', 'UserController@logout');
    //Llamada al metodo para crear la factura
    Route::post('crear-factura', 'FacturaController@crearFactura');
    //Llamada al método para editar la factura
    Route::put('editar-factura/{id}', 'FacturaController@editarFactura');
    //Llamda al método para mostrar las facturas Generadas
    Route::get('facturas', 'FacturaController@mostrarFacturasGeneradas');
    //Llamada al método para mostrar la factura de acuerdo al id
    Route::get('factura/{id}', 'FacturaController@mostrarFacturaId');
    
});
  
