<?php

namespace App\Http\Procesos;

class NormalizarTelefono
{

    private $codPai;

    private $telefono;


    public function __construct( string $codPai, string $telefono )
    {
        $this->codPai = $codPai;
        $this->telefono = $telefono;
    }


    public function sql()
    {
        return "SELECT normaliza_tel( \"$this->codPai\" , \"$this->telefono\" )as telefono,
        normaliza_operador( \"$this->codPai\" , \"$this->telefono\" )as operador,
        normaliza_localidad( \"$this->codPai\" , \"$this->telefono\" )as localidad,
        normaliza_iscel( \"$this->codPai\" , \"$this->telefono\" )as es_movil";
    }


}