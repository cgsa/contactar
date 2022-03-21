<?php 

namespace App\Http\Procesos;

trait QueryNormalizadorTelefono 
{

    public function query($codPai, $telefono)
    {
        return "SELECT normaliza_tel( $codPai , $telefono )as Telefono,
        normaliza_operador( $codPai , $telefono )as Operador,
        normaliza_localidad( $codPai , $telefono )as Localidad,
        normaliza_iscel( $codPai , $telefono )as Es_Movil";
    }
}
?>