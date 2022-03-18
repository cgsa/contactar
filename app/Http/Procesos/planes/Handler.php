<?php
namespace App\Http\Procesos\planes;

use App\Models\ClientePlan;
use App\Models\PagoPlan;
use Carbon\Carbon;

class Handler
{

    private $plan;

    function __construct()
    {
        $this->plan = new ClientePlan();
    }


    public static function init()
    {
        return new static;
    }


    public function active(PagoPlan $pago)
    {
        $a = $this->isActive($pago->iduser);

        if(!is_countable($a)){            
            return $this->create($pago);
        }

        return $this->update($a, $pago);

    }


    private function update($plan, $pago)
    {
        $newPlan = $this->plan->find($plan->id);
        return $newPlan->update([
            'fecha_fin'     => $this->newDate($plan->fecha_fin)->addDay($pago->plan->cant_dias),
        ]);
    }


    private function create($pago)
    {
        return $this->plan->create([
            'fecha_inicio'  => Carbon::now(),
            'fecha_fin'     => Carbon::now()->addDay($pago->plan->cant_dias + 1),
            'idpago'        => $pago->id,
            'iduser'        => $this->plan->iduser
        ]);
    }


    private function newDate($date): Carbon
    {
        return new Carbon(new \DateTime($date));
    }

    private function isActive($id)
    {
        return $this->plan->where([
            ['fecha_fin', '<', Carbon::now()],
            ['iduser', '=', $id],
        ])->get();
    }
}