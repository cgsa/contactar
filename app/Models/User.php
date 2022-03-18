<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'is_validated',
        'validation_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'iduser');
    }


    public function pagoPlan()
    {
        return $this->hasOne(PagoPlan::class, 'iduser');
    }


    public function planUser()
    {
        $pago = PagoPlan::where('iduser', Auth::user()->id)->first();
        $plan = is_null($pago)? 'S/P' : $pago->plan->titulo;
        return _('Plan activo: '). $plan;
    }
}
