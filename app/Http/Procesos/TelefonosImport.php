<?php 
namespace App\Http\Procesos;

use App\Http\Procesos\QueryNormalizadorTelefono;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TelefonosImport
{

    use QueryNormalizadorTelefono;

    private $user;


    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function import($file)
    {

       Excel::filter('chunk')->load($file)->chunk(250, function($results)
        {
                foreach($results as $row)
                {
                    var_dump($row);die;
                }
        });
    }


    private function addRegister( $campos )
    {
        $telefono = $this->normalizador($campos['telefono']);
        Solicitud::create([
            'numero_original'=>$campos['telefono'],
            'numero_encontrado'=>$telefono[0]->Telefono,
            'operador'=>$telefono[0]->Operador,
            'localidad'=>$telefono[0]->Localidad,
            'es_movil'=>is_countable($telefono[0]->Telefono)? $telefono[0]->Es_Movil: '',
            'iduser'=> $this->user->id,
            'idestado'=>$this->user->id
        ]);
    }

    private function normalizador($telefono)
    {
        return DB::select($this->query($telefono));
    }
}

?>