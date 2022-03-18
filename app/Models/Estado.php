<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion',
        'codigo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];


    public static function state($codigo, $seccion)
    {
        return self::where([
            ['codigo','=',$codigo],
            ['seccion','=',$seccion],
        ])->first();
    }


    public function findByCodigo($codigo)
    {
        return self::where('codigo',$codigo)->get();
    }


    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'idestado');
    }

}
