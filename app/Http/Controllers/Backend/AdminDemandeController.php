<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;

class AdminDemandeController extends Controller
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setAgent($id, $agent_id)
    {
        $getdemande = Demande::find($id);
        if ($getdemande->status == 0) {

            $getdemande->agent_id = $agent_id;
            $getdemande->status = 1;

            $getdemande->save();
            $response = [

                'message' => 'Agent attribuer avec success',
                'status' => 201,
                'success' => true,
                'results' => $getdemande,
            ];
            return response($response, 201);
        } else {
            
        $getdemande->agent_id = $agent_id;

        $getdemande->save();
        $response = [

            'message' => 'Agent attribuer avec success',
            'status' => 201,
            'success' => true,
            'results' => $getdemande,
        ];
        return response($response, 201);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $status)
    {


        $getdemande = Demande::find($id);
        $getdemande->status = $status;

        $getdemande->save();

        return $this->reply(true,"Mise ajour du status avec success",$getdemande);
       
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
