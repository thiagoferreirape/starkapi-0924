<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = "transactions";
    protected $guarded = ["id"];

    public function history(){
        return $this->hasMany(TransactionsHistory::class,'transaction_id','transaction_id');
    }
}
