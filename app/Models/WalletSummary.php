<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletSummary extends Model
{
    use HasFactory;

    protected $table = "wallet_summaries";

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
