<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = "user_addresses";

    protected $primaryKey = "id";

    protected $guarded = [];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = Auth::user()->id ?? null;
            $query->updated_by = Auth::user()->id ?? null;
        });

        static::updating(function ($query) {
            $query->updated_by = Auth::user()->id ?? null;
        });
    }

    public function country()
    {
        return $this->belongsTo(SysCountry::class, 'country_id', 'id');
    }
}
