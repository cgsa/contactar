<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enacom extends Model
{
    use HasFactory;

    protected $table = "enacom";

    protected $fillable = [
        'servicio',
        'modalidad',
        'localidad',
        'indicativo',
        'bloque',
        'resolucion',
        'fecha',
        'is_cel_pho'
    ];

}
