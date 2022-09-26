<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Resetpasswords;
use App\Http\Controllers\SendMailController;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return $this->reply(true, "Liste des utilisateurs", $user);
    }

    public function userDemande()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function changeuserstatus($id)
    {

        $user = User::where('id', $id)->first();


        $user->active = !$user->active;

        $userUpdate = $user->update();
        if ($userUpdate) {
            return $this->reply(true, "mise à jour effectuée avec succès", $user);
        } else {
            return $this->reply(false, "Erreur de mise à jour", null);
        }
        return $this->reply(false, "Erreur", null);
    }

    public function changeuserRole($id, $status)
    {

        $user = User::where('id', $id)->first();


        $user->role_id = $status;

        $userUpdate = $user->update();
        if ($userUpdate) {
            return $this->reply(true, "mise à jour effectuée avec succès", $user);
        } else {
            return $this->reply(false, "Erreur de mise à jour", null);
        }
        return $this->reply(false, "Erreur", null);
    }

    public function update(Request $request)
    {


        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }


        $fields = $request->validate([
            'tel' => 'required|string',
            'ville' => 'string',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|string'

        ]);

        $getUserInfo = User::find(auth()->user()->id);

        $getUserInfo->nom = $request->input("nom");
        $getUserInfo->prenom = $request->input("prenom");
        $getUserInfo->tel = $request->input("tel");
        $getUserInfo->ville = $request->input("ville");
        $getUserInfo->email = $request->input("email");

        if ($getUserInfo->update()) {
            return $this->reply(true, "Utilisateur modifié avec succès", $getUserInfo);
        } else {
            return $this->reply(false, "Erreur de modification", $getUserInfo);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function resetPassword(Request $request)
    {

      
        $user = User::find(auth()->id());

        if (!$user) {
            return $this->reply(false, 'Utilisateur introuvable', null);

        }
        if (!Hash::check($request->input('oldpassword'), $user->password)) {
            return $this->reply(false, 'Votre mot de passe est invalide', null);
        }
        $reset =  $user->update([
                'password' => bcrypt($request->input('password')),
            ]);
        if ($reset) {
            return $this->reply(true, 'Mot de passe modifié avec succès', $reset);
        }
        return $this->reply(false, 'Erreur de modification', null);
    }

    public function  sendVerificationOtp(Request $request){

       if(empty($request->input('credentials'))){
         return $this->reply(false,"Veuillez entrer le compte à réinitialiser",null);
       }

      $code = random_int(100000, 999999);
      $user = User::where('email',$request->input('credentials'))->first();

      if($user){

        $exit = resetPasswords::where('user_id',$user->id)->first();

        if($exit){
            $exit->delete();
        }

        $result =  Resetpasswords::updateOrCreate([
            "user_id"=>$user->id,
            "code"=>$code,
            "tel"=>$user->tel,
        ]);


        $request->request->add([
            "otp"=>$code,
            "subject"=>"Code de vérification windam",
            "email"=>$request->input('credentials'),
            "message" =>"Bonjour ".$user->nom ." Vueillez rensiegner ce code dans l'application pour reinitialiser votre compte"
        ]);

        (new SendMailController())->sendOtp($request);
        
        return $this->reply(true,"Code de verification envoyé",null);

      }else{
        return $this->reply(false,"Ce compte est introuvable",null);
      }
    }

    public function verifyOtpOrReset(Request $request){

        $exit =  Resetpasswords::where("code",$request->input('code'))->first();

        if(!$exit){ 
          return $this->reply(false,"Code de verification incorrect",null); 
        }

        if($exit && empty($request->input('newPassword'))){
            return $this->reply(true,"Code de vérification correct",null); 
        }
       
        $user = User::find($exit->user_id);
        $user->password =  bcrypt($request->input('newPassword'));
        $save = $user->save();

        if($save){
            return $this->reply(true,"Mot de passe réinitialisé avec succès. Connectez vous",null); 
        }

        return $this->reply(false,"Erreur interne veuillez réessayer plustard",null); 

    }
}
