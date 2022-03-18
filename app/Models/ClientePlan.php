<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientePlan extends Model
{
    use HasFactory;


    protected $table = "cliente_plan";


    public function estado()
    {
        return $this->belongsTo('idestado');
    }


    public function pago()
    {
        return $this->belongsTo('idpago');
    }


    public function user()
    {
        return $this->belongsTo('iduser');
    }

}
