<?php

namespace App\Http\Procesos;

class NormalizarTelefono
{

    private $telefono;

    private $codPai;


    public function __construct( $telefono, $codPai )
    {
        $this->telefono = $telefono;
        $this->codPai = $codPai;
    }


    public function sql()
    {
        return "SELECT normaliza_tel($this->codPai, $this->telefono ) as Telefono,
        normaliza_operador( $this->codPai, $this->telefono ) as Operador,
        normaliza_localidad( $this->codPai, $this->telefono ) as Localidad,
        normaliza_iscel( $this->codPai, $this->telefono ) as Es_Movil";
    }


}