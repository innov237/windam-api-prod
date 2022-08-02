<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use App\Models\Devices;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.verify', ['except' => ['login', 'register']]);
    }



    public function login(Request $request)
    {
        //  return  bcrypt($request->password);

        $validator =  Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);


      

        if (!$token = auth()->attempt($validator->validated())) {
            return $this->reply(false, "Nom d'utilisateur ou mot de passe incorrect");
        }

        JWTAuth::setToken($token);
        $user = JWTAuth::toUser();
        $user->access_token = $token;
         if(!empty($request->input("isAdmin"))){
             
             if($user->role->id!=1){
                 return $this->reply(false,"Vous n'avez pas le droit de vous connecter");
             }
             
           
       }

        if (!empty($request->input('device_token'))) {
            $this->setdeviceToken($request, $user);
        }
        
         if ($user->active==0) {
            return $this->reply(false, "Votre compte a été désactivé", null);
        }

        // return $user;

        return $this->reply(true, "bien connecté", $user);
    }


    public function setdeviceToken(Request $request, $user)
    {
        try {
            $devices = Devices::where('user_id', $user->id)->first();
            if ($devices) {
                $devices->update(
                    [
                        'token' => $request->device_token,
                    ]
                );
            } else {
                Devices::create(
                    [
                        'user_id' => $user->id,
                        'token' => $request->device_token,
                        'platform' => "MOBILE",
                        'app_version' => "",
                    ]
                );
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->reply(true, "logout", null);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return auth()->refresh(true, true);
        //$token = auth()->tokenById(123);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function register(Request $request)
    {
        //  return $request->device_token;
        $devices = User::where('tel',  $request->tel)->orwhere('email', $request->email)->first();
        if ($devices) {
            return $this->reply(false, "compte existant", null);
        }

        DB::beginTransaction();

        try {
            $user = User::create(
                [
                    'nom' => $request->nom,
                    'tel' => $request->tel,
                    'sexe' => $request->sexe,
                    'ville' => $request->ville,
                    'prenom' => $request->prenom,
                    'email' =>  $request->email,
                    'role_id' => 2,
                    'active' => 1,
                    'password' => bcrypt($request->password)
                ]
            );
            $devices = Devices::create(
                [
                    'user_id' => $user->id,
                    'token' => $request->device_token,
                    'platform' => "MOBILE",
                    'app_version' => "",
                ]
            );


            DB::commit();
            if ($user && $devices) {
                return  $this->login($request);
            } else {
                return $this->reply(false, "Une erreur c'est prduite");
            }
        } catch (\Exception $e) {
            return $e;
            DB::rollback();
        }
    }

    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tel' => 'required|Integer',
            'password' => 'required|string',
        ]);
        //   return  bcrypt($credentials['password']);

        if ($validator->fails()) {
            return $this->reply(false, "Erreur sur les données", null);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return $this->reply(false, "Nom d'utilisateur ou mot de passe incorrect");
        }

        JWTAuth::setToken($token);
        $user = JWTAuth::toUser();
        $user->access_token = $token;

        return $this->reply(true, "bien connecté", $user);
    }


    //test

    public function update(Request $request)
    {

        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }

        $fields = $request->validate([
            'tel' => 'required|string',
            'ville' => 'required|string',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|string'

        ]);




        $getUserInfo = User::find(auth()->user()->id);

        if ($getUserInfo or $getUserInfo->password == bcrypt($fields['password'])) {
            $getUserInfo->nom = $fields['nom'];
            $getUserInfo->prenom = $fields['prenom'];
            $getUserInfo->tel = $fields['tel'];
            $getUserInfo->ville = $fields['ville'];
            $getUserInfo->email = $fields['email'];
            if ($getUserInfo->update()) {
                return $this->reply(true, "Modification éffectuée", $getUserInfo);
            } else {
                return $this->reply(false, "Erreur de modification", $getUserInfo);
            }
        } else {
            return $this->reply(false, "Erreur de modification", $getUserInfo);
        }
    }
}
