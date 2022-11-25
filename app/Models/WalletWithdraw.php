<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletWithdraw extends Model
{
    use HasFactory;

    protected $table = "wallet_withdraws";

    protected $primaryKey = "id";

    protected $guarded = [];
}
