<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Service;
use App\Models\Translation;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

                $allCategory = Category::with(['translation' => function ($query) use ($request) {
                $query->where([['locale', $request->locale], ['type', "CATEGORY"]])->first();
            }])->get();


            return $this->reply(true, "liste des categorie", $allCategory);
      
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
            'nom_category' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|string',

        ]);

        // $path = ($request->file('image')) ?  $request->file('image')->store('public/imagesAsset') : null;

        $category = Category::create([
            'nom_category' => $fields['nom_category'],
            'description' => $fields['description'],
            'img' => $fields['image'],
        ]);


        return $this->reply(true, "Category ajouter avec success", $category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        $response = [

            'message' => 'Detail de cette category',
            'status' => 201,
            'success' => true,
            'results' => $category,
        ];

        return response($response, 201);
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

        $category = Category::find($id);
        $category->update($request->all());



        return $this->reply(true,"categorie modifier avec success",$category);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
           $category = Category::where('id', $id)->delete();

if ($category) {
    return $this->reply(true,"categorie suprimer avec success",null);
}
return $this->reply(false,"une erreur c'est produite",null);



    }
}
