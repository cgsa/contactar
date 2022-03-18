<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estado = $this->estado();

        $this->create([
            'descripcion'   =>'Paypal',
            'codigoext'     =>'paypal',
            'idestado'      =>$estado->id
        ]);

        $this->create([
            'descripcion'   =>'Mercado Pago',
            'codigoext'     =>'mp',
            'idestado'      =>$estado->id
        ]);
    }


    private function estado()
    {
        return Estado::where([
            ['codigo','=','A'],
            ['seccion','=','MP'],
        ])->first();
    }


    private function create(array $data): MetodoPago
    {
        return MetodoPago::create($data);
    }
}
