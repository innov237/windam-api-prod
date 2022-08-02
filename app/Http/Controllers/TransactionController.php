<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Facture;
use App\Models\Demande;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function verify($transaction)
    {

            $getDemande = Facture::where('id',$transaction->invoices_id )->first();

            $facture = Facture::where('id', $transaction->invoices_id )->update([
                'status' => 1,
            ]);

                Demande::where('id', $getDemande->demande_id)->update([
                'status' => 1,
            ]);

            Transaction::where('invoices_id', $transaction->invoices_id )->update([
                'paid_date' =>  date('Y-m-d', time()),
            ]);

      //  return $this->reply(true, "Transaction effectuer avec success", $facture);
    }

    public function successPaid($data)
    {
    }
}
