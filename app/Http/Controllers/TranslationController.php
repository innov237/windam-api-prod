<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends  Controller
{




    public function getServiceTranlation($service_id, $type)
    {


        $allserviceTranslation = Translation::where([["table_id", $service_id], ["type", $type]])->get();

        return $this->reply(true, "liste des traductiob du service", $allserviceTranslation);
    }

    public function editServiceTranslation(Request $request, $id)
    {

        $gettr = Translation::find($id)->update([
            "label" => $request->label,
            "description" => $request->description
        ]);

        if ($gettr) {
            return $this->reply(true, "Update ok", $gettr);
        }
    }


    public function deleteServiceTranslation($id)
    {
        $gettr = Translation::find($id)->delete();

        if ($gettr) {
            return $this->reply(true, "Translation deleted", $gettr);
        } else {
            return $this->reply(false, "error durring deletetion", null);
        }
    }
    public function postServiceTranslation(request $request)
    {

        //  return $request->description;

        $find=Translation::where([["locale",$request->locale],["table_id",$request->id],["type",$request->type]])->first(); 
        if ($find) {
            return $this->reply(false,"Déjà traduire",null);
        }

        $add = new Translation();

        $add->table_id = $request->id;
        $add->label = $request->label;
        $add->description = $request->description;
        $add->locale = $request->locale;
        $add->type = $request->type;

        $add->save();

        if ($add) {
            return $this->reply(true, "add translation", $add);
        } else {
            return $this->reply(false, "error adding", null);
        }
    }
}
