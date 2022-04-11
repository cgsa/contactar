<?php

namespace App\Http\Controllers;

use App\Http\Procesos\NormalizarTelefono;
use App\Http\Procesos\ValidatePhone;
use App\Imports\TelefonosImport;
use App\Models\Estado;
use App\Models\OAuthClient;
use App\Models\Solicitud;
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
         'telefono' => ['required', 'numeric','digits_between:7,14'],
         'cod-pai' => ['required', 'string',Rule::in([
             'VE','AR','BO','BR','CL','CO','EC','MX','PE','PY','UY'
             ])],
         'ip-user' =>['sometimes','ip'],
         'from' => ['sometimes', 'string',Rule::in([
            'WEBSITE','API'
            ])],
        ]);
        
        try { 
            
            
            $idUser = $this->getIdUser($request);

            $normalizador = new NormalizarTelefono($campos['cod-pai'], $campos['telefono']);
            $telefono = DB::select($normalizador->sql());
            $estado = 'SNE';

            if(is_countable($telefono)){
                $estado = 'SE';
            }


            if(
                is_array($telefono) && trim($telefono[0]->localidad) == "No validado por argentina" 
            ){
                throw new \Error('No se encontro información referente');
            }            
            
            $status = Estado::state($estado, 'S'); 
            if(isset($campos['from']) && $campos['from'] == "WEBSITE"){
                OAuthClient::deleteTokenIdByRequest($request);
            }
            
            
            DB::beginTransaction();   

            $solicitud = Solicitud::create([
                'numero_original'=>$campos['telefono'],
                'numero_encontrado'=>$telefono[0]->telefono,
                'operador'=>$telefono[0]->operador,
                'localidad'=>$telefono[0]->localidad,
                'es_movil'=>is_countable($telefono)? $telefono[0]->es_movil: '',
                'iduser'=> $idUser,
                'idestado'=>$status->id,
                'cod_pai'=>$campos['cod-pai']
            ]);

            DB::commit();

            return response()->json([
                'telefono' => $solicitud
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


    private function getIdUser(Request $request)
    {
        if($request->route()->uri == "api/v1/validate" || $request->route()->uri == "api/v1/validate-file")
        {
            return OAuthClient::findByRequest($request)->user_id;
        }

        return Auth::user()->id;
    }


/*
    public function validateTest(Request $request)
    {
        
        $campos = $request->validate([
        'telefono' => ['required', 'numeric','digits_between:8,14'],
        'cod-pai' => ['required', 'string',Rule::in([
            'VE','AR','BO','BR','CL','CO','EC','MX','PE','PY','UY'
            ])],
        'ip-user' =>['sometimes','ip']
        ]);
        
        try {
            $validate = new ValidatePhone($campos);
            dd($validate->login());
            
        } catch (\Throwable $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }

    }*/


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
            $user = $this->getIdUser($request);
            $import = new TelefonosImport($campos['cod-pai'], $user);
            //$import->setbatchSize();
            $import->import($request->file('telefonos'),'local');
            $fail = "";
            
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
                'telefono' => $import->getProccessed(),
                'failures' => $fail
            ], 201);
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
