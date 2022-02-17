<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\MailValidationUser;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    
    /**
     * Registro de usuario
     */
    public function register(Request $request)
    {
        //dd($request);
        $datos = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user_validation_token = Str::random(50);

        try {   
            
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $datos['first_name'],
                'last_name' => $datos['last_name'],
                'email' => $datos['email'],
                'password' => bcrypt($datos['password']),
                'validation_token' => $user_validation_token
            ]);

            $user->cliente()->create([
                'nombrefantasia' => $datos['first_name']." ".$datos['last_name'],
                'mail' => $datos['email'],
                'idestado'=>1
            ]);
    
            Mail::to($user->email)->queue(new MailValidationUser($user->toArray())); 

            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'Successfully created user!',
            ], 200);
        } 
        
        catch (\Throwable $e) {
            DB::rollBack();
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function informationPersonal(Request $request)
    {

        $user = Auth::user();
        $complementos = User::find($user->id);
        
        if(!$complementos){
            throw new \Error('Hubo un error inesperado');
        }
        
        // Chequea los campos de entrada
        $campos = $request->validate([
            'razon_social' => ['required', 'string'],
            'nombre_apellido' => ['required', 'string'],
            'documento_cuit' => ['required', 'numeric'],
        ]);

        try {          

            $complementos->update([
                'razonsocial'=> $campos['razon_social'],
                'nombrefantasia'=> $campos['nombre_apellido'],
                'cuit'=> $campos['documento_cuit']
            ]);  

            return response()->json([
                'status' => 200,
                'message' => 'La actualización del registro fue exitosa',
            ], 200);
        } 
        
        catch (\Throwable $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Guarda la validación del mail de contacto de la empresa en la BD
    public function validationUser(Request $request)
    {
        $validation_token = $request->input('token');

        $user = User::where([
            ['validation_token', '=', $validation_token]
        ])->first();

        try {

            if (!$user) {
                throw new \Error('No existe una empresa con el token dado');
            } elseif ($user['is_validated'] == 1) {
                throw new \Error('El mail de la empresa ya estaba validado');
            }

            //UPDATE en la DB
            User::where('id', $user['id'])->update([
                'is_validated' => 1,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Validación realizada con éxito'
            ]);
        } catch (\Throwable $e) {
            $code = $e->getCode() ? $e->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    //Reenvía un mail al usuario para validar la cuenta
    public function sendMailRevalidationUser()
    {
        $user = Auth::user();

        //Mail::to($user['email'])->queue(new MailValidacionUsuario($user->toArray()));

        Mail::to($user->email)->queue(new MailValidationUser($user));

        return response()->json(null, 201);
    }


    /*public function register(Request $request)
    {
        $account_type = $request->input('account_type');

        $validation_rules = $account_type == "PERSONAL" ? $this->personal_validation_rules : $this->company_validation_rules;

        $validator = Validator::make($request->all(), $validation_rules, [
            'email.unique' => "Esta dirección de correo electrónico se encuentra en uso",
            'cuit.unique' => "El CUIT se encuentra registrado",
        ]);

        $data = $validator->validate();

        DB::beginTransaction();
        try {
            $user_validation_token = Str::random(50);

            if ($account_type == "PERSONAL") {
                $account = self::registerPerson($data);
            } else {
                $account = self::registerCompany($data);
            }

            if (isset($data['mail_promotions'])) {
                $user = $account->user()->create(['email' => $data['email'], 'password' => Hash::make($data['password']), 'validation_token' => $user_validation_token, 'mail_promotions' => $data['mail_promotions']]);
            }else{
                $user = $account->user()->create(['email' => $data['email'], 'password' => Hash::make($data['password']), 'validation_token' => $user_validation_token]);
            }

            DB::commit();

            //Se envía mail de validación de email cuando se registra una cuenta USUARIO
            if($account_type == "PERSONAL"){
                Mail::to($user['email'])->queue(new MailValidacionUsuario($user->toArray()));
            }else{
                Mail::to($user['email'])->queue(new MailBienvenidaEmpresa($user->toArray()));
            }

            //Disparo evento luego de la creación de usuario, que envía notificación al mismo para que complete la info necesaria
            //event(new CompleteInfoEvent($user));

            return response()->json(null, 201);
        } 
        catch (\Throwable $error) {
            DB::rollBack();
            return response()->json(['errors' => $error->getMessage()], 400);
        }
    }*/

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
