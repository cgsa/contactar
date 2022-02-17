<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadoController extends Controller
{
    
    public function create(Request $request)
    {
        // Chequea los campos de entrada
        $campos = $request->validate([
           // 'valid_since' => ['sometimes', 'date', 'after_or_equal:today'],
        'descripcion' => ['required', 'string'],
        'codigo' => ['required','string', 'max:3']
        ],[
            'descripcion'=> 'El campo Descripción es obligatorio',
            'codigo'=> 'El campo Codigo es obligatorio',
        ]);

        try {
            DB::beginTransaction();           


            Estado::create([
                'descripcion' => $campos['descripcion'],
                'seccion' => $campos['codigo'],
            ]);

            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'El registro se realizó de manera exitosa',
            ], 200);
        }
        
        catch (\Throwable $e) {
            DB::rollBack();
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function update(Request $request, $id)
    {

        $estado = Estado::find($id);

        if(!$estado){
            throw new \Error('No se encontro ningún registro con el id: '.$id);
        }
        
        // Chequea los campos de entrada
        $campos = $request->validate([
           // 'valid_since' => ['sometimes', 'date', 'after_or_equal:today'],
        'descripcion' => ['sometimes', 'string'],
        'codigo' => ['sometimes','string', 'max:3']
        ],[
            'descripcion'=> 'El campo Descripción es obligatorio',
            'seccion'=> 'El campo Codigo es obligatorio',
        ]);

        try {
            DB::beginTransaction();            

            $estado->update($campos);            

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'La actualización del registro fue exitosa',
            ], 200);
        } 
        
        catch (\Throwable $e) {
            DB::rollBack();
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function estados()
    {

        try {
            
            $estado = Estado::all();

            return response()->json([
                'status' => 200,
                'estados' => $estado,
            ], 200);
        } 
        
        catch (\Throwable $e) {
            DB::rollBack();
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function estado($id)
    {

        try {
            
            $estado = Estado::findOrFail($id);

            return response()->json([
                'status' => 200,
                'estados' => $estado,
            ], 200);
        } 
        
        catch (\Throwable $e) {
            DB::rollBack();
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
