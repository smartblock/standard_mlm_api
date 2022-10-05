<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Auth;

class AnnouncementDetail extends BaseModel
{
    use HasFactory;

    protected $table = "announcement_details";

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

    public function language()
    {
        return $this->belongsTo(SysLanguage::class, 'language_id', 'id');
    }
}
