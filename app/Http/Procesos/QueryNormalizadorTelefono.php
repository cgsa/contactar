<?php 

namespace App\Http\Procesos;

trait QueryNormalizadorTelefono 
{

    public function query($telefono)
    {
        return "SELECT normaliza_tel( $telefono ) as Telefono,
            normaliza_operador( $telefono ) as Operador,
            normaliza_localidad( $telefono ) as Localidad,
            normaliza_iscel( $telefono ) as Es_Movil";
    }
}
?>