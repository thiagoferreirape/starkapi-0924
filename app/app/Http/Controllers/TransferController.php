<?php

namespace App\Http\Controllers;

use Exception;



use StarkBank\Transfer;

use Illuminate\Http\Request;
use App\Models\Transactions;


class TransferController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
    }





    public function transferById($id)
    {



        $transfer = Transfer::get($id);
        return response()->json($transfer);
    }

   



    public function transfer()
    {


        if (request()->missing(['bankCode', 'amount', 'branch', 'account', 'taxId', 'name'])) {
            return response()->json("some parameters are missing", 400);
        }




        try {
            $transfer = Transfer::create([
                new Transfer([
                    "amount" => intval(request()->input("amount")),
                    "bankCode" => request()->input("bankCode"),
                    "branchCode" => request()->input("branch"),
                    "accountNumber" => request()->input("account"),
                    "accountType" => "salary",
                    "taxId" => request()->input('taxId'),
                    "name" => request()->input('name'),
                    "externalId" => uniqid(),
                    "description" => "Transaction to " . request()->input('name'),
                    "tags" => []
                ])
            ])[0];



            Transactions::create(["transaction_id" => $transfer->id, "status" => $transfer->status , "type" => "transfer"]);
            $response = [$transfer];
        } catch (\Exception $e) {
            $response = ["error" => $e->getMessage()];
        }

        return response()->json($response, isset($transfer) ? 200 : 500);
    }

    public function transfersList()
    {
        if (request()->missing(['dataInicial', 'dataFinal'])) {
            return response()->json("some parameters are missing", 400);
        }


        $transfers = Transfer::query([
            "after" => request()->input("dataInicial"),
            "before" => request()->input("dataFinal"),
        ]);

        $trans = iterator_to_array($transfers);
        return response()->json(["trans" => $trans], 200);
    }

    

    public function transferHistory($id)
    {
        $trans = Transactions::with('history')->where("transaction_id",$id)->get();
        return $trans;
    }

    public function getPDF($id)
    {

        $pdf = Transfer::pdf($id);
        return $pdf;
    }
}
