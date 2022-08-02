<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Demande;
use App\Models\Facture;
use App\Models\Transaction;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;

use Illuminate\Support\Facades\Validator;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function invoicePaid(Request $request)
    {

        //  return $request;
        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }

        DB::beginTransaction();

        try {

            $facture = Facture::where('id', $request->invoice_id)->where('user_id', auth()->user()->id)->update([
                'status' => 1,
            ]);

            Demande::where('id', $request->demande_id)->update([
                'status' => 0,
            ]);
            Transaction::where('invoices_id', $request->invoice_id)->update([
                'paid_date' =>  date('Y-m-d', time()),
            ]);



            DB::commit();
        } catch (\Exception $e) {
            return $e;
            DB::rollback();
        }

        return $this->reply(true, "Transaction effectuer avec success", $facture);
    }

    public function invoiceReject(Request $request)
    {

        //  return $request;
        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }

        $facture = Facture::where('id', $request->invoice_id)->where('user_id', auth()->user()->id)->first();

        DB::beginTransaction();

        try {

            $facture->update([
                'status' => 2,
            ]);



            $chat = new Chat();
            $chat->invoices_id = $facture->id;
            $chat->sender_id =  auth()->user()->id;
            $chat->receiver_id = User::ADMIN_ID;
            $chat->message_id = $request->message_id;
            $chat->message = $request->message;
            $chat->status = 0;
            $chat->save();

   Demande::where('id', $facture->demande_id)->update([
                'status' => 2,
            ]);




            DB::commit();
        } catch (\Exception $e) {
            return $e;
            DB::rollback();
        }

        return $this->reply(true, "Devis rejecter", $chat);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {


            $setFacture = new Facture();
            $setFacture->montant = $request->montant;
                 $setFacture->tva = $request->tva;
            $setFacture->description = $request->description;
            $setFacture->demande_id = $request->idDemande;
            $setFacture->user_id = $request->receiver_id;
            $setFacture->lignecmd = $request->lignecmd == null ? null : $request->lignecmd;
            $setFacture->status = 0;
            $setFacture->save();

            $transaction = new Transaction();
            $transaction->invoices_id = $setFacture->id;
            $transaction->init_date = date("Y-m-d");
            $transaction->save();


            $chat = new Chat();
            $chat->invoices_id = $setFacture->id;
            $chat->sender_id = $request->sender_id;
            $chat->receiver_id = $request->receiver_id;
            $chat->message_id = $chat->id;
            $chat->message = $request->message;
            $chat->status = 0;
            $chat->save();

            $updatechat = Chat::where('id', $chat->id)->update([
                'message_id' => $chat->id,
            ]);


            $demande = Demande::where('id', $request->idDemande)->update([
                'status' => 4,
            ]);

            DB::commit();
            
            $request->request->add([
                "user_id" => $request->receiver_id,
                "body" => "Vous Avez reçu une facture",
                "title" => "Facturation",
                "channel" => "Facturation"
            ]);
            
            (new  FCMNotificationController())->notify($request);
        } catch (\Exception $e) {
            return  $this->reply(true, "Facture envoyée avec succès ", $e);
            DB::rollback();
        }




        return $this->reply(true, "Facture envoyée avec succès ", $setFacture);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    
  

      DB::beginTransaction();

        try {


            $setFacture = new Facture();
            $setFacture->montant = $request->montant;
                 $setFacture->tva = $request->tva;
            $setFacture->description = $request->description;
            $setFacture->demande_id = $request->idDemande;
            $setFacture->user_id = $request->receiver_id;
            $setFacture->lignecmd = $request->lignecmd == null ? null : $request->lignecmd;
            $setFacture->status = 0;
            $setFacture->save();

            $transaction = new Transaction();
            $transaction->invoices_id = $setFacture->id;
            $transaction->init_date = date("Y-m-d");
            $transaction->save();


            $chat = new Chat();
            $chat->invoices_id = $setFacture->id;
            $chat->sender_id = $request->sender_id;
            $chat->receiver_id = $request->receiver_id;
            $chat->message_id = $chat->id;
            $chat->message = $request->message;
            $chat->status = 0;
            $chat->save();

            $updatechat = Chat::where('id', $chat->id)->update([
                'message_id' => $chat->id,
            ]);


            $demande = Demande::where('id', $request->idDemande)->update([
                'status' => 4,
            ]);

            DB::commit();
            
            $request->request->add([
                "user_id" => $request->receiver_id,
                "body" => "Vous Avez reçu une facture",
                "title" => "Facturation",
                "channel" => "Facturation"
            ]);
            
            (new  FCMNotificationController())->notify($request);
        } catch (\Exception $e) {
            return  $e;
            DB::rollback();
        }




        return $this->reply(true, "Facture envoyée avec succès ", $setFacture);
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
}
