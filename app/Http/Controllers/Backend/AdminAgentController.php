<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Agents;
use Illuminate\Http\Request;

class AdminAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allAgent= Agents::all();

      
        return $this->reply(true,"Liste des agent disponible ",$allAgent);

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
            'nom_agent' => 'required|string|unique:agents,nom_agent',
            'tel' => 'required|string|unique:agents,tel',
            'email' => 'required|string|unique:agents,email',
            'description' => 'required|string',
            'image' => 'required|string',
            'service_id' => 'required|integer',

        ]);
 
        $agent =new Agents();
        $agent->nom_agent = $fields['nom_agent'];
        $agent->tel =$fields['tel'];
        $agent->email =$fields['email'];
        $agent->description =$fields['description'];
        $agent->image =$fields['image'];
        $agent->service_id =$fields['service_id'];
        $agent->active =1;
        $agent->save();

        return $this->reply(true,"Agent ajouter",$agent);
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
    public function update(Request $request,$id)
    {
        $fields = $request->validate([
            'nom_agent' => 'required|string|unique:agents,nom_agent',
            'tel' => 'required|integer|unique:agents,tel',
            'email' => 'required|string|unique:agents,email',
            'description' => 'required|string',
            'service' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',

        ]);

        $getAgent=Agents::find($id);
        $getAgent->nom_agent=$fields['nom_agent'];
        $getAgent->tel=$fields['tel'];
        $getAgent->email=$fields['email'];
        $getAgent->description=$fields['description'];
        $getAgent->service=$fields['service'];

       

       if($getAgent->save()){

        $response = [
            
            'message' => 'Agent Modifier',
            'status' => 201,
            'success'=> true,
            'results' => $getAgent,
        ];

        return response($response, 201);
       } else{
        $response = [
            
            'message' => 'Impossible de modifier l\'agent',
            'status' => 501,
            'success'=> false,
        ];

        return response($response, 501);
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
}
