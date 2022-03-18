<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoPlan extends Model
{
    use HasFactory;


    protected $fillable = [
        'idpay',
        'token',
        'state',
        'link',
        'iduser',
        'idplan'
    ];
    
    protected $table = "pagos_planes";


    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'idplan');
    }


    public function updateState( $state )
    {
        return self::update([
            'state'=> $state
        ]);
    }
}
