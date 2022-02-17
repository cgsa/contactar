<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{

    public function create(Request $request)
    {
        // Chequea los campos de entrada
        $campos = $request->validate([
           // 'valid_since' => ['sometimes', 'date', 'after_or_equal:today'],
            'razonsocial' => ['required', 'string'],
            'nombrefantasia' => ['required', 'string'],
            'mail' => ['required', 'email'],
            'cuit' => ['required', 'string'],
        ]);

        try {         

            $user = User::find(Auth::user()->id);

            $user->cliente()->update([
                'razonsocial' => 'Otra',
                'nombrefantasia' => $campos['nombrefantasia'],
                'mail' => $campos['mail'],
                'cuit' => $campos['cuit'],
                'idestado'=>1
            ]);

            return response()->json([
                'status' => 201,
                'message' => 'El registro se realizó de manera exitosa',
            ], 200);
        }
        
        catch (\Throwable $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function informationPersonal()
    {

        $user = User::find(Auth::user()->id);

        try {          
            
            if(!$user){
                throw new \Error('No se encontro ningún registro.');
            }            

            return response()->json([
                'status' => 200,
                'usuario' => $user->toArray(),
                'cliente'=> $user->cliente
            ], 200);
        } 
        
        catch (\Throwable $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function clientes()
    {

        try {
            
            $estado = Cliente::all();

            return response()->json([
                'status' => 200,
                'estados' => $estado,
            ], 200);
        } 
        
        catch (\Throwable $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function cliente($id)
    {

        try {
            
            $estado = Cliente::findOrFail($id);

            return response()->json([
                'status' => 200,
                'estados' => $estado,
            ], 200);
        } 
        
        catch (\Throwable $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
