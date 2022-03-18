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
        return "SELECT normaliza_tel($this->telefono, $this->codPai ) as Telefono,
        normaliza_operador( $this->telefono, $this->codPai ) as Operador,
        normaliza_localidad( $this->telefono, $this->codPai ) as Localidad,
        normaliza_iscel( $this->telefono, $this->codPai ) as Es_Movil";
    }


}