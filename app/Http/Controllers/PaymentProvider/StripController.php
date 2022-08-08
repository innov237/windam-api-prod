<?php

namespace App\Http\Controllers\PaymentProvider;

use Exception;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\TransactionController;
use App\Models\Transaction;

class StripController extends Controller
{
    public function payIn()
    {
        $balance = request()->amount;
       
        $validator = Validator::make(array_merge(request()->all(), ["amount" => $balance]), [
            'card_no' => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
           // 'amount' => 'min:300',
        ]);
        if ($validator->passes()) {

            try {
                $stripe = (new Stripe())->setApiKey(env('STRIPE_SECRET'));
               
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => request()->get('card_no'),
                        'exp_month' => request()->get('ccExpiryMonth'),
                        'exp_year' => request()->get('ccExpiryYear'),
                        'cvc' => request()->get('cvvNumber'),
                    ],
                ]);

                if (!isset($token['id'])) {
                    return $this->reply(false,"Failure", $token);
                }
                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    //'customer' =>  $transaction->id,
                    'currency' => 'EUR',
                    //'receipt_email' => $user->email,
                    'amount' => $balance,
                    'description' => "paiement facture"
                ]);
                $transaction = Transaction::where("invoices_id",request()->get('invoice_id'))->first();
            
                if (empty($transaction)) {
                    return $this->reply(true, "Transaction not found", $transaction);
                } 

                if ($charge['status'] == 'succeeded') {
                    $transaction->logs =json_encode($charge);
                    $transaction->payment_token =  $charge['id'];
    
                    $transaction->save();

                 
                    TransactionController::verify($transaction);
                    return $this->reply(true,"Paiment éffectué", $charge);
                } else {
                    return $this->reply(false,"Erreur de paiement", $charge);
                }
            } catch (Exception $e) {
                return $this->reply(false,"Erreur de paiement", $e->getMessage());
            }
        }

        return $this->reply(false,"Erreur de validation",$validator->errors());
     
    }

    public function payOut($transaction, $user)
    {
        // TODO: Implement payOut() method.
    }

    public function getAccount($userId = null)
    {
        return env('STRIPE_SECRET');
    }

    public function onSuccess(Request $request)
    {
        // TODO: Implement onSuccess() method.
    }

    public function onCancel(Request $request)
    {
        // TODO: Implement onCancel() method.
    }

    public function getId()
    {
        return Provider::STRIPE;
    }
}
