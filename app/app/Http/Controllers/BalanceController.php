<?php

namespace App\Http\Controllers;


use StarkBank\Balance;


class BalanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
    }



    public function getBalance()
    {

        $balance = Balance::get();
        return response()->json(['saldo' => $balance->amount, 'moeda' => $balance->currency, "updated_at" => $balance->updated->format('d-m-Y H:m:s')], 200);
    }
}
