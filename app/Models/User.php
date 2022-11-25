<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\NewAccessToken;

use Str;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'code',
        'name',
        'email',
        'password',
        'status',
        'country_id',
        'referral_code',
        'email_verified_at',
        'parent_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = "api";

    public function country()
    {
        return $this->belongsTo(SysCountry::class, 'country_id', 'id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function wallet()
    {
        return $this->hasMany(WalletSummary::class, 'user_id', 'id');
    }

    public function createToken(string $name, $abilities = ['*'], $minute = 60)
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'abilities' => $abilities,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'expires_at' => Carbon::now()->addMinutes($minute)
        ]);

        return new NewAccessToken($token, $plainTextToken);
    }
}
