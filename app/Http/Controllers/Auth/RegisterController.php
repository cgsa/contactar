<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailValidationUser;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "/verification-message";//RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms_conditions' => ['required', 'bool'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user_validation_token = Str::random(50);

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'terms_conditions' => $data['terms_conditions'],
            'password' => Hash::make($data['password']),
            'validation_token' => $user_validation_token
        ]);
    }

    

    protected function registered(Request $request, $user)
    {
        $user->cliente()->create([
            'nombrefantasia' => $user->first_name." ".$user->last_name,
            'mail' => $user->email,
            'idestado'=>1
        ]);

        Mail::to($user->email)->queue(new MailValidationUser($user->toArray()));

        
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return redirect($this->redirectPath())->with('register_complete', true);

    }


    public function message()
    {
        if(!session('register_complete'))
        {
            return redirect('/home');
        }

        return view('auth.verify');
        
    }


    //Guarda la validación del mail de contacto de la empresa en la BD
    public function validateUser(Request $request)
    {
        $validation_token = $request->input('token');

        $user = User::where([
            ['validation_token', '=', $validation_token]
        ])->first();

        if (!$user) {
            return response('No existe un usuario con el token dado', 422);
        } elseif ($user['is_validated'] == 1) {
            return response('El mail de su usuario ya estaba validado', 422);
        }

        //UPDATE en la DB
        User::where('id', $user['id'])->update([
            'is_validated' => 1,
        ]);

        return view('auth.verify_token');
    }

    //Reenvía un mail al usuario para validar la cuenta
    public function sendMailRevalidationUser()
    {
        $user = Auth::user();

        //Mail::to($user['email'])->queue(new MailValidacionUsuario($user->toArray()));

        Mail::to($user->email)->queue(new MailValidationUser($user));

        return response()->json(null, 201);
    }

}
