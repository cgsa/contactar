<?php

namespace App\Imports;

use App\Http\Procesos\QueryNormalizadorTelefono;
use App\Models\Estado;
use App\Models\Solicitud;
use App\Rules\Telefono;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TelefonosImport implements ToModel, WithValidation, WithHeadingRow, SkipsOnFailure, WithBatchInserts, WithChunkReading
{

    use Importable, SkipsFailures, QueryNormalizadorTelefono;

    private $user;

    private $status;

    private $size = 1000;

    private $telefonos = [];


    public function __construct()
    {
        $this->user = Auth::user();
        $this->status = new Estado;
    }

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return $this->addRegister($row);
    }

    public function rules(): array
    {
        return [
            'telefono' => function($attribute, $value, $onFailure) {  
                $tel = Telefono::rule();  
                  if (!$tel->passes($attribute, $value)) {
                       $onFailure($tel->message());
                  }
            }
        ];
    }


    public function setbatchSize($size)
    {
        $this->size = $size;
    }
    
    public function batchSize(): int
    {
        return $this->size;
    }
    
    public function chunkSize(): int
    {
        return 100;
    }


    private function addRegister( $campos )
    {
        $telefono = $this->normalizador($campos['telefono']);

        if(!is_object($telefono)){
            return false;
        }

        $estado = $this->getStatus($this->condition($telefono->Telefono));
        
        $this->telefonos[] = Solicitud::create([
            'numero_original'=>$campos['telefono'],
            'numero_encontrado'=>$telefono->Telefono,
            'operador'=>$telefono->Operador,
            'localidad'=>$telefono->Localidad,
            'es_movil'=>$telefono->Es_Movil,
            'iduser'=> $this->user->id,
            'idestado'=>$estado->id
        ]);
    }


    private function getStatus($condition)
    {
        return $this->status->findByDescripcion($condition);
    }


    public function getProccessed(): array
    {
        return $this->telefonos;
    }


    public function proccessed(): int
    {
        return count($this->telefonos);
    }


    private function condition($telefono): string
    {
        $estado = 'SNE';

        if(is_countable($telefono)){
            $estado = 'SE';
        }

        return $estado;
        
    }

    private function normalizador($telefono)
    {
        $result = DB::select($this->query($telefono));
        return is_countable($result)? $result[0] : null;
    }
}