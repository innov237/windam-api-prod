<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatsUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allChat()
    {
      
        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }
       
        $alluserChat = Chat::where('receiver_id', auth()->user()->id)->orWhere('sender_id', auth()->user()->id)->reorder('id', 'desc')->get();

        return $this->reply(true, "liste de mes message", $alluserChat);
    }

    public function responseChat(Request $request)
    {
        // return  auth()->user();

        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }


        $updatechat = Chat::where('receiver_id', auth()->user()->id)->where('message_id', $request->message_id)->first();

        if (!$updatechat) {
            return $this->reply(false, "message recue", null);
        }

        DB::beginTransaction();

        try {

            $updatechat->status = 1;
            $updatechat->save();



            $chat = new Chat();

            $chat->invoices_id = $updatechat->invoices_id;
            $chat->isender_id = auth()->user()->id;
            $chat->receiver_id = $updatechat->isender_id;
            $chat->message_id = $updatechat->message_id;
            $chat->message = $request->message;
            $chat->status = 0;
            $chat->save();


            DB::commit();
            return $this->reply(true, "Message envoyer avec success", $chat);
        } catch (\Exception $e) {
            return $e;
            DB::rollback();
        }
    }

    
    public function sendChat(Request $request)
    {
 

        if (empty(auth()->user())) {
            return $this->reply(false, "Utilisateur introuvable", null);
        }


        
        DB::beginTransaction();

        try {

            $chat = new Chat();
            $chat->invoices_id = null;
            $chat->sender_id = auth()->user()->id;
            $chat->receiver_id = User::ADMIN_ID;
            $chat->message_id = null;
            $chat->message = $request->message;
            $chat->status = 0;
            $chat->save();

            DB::commit();
            $request->request->add([
                "user_id" => User::ADMIN_ID,
                "body" => $request->message,
                "title" => "Nouveau message",
                "channel" => "NewMessage"
            ]);
            (new  FCMNotificationController())->notify($request);
            return $this->reply(true, "Message envoyer avec success", $chat);
        } catch (\Exception $e) {
            return $e;
            DB::rollback();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findOne(int $id)
    {
        $category = Service::all()->where('id', $id);

        $response = [

            'message' => 'detail du service ',
            'status' => 201,
            'success' => true,
            'results' => $category,
        ];
        return response($response, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {




        $fields = $request->validate([
            'category_id' => 'required|integer',
            'nom_service' => 'required|string|unique:services,nom_service',
            'description' => 'required|string',
            'img' => 'required|string',
            'banner' => 'required|string',
            'montant_min' => 'required|integer',
        ]);





        $demande = Service::create([
            'category_id' => $fields['category_id'],
            'nom_service' => $fields['nom_service'],
            'description' => $fields['description'],
            'img' => $fields['img'],
            'banner' => $fields['banner'],
            'montant_min' => $fields['montant_min'],
        ]);



        $response = [

            'message' => 'service créer',
            'status' => 201,
            'success' => true,
            'results' => $demande,
        ];

        return response($response, 201);
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
    public function update(Request $request, $id)
    {
        //
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
