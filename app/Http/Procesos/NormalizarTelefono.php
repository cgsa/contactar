<?php

namespace App\Http\Procesos;

class NormalizarTelefono
{

    private $codPai;

    private $telefono;


    public function __construct( $codPai, $telefono )
    {
        $this->codPai = $codPai;
        $this->telefono = $telefono;
    }


    public function sql()
    {
        return "SELECT normaliza_tel( $this->codPai , $this->telefono )as Telefono,
        normaliza_operador( $this->codPai , $this->telefono )as Operador,
        normaliza_localidad( $this->codPai , $this->telefono )as Localidad,
        normaliza_iscel( $this->codPai , $this->telefono )as Es_Movil";
    }


}