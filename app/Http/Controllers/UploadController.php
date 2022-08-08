<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function uploadImageV1(Request $request)
    {
        $path = ($request->file('image')) ?  $request->file('image')->store('public/imagesAsset') : null;

        return  $this->reply(true, "image uploaded succesfull", asset(str_replace("public", "storage", $path)));
    }

    public function uploadImage(Request $request)
    {
      
         $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,mp4|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->reply(true, "image invalide", $validator->errors());
        } 

        //$path = ($request->file('image')) ?  $request->file('image')->store('assets/images') : null;
        //return  $this->reply(true, "image uploaded succesfull", $path);
 
        try {
            $imageName = time().'.'.$request->image->extension();
            $path = Storage::disk('s3')->put('images', $request->image);
            $path = Storage::disk('s3')->url($path);
        
            return $this->reply(true, "image uploaded succesfull", $path);
        } catch (Exception $ex) {
            return $this->reply(false, "Error", $ex->getMessage());
        }


    }
}
