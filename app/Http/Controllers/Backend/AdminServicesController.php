<?php

namespace App\Http\Controllers\Backend;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Demande;

class AdminServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allService= Service::all();
/* 
        $response = [
            
            'message' => 'Liste des service ',
            'status' => 201,
            'success'=> true,
            'results' => $allService ,
        ];
        return response($response, 201); */
        return $this->reply(true,"liste des seervices",$allService);
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findOne(int $id)
    {
        $category= Service::all()->where('id',$id);

        $response = [
            
            'message' => 'detail du service ',
            'status' => 201,
            'success'=> true,
            'results' => $category ,
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
            'category_id' =>$fields['category_id'],
            'nom_service' =>$fields['nom_service'],
            'description' =>$fields['description'],
            'img' =>$fields['img'],
            'banner' =>$fields['banner'],
            'montant_min' =>$fields['montant_min'],
        ]);



        $response = [
            
            'message' => 'service créer',
            'status' => 201,
            'success'=> true,
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
        $category = Service::find($id);
        $category->update($request->all());

        return $this->reply(true,"service modifier avec success",$category);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Service::find($id);
            $getdemande=Demande::where("service_id",$id)->first();
            if ($getdemande) {
                return $this->reply(true,"SuprESSION IMPOSSIBLE",null);
            }
        $category->delete();
        return $this->reply(true,"Service suprimer avec success",$category);

    }
}
