<?php

namespace App\Http\Controllers;


use StarkBank\Invoice;


class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    public function invoiceById()
    {
        $invoice = Invoice::get(request()->input("invoice_id"));
        return response()->json($invoice);
    }

    public function invoicesList()
    {
        $invoices = iterator_to_array(
            Invoice::query([
                "limit" => 10,

            ])
        );

        return $invoices;
    }
    public function invoice()
    {
        $invoices = [
            new Invoice([
                "amount" => intval(request()->input("amount")),
                "due" => request()->input("vencimento"),
                "taxId" => request()->input("identificador"),
                "name" => request()->input("nome"),


            ])
        ];

        $invoice = Invoice::create($invoices);
        return $invoice;
    }

    public function invoiceQR($id)
    {
        $qr = Invoice::qrcode($id, ["size" => 7]);

        $fp = fopen(storage_path('invoice-qr-' . $id . '.png'), 'w');
        fwrite($fp, $qr);
        fclose($fp);
    }
}
