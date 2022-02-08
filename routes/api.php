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
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FacturaController;



Route::get('items', [ItemController::class, 'obtenerTodosItems']);

Route::post('login', [UserController::class, 'login']);
Route::post('signup', [UserController::class, 'signUp']);


Route::group([
    'prefix' => ''
], function () { // AQUI ESTARAN TODAS LAS RUTAS QUE NO NECESTAN AUTENTICACION

    //LISTO TODOS LOS PRODUCTOS DISPONIBLES PARA SU VENTA
    Route::get('items', [ItemController::class, 'obtenerTodosItems']);

    Route::post('login', [UserController::class, 'login']);
    Route::post('signup', [UserController::class, 'signUp']);
       
    //acceso a rutas con autenticación
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'auth'
  ], function() { 
       //Llamada al metodo para crear salir de la sesion

      Route::get('logout', [UserController::class, 'logout']); 

      //Llamada al metodo para crear la factura
      Route::post('crear-factura', [FacturaController::class, 'crearFactura']);

      //Llamada al método para editar la factura
      Route::post('editar-factura/{id}', [FacturaController::class, 'editarFactura']);     

      //Llamda al método para mostrar las facturas Generadas .      
      
      Route::get('facturas', [FacturaController::class, 'mostrarFacturasGeneradas']);   

      

      //Llamada al método para mostrar la factura de acuerdo al id
      Route::get('factura/{id}', [FacturaController::class, 'mostrarFacturaId']);        
         
    
});
   
});       

