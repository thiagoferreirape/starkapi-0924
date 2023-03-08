<?php

namespace App\Http\Controllers;

use App\Models\TransactionsHistory;
use App\Models\Transactions;
use App\Models\TransactionsReceipt;
use App\Http\Controllers\TransferController;
use Illuminate\Http\Request;
use Exception;
use StarkBank\Webhook;
use Illuminate\Support\Facades\DB;


class BaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $status;
    protected $transaction_id;

    public function __construct()
    {
    }


    public function registerHook()
    {
         $webhooks = Webhook::create([
             "url" => "https://starkapi-3.redesistemaeaplicativo.com.br/monitor",
             "subscriptions" => ["transfer"]
         ]);
        
         return $webhooks;

       
    }

    public function handleBucket()
    {
       
        
        if (request()->query('status') == "success") {
            $trans_id = request()->query('transaction_id');
            $tc = new TransferController();
            $pdf = $tc->getPDF($trans_id);
            $bucket = 'selecao-dev-pedeai';
           
           
            
            $result = app('s3')->putObject([
                'Bucket' => $bucket,
                'Key' => uniqid().'.pdf',
                'Body' => $pdf 
            ]);

            if($result['ObjectURL']){
                TransactionsReceipt::create(['transaction_id' => $trans_id , 'url' => $result['ObjectURL']]);
            }
            
            
        }
    }

    

    public function monitor(Request $request)
    {
        $data = $request->json()->all();
        $sub = $data["event"]["subscription"];
        $this->transaction_id = $data["event"]["log"][$sub]["id"];
        $this->status = $data["event"]["log"][$sub]["status"];

        DB::beginTransaction();
        try {
            TransactionsHistory::create([
                "type" => $sub,
                "transaction_id" => $this->transaction_id,
                "status" => $this->status
            ]);

            Transactions::where("transaction_id", $this->transaction_id)->update(["status" => $this->status]);
            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json(["error" => $e->getMessage(), 500]);
        }


        $this->handleStatus();
        return response()->json([], 200);
    }
    private function handleStatus()
    {

        if ($this->status == "success" || $this->status == "failed") {
            $client = new \GuzzleHttp\Client();
            $response = $client->request("GET", env('ENDPOINT_ATIVIDADE'),[
                'query' => ['transaction_id' => $this->transaction_id,'status' => $this->status]
            ]);
            return $response->getBody();
        }
    }
}
