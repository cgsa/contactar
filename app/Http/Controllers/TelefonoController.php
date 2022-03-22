<?php

namespace App\Http\Controllers;

use App\Http\Procesos\NormalizarTelefono;
use App\Imports\TelefonosImport;
use App\Models\Estado;
use App\Models\OAuthClient;
use App\Models\Solicitud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TelefonoController extends Controller
{
    
    public function identify( Request $request )
    {
        
        // Chequea los campos de entrada
        $campos = $request->validate([
         'telefono' => ['required', 'numeric','digits_between:8,14'],
         'cod-pai' => ['required', 'string',Rule::in([
             'VE','AR','BO','BR','CL','CO','EC','MX','PE','PY','UY'
             ])],
         'ip-user' =>['sometimes','ip']
        ]);
        
        try { 
            
            
            $user = $this->getUser($request);

            $normalizador = new NormalizarTelefono($campos['cod-pai'], $campos['telefono']);
            $telefono = DB::select($normalizador->sql());
            $estado = 'SNE';

            if(is_countable($telefono[0]->Telefono)){
                $estado = 'SE';
            }


            if(
                trim($telefono[0]->Localidad) === "No validado por enacom" 
            ){
                throw new \Error('No se encontro información referente');
            }
            
            
            $status = Estado::state($estado, 'S');
            
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


    private function getUser(Request $request)
    {
        if($request->route()->uri == "api/v1/validate")
        {
            return OAuthClient::findByRequest($request);
        }

        return Auth::user();
    }


    public function processFile( Request $request )
    {
        // Chequea los campos de entrada
        $campos = $request->validate([
            'telefonos' => ['required', 'file','mimes:csv,xls'],
            'cod-pai' => ['required', 'string',Rule::in([
                'VE','AR','BO','BR','CL','CO','EC','MX','PE','PY','UY'
            ])],
        ]);

        try { 

            DB::beginTransaction();   
            $import = new TelefonosImport();
            //$import->setbatchSize();
            $import->import($request->file('telefonos'),'local');
            
            foreach ($import->failures() as $failure) {
                $fail = [
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
                'telefono' => $import->getProccessed(),
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
