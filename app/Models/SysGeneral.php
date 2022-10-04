<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class SysGeneral extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "sys_generals";

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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
