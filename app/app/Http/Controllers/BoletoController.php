<?php

namespace App\Http\Controllers;



use StarkBank\Boleto;





class BoletoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    

    

    
   
    public function boleto()
    {
        $boleto = Boleto::create([
            new Boleto([
                "amount" => intval(request()->input("amount")),
                "name" => request()->input("nome"),
                "taxId" => request()->input("identificador"),
                "streetLine1" => request()->input("endereco"),
                "streetLine2" => request()->input("complemento"),
                "district" => request()->input("bairro"),
                "city" => request()->input("cidade"),
                "stateCode" => request()->input("uf"),
                "zipCode" => request()->input("cep"),
                "due" => request()->input("vencimento"),


            ])
        ]);

        return $boleto;
    }

    public function boletoById()
    {

        $boleto = Boleto::get(request()->input('boleto_id'));
        return response()->json($boleto);
    }

    public function boletoPDF($id)
    {
        $boleto = Boleto::pdf($id);
        $fp = fopen(storage_path('boleto-' . $id . '.pdf'), 'w');
        fwrite($fp, $boleto);
        fclose($fp);
    }
}
