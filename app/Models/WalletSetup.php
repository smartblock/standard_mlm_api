<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletSetup extends BaseModel
{
    use HasFactory;

    protected $table = "wallet_setups";

    protected $primaryKey = "id";

    protected $guarded = [];

    public function balance()
    {
        return $this->belongsToMany(User::class, 'wallet_summaries', 'wallet_id', 'user_id');
    }
}
