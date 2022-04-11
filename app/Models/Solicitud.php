<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;


    protected $table = "solicitudes";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero_original',
        'numero_encontrado',
        'operador',
        'servicio',
        'localidad',
        'es_movil',
        'iduser',
        'idestado',
        'cod_pai'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];


    public function estado()
    {
        return $this->belongsTo(Estado::class, 'idestado');
    }
}
