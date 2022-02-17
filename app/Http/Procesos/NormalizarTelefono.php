<?php

namespace App\Http\Procesos;

class NormalizarTelefono
{

    private $telefono;


    public function __construct( $telefono )
    {
        $this->telefono = $telefono;
    }


    public function sql()
    {
        return "SELECT normaliza_tel( $this->telefono ) as Telefono,
        normaliza_operador( $this->telefono ) as Operador,
        normaliza_localidad( $this->telefono ) as Localidad,
        normaliza_iscel( $this->telefono ) as Es_Movil";
    }


}