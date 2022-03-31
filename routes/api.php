<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\TelefonoController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/



Route::group([
    'prefix' => 'v1',
    'middleware' => 'JSONMiddleware'
], function () {


    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('signup', [AuthController::class, 'register']);
    
        Route::group([
          'middleware' => 'auth:api'
        ], function() {
            Route::get('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
        });
    
    });


    Route::post('validate', [TelefonoController::class, 'identify'])->middleware('client');
    Route::post('validate-file', [TelefonoController::class, 'processFile'])->middleware('client');    
    

    Route::group([
    'middleware' => 'auth:api'
    ], function() {//processFile
        Route::post('search-telefono', [TelefonoController::class, 'identify']);
        Route::post('file-telefono', [TelefonoController::class, 'processFile']);

        /*
        Route::post('estado/add', [EstadoController::class, 'create']);
        Route::put('estado/update/{id}', [EstadoController::class, 'update']);
        Route::post('estado/all', [EstadoController::class, 'estados']);
        Route::post('estado/get/{id}', [EstadoController::class, 'estado']);



        Route::post('paypal/pay',[PaymentController::class, 'payWithPayPal']);
        Route::get('paypal/status',[PaymentController::class, 'payPalStatus']);

        Route::post('planes/add', [PlanController::class, 'create']);
        Route::put('planes/update/{id}', [PlanController::class, 'update']);
        Route::post('planes/all', [PlanController::class, 'planes']);
        Route::post('planes/get/{id}', [PlanController::class, 'plan']);


        Route::put('cliente/registro-datos-personales', [ClienteController::class, 'create']);
        Route::get('cliente/information-personal', [ClienteController::class, 'informationPersonal']);
        Route::post('cliente/all', [ClienteController::class, 'clientes']);
        Route::post('cliente/get/{id}', [ClienteController::class, 'cliente']);
        
         
        Route::put('usuario/information-personal', [AuthController::class, 'informationPersonal']);
        Route::put('usuario/validacion',[AuthController::class, 'validationUser']);

        /**
         * Resource Solicitudes
         
        Route::get('solicitudes/all-search', [SolicitudController::class, 'solicitudesUsuarios']);
        */
    });

});


