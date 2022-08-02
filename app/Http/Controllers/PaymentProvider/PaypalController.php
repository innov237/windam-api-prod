<?php

namespace App\Http\Controllers\PaymentProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Srmklive\PayPal\Services\ExpressCheckout;
use App\Models\Transaction;
use App\Http\Controllers\TransactionController;

class PaypalController extends Controller
{
    //NEW---
    protected $provider;

    public function __construct()
    {
        $this->provider = new ExpressCheckout();
    }

    //
    public function payIn(Request $request)
    {

        
        $product = [];
        $amount = $request->amount;
        $product['items'] = [
            [
                'name' =>"$request->name",
                'price' => number_format($amount, 2),
                'desc' => json_encode(array(
                    "operation" => "DEPOSIT",
                    "total" => number_format($amount, 2),//New
                      "request" =>[
                        "invoice_id"=>$request->invoice_id
                    ],
                )),
                'qty' => 1
            ]
        ];

        $product['invoice_id'] = $request->invoices_id;
        $product['invoice_description'] = "Order #{$product['invoice_id']} " . "Bill payment";
        $product['return_url'] = route('paypal.success');
        $product['cancel_url'] = route('paypal.cancel');
        $product['total'] = number_format($amount, 2);

        $paypalModule = $this->provider;
        $paypalModule->setApiCredentials(config('paypal'));

        $res = $paypalModule->setExpressCheckout($product, true);

        if (empty($res['paypal_link'])) {
            return $this->reply(false, 'Paypal not available', [$product, $res]);
        }

        return $this->reply(true, null, [
            'url' => $res['paypal_link'],
        ]);
    }

    public function payOut($transaction, $user)
    {
    }

    public function onSuccess(Request $request)
    {
       
        $token = $request->get('token');
        $PayerID = $request->get('PayerID');
        
        $paypalModule = new ExpressCheckout();
        $response = $paypalModule->getExpressCheckoutDetails($request->token);
        
     
       
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
          
          
            $invoiceData=json_decode($response["L_DESC0"]);
         
            
             $transaction = Transaction::where("invoices_id",$invoiceData->request->invoice_id)->first();
          
            if (empty($transaction)) {
                return $this->reply(false, "Transaction not found", $transaction);
            } 
            
          
           

            // Perform transaction on PayPal
            // and get the payment status


            $cardItem = $this->getCard($response);



          
            $payment_status = $paypalModule->doExpressCheckoutPayment($cardItem, $token, $PayerID);
            
          
            $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];
            $paymentInfo = $payment_status['PAYMENTINFO_0_ACK'];
            $ack = $payment_status['ACK'];
         
            if ("COMPLETED" == strtoupper($status) && "SUCCESS" == strtoupper($paymentInfo) && ("SUCCESS" == strtoupper($ack) || "SUCCESSWITHWARNING" == strtoupper($ack))) {
                $transaction->logs = $response;
                $transaction->payment_token = $request->token;

                $transaction->save();
               
                TransactionController::verify($transaction);

                return $this->reply(true, 'Payment was successful.', $response);
            }
        }
        return $this->reply(true, "Something went wrong", null);
    }

    public function getCard($transactionData)
    {
         
           $invoiceData = json_decode($transactionData["L_DESC0"]);
          
        return [
            // if payment is recurring cart needs only one item
            // with name, price and quantity
            'items' => [
                [
                    // 'name' => $transactionData['L_NAME0'],
                    'name' => $transactionData['FIRSTNAME'],
                    'price' => $transactionData['PAYMENTREQUEST_0_AMT'],
                    'qty' => $transactionData['L_PAYMENTREQUEST_0_QTY0'],
                ],
            ],

            'invoice_id' => $invoiceData->request->invoice_id,
            'invoice_description' => $transactionData['PAYMENTREQUEST_0_DESC'],
            'return_url' => route('paypal.success'),
            'cancel_url' => route('paypal.cancel'),
            //'total' => $transactionData['PAYMENTREQUEST_0_AMT'],
            'total' => $invoiceData->total,
        ];
    }

    public function onCancel(Request $request)
    {
        return $this->liteResponse(config("code.request.FAILURE"), "Please Retry later");
    }

    public function getAccount($userId = null)
    {
        return "sb-nek5h3412442@personal.example.com";
        // return env('PAYPAL_SANDBOX_API_USERNAME');
    }

    public function getId()
    {
        return Provider::PAYPAL;
    }
}
