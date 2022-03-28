<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $cliente = Client::where('user_id', $user->id)->first();
        $solicitudes = Solicitud::where('iduser', $user->id)
        ->orderBy('id','DESC')
        ->limit(10)
        ->get();
        return view('dashboard', compact('cliente','solicitudes'));
    }
}
