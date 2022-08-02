<?php

namespace App\Http\Controllers;

use App\Models\avis;
use App\Models\demande;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $getavislist = avis::all()->where('idDemande', $id);

        if (empty($getavislist)) {
            $response = [

                'message' => 'Liste de mes avis',
                'status' => 201,
                'success' => true,
                'results' => $getavislist,
            ];

            return response($response, 201);
        } else {
            $response = [

                'message' => 'Vous n\'avez aucun avis pour le moment',
                'status' => 501,
                'success' => false,

            ];

            return response($response, 501);
        }
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
            'idDemande' => 'required|integer',
            'note' => 'required|integer',
            'comments' => "required|string",
        ]);

        $category = avis::create([
            'idDemande' => $fields['idDemande'],
            'note' => $fields['note'],
            'comments' => $fields['comments'],
        ]);

        $response = [

            'message' => 'avis ajouter avec success',
            'status' => 201,
            'success' => true,
            'results' => $category,
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
        $getavislist = avis::find($id);

        if ($getavislist) {

            if ($getavislist->delete()) {
                    $response = [

                'message' => 'avis supprimer avec succes',
                'status' => 201,
                'success' => true,
                'results' => $getavislist,
            ];

            return response($response, 201); 
            } else {
                $response = [
                    'message' => 'impossible de suprimer cette avis',
                    'status' => 501,
                    'success' => false,

                ];

                return response($response, 501);
            }
        } else {
            $response = [
                'message' => 'Avis innexistant',
                'status' => 501,
                'success' => false,

            ];

            return response($response, 501);
        }
    }
}
