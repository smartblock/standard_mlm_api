<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;

class ProductCategory extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "product_categories";

    protected $primaryKey = "id";

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

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
}
