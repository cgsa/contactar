<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    

    public function solicitudesUsuarios()
    {
        $user = Auth::user();
        return Solicitud::where('iduser', $user->id)->with('estado')->get();
    }
}
