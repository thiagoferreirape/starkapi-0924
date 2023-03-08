<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionsHistory extends Model
{
    protected $table = "transactions_history";
    protected $guarded = ["id"];
}
