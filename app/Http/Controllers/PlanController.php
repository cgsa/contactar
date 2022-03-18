<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function planes()
    {        

        try {
            
            $planes = Plan::all();

            return response()->json([
                'status' => 200,
                'estados' => $planes,
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


    public function plan($id)
    {

        try {
            
            $plan = Plan::find($id);

            if(!$plan){
                throw new \Error('No se encontro ningún registro con el id: '.$id);
            }

            return response()->json([
                'status' => 200,
                'estados' => $plan,
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Chequea los campos de entrada
        $campos = $request->validate([
            // 'valid_since' => ['sometimes', 'date', 'after_or_equal:today'],
            'titulo' => ['required', 'string', 'max:60'],
            'descripcion' => ['required', 'string'],
            'moneda' => ['required', 'integer'],
            'valor' => ['required', 'numeric'],
            'cant_dias' => ['required', 'integer'],
            'solicitudes' => ['required', 'numeric'],
            'estado' => ['required', 'integer','exists:estados,id'],
         ]);
 
         try {
             DB::beginTransaction();           
 
             //$estado = Estado::findByDescripcion($campos['estado']);
             Plan::create([
                 'titulo' => $campos['titulo'],
                 'descripcion' => $campos['descripcion'],
                 'moneda' => $campos['moneda'],
                 'valor' => $campos['valor'],
                 'cant_dias' => $campos['cant_dias'],
                 'solicitudes'=>$campos['solicitudes'],
                 'idestado'=>$campos['estado']
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estado = Plan::find($id);

        if(!$estado){
            throw new \Error('No se encontro ningún registro con el id: '.$id);
        }
        
        // Chequea los campos de entrada
        $campos = $request->validate([
           // 'valid_since' => ['sometimes', 'date', 'after_or_equal:today'],
           'titulo' => ['sometimes', 'string', 'max:60'],
           'descripcion' => ['sometimes', 'string'],
           'moneda' => ['sometimes', 'integer'],
           'valor' => ['sometimes', 'numeric'],
           'cant_dias' => ['sometimes', 'integer'],
           'idestado' => ['sometimes', 'integer','exists:estados,id'],
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
