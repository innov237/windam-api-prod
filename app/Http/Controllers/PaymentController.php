<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PaymentProvider\Provider;
use App\Http\Controllers\PaymentProvider\StripController;
use App\Http\Controllers\PaymentProvider\PaypalController;
use App\Models\Facture;

class PaymentController extends Controller
{
    
    public function pay(Request $request)
    {
           $invoice=Facture::where("id",$request->invoice_id)->first();
           
           

        if (empty($invoice)) {
           return $this->reply(false,"invoice not found");
        }
        
       
        $request->request->add([
            "amount"=>$invoice->montant,
           "name"=>$invoice->demande->user->nom
        ]);



        if($request->provider_id == Provider::PAYPAL){
            return (new PaypalController())->payIn($request);
        }

        if($request->provider_id == Provider::STRIPE){
            return (new StripController())->payIn();
        }
        
        return $this->reply(false,'Provider not found',null);
    }

    public function Success(Request $request){
        return (new PaypalController())->onSuccess($request);
    }
}
