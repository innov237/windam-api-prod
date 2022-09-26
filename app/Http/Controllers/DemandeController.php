<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Controllers\SendMailController;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }

        $demande = Demande::Where('user_id', auth()->id())->get();
        return $this->reply(true, "Demande dun utilisateur", $demande);
    }


    public function all(Request $request)
    {
        $demande = (new Demande($request->input('locale','fr')))->reorder('id', 'desc')->get();
        return $this->reply(true, "liste des demandes", $demande);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }

        $validator = Validator::make($request->all(), [
            'service_id' => ['required', 'exists:services,id'],
            'tel' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->reply(false, 'code.request.VALIDATION_ERROR', $validator->errors());
        }

  // $path = ($request->file('file')) ?  $request->file('file')->store('assets/images') : null;
        //return $request->file;
        //return auth()->id();

        $demande = new Demande();
    
        $demande->user_id = auth()->id();
        $demande->service_id = $request->service_id;
        $demande->tel = $request->tel;
        $demande->city = $request->city;
        $demande->description = $request->description;
        $demande->date = $request->date;
        $demande->heure = $request->heure;
        $demande->file =  $request->file;
        $demande->status = $request->input('status', 0);

        $save = $demande->save();
   
 
        $request->request->add([
            "user"=> "Nom: ".auth()->user()->nom ." Email: ".auth()->user()->email,
            "subject"=> "Nouvelle demande",
            "message"=> $request->description
        ]);

        (new SendMailController())->notify($request);
        
       
        if ($save) {
            return $this->reply(true, "Demande crée", $save);
        }

        return $this->reply(false, "Demande non crée", null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }

        $demande = Demande::find(auth()->user()->id);


        return $this->reply(true, "Detail de la demande", $demande);
    }


    public function userDemande($id)
    {
        $getDemande = Demande::where('user_id', $id)->get();

        if ($getDemande) {
            return $this->reply(true, "Demande ", $getDemande);
        }

        return $this->reply(false, "Demande introuvable", null);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }

        $getDemande = Demande::find($id);

        if ($getDemande) {
            if ($getDemande->status == 0) {
                if ($getDemande->update($request->all())) {
                    return $this->reply(true, "Mise a jour effectuer avec succes", $getDemande);
                } else {
                    return $this->reply(false, "Impossible d\'effectuer cette mise a jour", null);
                }
            } else {
                return $this->reply(false, "Impossible de modifier une demande qui est deja traiter . contacter les administrateur", null);
            }
        } else {
            return $this->reply(false, "Demande introuvable", null);
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

    public function notify(Request $request)
    {
        return (new  FCMNotificationController())->notify($request);
    }
}
