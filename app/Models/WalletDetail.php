<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletDetail extends BaseModel
{
    use HasFactory;

    protected $table = "wallet_details";

    protected $primaryKey = "id";

    protected $guarded = [];

    public function wallet()
    {
        return $this->belongsTo(WalletSetup::class, 'wallet_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
