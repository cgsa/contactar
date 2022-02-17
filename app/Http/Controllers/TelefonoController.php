<?php

namespace App\Http\Controllers;

use App\Http\Procesos\NormalizarTelefono;
use App\Imports\TelefonosImport;
use App\Models\Estado;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TelefonoController extends Controller
{
    
    public function identify( Request $request )
    {
        
        // Chequea los campos de entrada
        $campos = $request->validate([
         'telefono' => ['required', 'numeric','digits_between:6,13'],
         'ip-user' =>['sometimes','ip']
        ]);
        
        try { 
            
            $user = Auth::user();

            $normalizador = new NormalizarTelefono($campos['telefono']);
            $telefono = DB::select($normalizador->sql());
            $estado = 'SNE';

            if(is_countable($telefono[0]->Telefono)){
                $estado = 'SE';
            }
            
            $status = Estado::findByDescripcion($estado);
            //dd($status);
            DB::beginTransaction();   

            Solicitud::create([
                'numero_original'=>$campos['telefono'],
                'numero_encontrado'=>$telefono[0]->Telefono,
                'operador'=>$telefono[0]->Operador,
                'localidad'=>$telefono[0]->Localidad,
                'es_movil'=>is_countable($telefono[0]->Telefono)? $telefono[0]->Es_Movil: '',
                'iduser'=> $user->id,
                'idestado'=>$status->id
            ]);
            
            DB::commit();

            return response()->json([
                'status' => 201,
                'telefono' => $telefono,
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


    public function processFile( Request $request )
    {
        // Chequea los campos de entrada
        $campos = $request->validate([
            'telefonos' => ['required', 'file','mimes:csv,xls'],
        ]);

        try { 
            //dd($request->file('telefonos'));


            DB::beginTransaction();   
            $import = new TelefonosImport();
            $import->import($request->file('telefonos'),'local');
            
            foreach ($import->failures() as $failure) {
                $fail = [
                    'status' => 500,
                    'message' => [
                        'line'=>$failure->row(),                         
                        'error'=>$failure->errors(), 
                        'value'=>$failure->values()
                    ] ,
                ];

                
            }
            
            DB::commit();

            return response()->json([
                'status' => 201,
                'telefono' => $import->getResult(),
                'failures' => $fail

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
