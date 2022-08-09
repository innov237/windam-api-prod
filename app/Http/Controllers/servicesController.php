<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class ServicesController extends Controller
{


    /**
     * Display a listing of the category with services.
     *
     * @return \Illuminate\Http\Response
     */
    public function getServiceWithCategory(Request $request)
    {

        $locale = $request->locale;
        
        if(empty($locale)){
            $locale = "fr";
        }

        $allCategory = Category::with(['services'=> function($query) use ($locale){
            $query->with(['translation' => function ($queryt) use ($locale) {
                $queryt->where([['locale', $locale], ['type', "SERVICE"]]);
            }])->get();
        },'translation' => function ($query) use ($locale) {
                $query->where([['locale', $locale], ['type', "CATEGORY"]])->get();
        }])->get();

        if ($allCategory) {
            return $this->reply(true, "liste des categorie", $allCategory);
        } else {
            return $this->reply(false, "Aucune categorie", null);
        }
        return $this->reply(false, "Aucune categorie", null);

    }


    /**
     * Display a listing of the all service.
     *
     * @return \Illuminate\Http\Response
     */
    public function allServices()
    {
        // var_dump($this::inputFormatter("ok"));
        $category = Service::all();
        //  $category = category::with('services')->paginate(1);
        if ($category and count($category) > 0) {
            return $this->reply(true, "liste des services par categorie", $category);
        } else {
            return $this->reply(false, "Aucun service", null);
        }
        return $this->reply(false, "Aucun service", null);
    }



    /**
     * Display a listing of the all category of services.
     *
     * @return \Illuminate\Http\Response
     */
    public function allCategory()
    {
        return $this->reply(true, "liste des category", Category::all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        //  return category::with('services')->where('nom_service','like','%'.$name.'%')->get();
        $serviceByCategory = Service::where('nom_service', 'like', '%' . $request->input('key') . '%')
            ->with(['translation' => function ($query) use ($request) {
                    $query->where([['locale', $request->input('locale')], ['type', "SERVICE"]]);
                }])->paginate(20);

        if ($serviceByCategory) {
            return $this->reply(true, "Resultat de la recherche", $serviceByCategory);
        }
        $this->reply(false, "Aucune categori", null);
    }

    /**
     * display a listing of services by a category
     * @return \Illuminate\Http\Response
     */
    public function serviceByCategory($id)
    {
        $serviceByCategory = Service::where('category_id', $id)->get();
        //return $serviceByCategory;
        if ($serviceByCategory) {
            return $this->reply(true, "Liste des service par category", $serviceByCategory);
        } else {
            return $this->reply(false, "Aucune categories", null);
        }
        //   return $this::ApiResponse($serviceByCategory, 201, "Liste des service par category", true);
    }


    public function inputFomater($data)
    {
        return $data;
    }
}
