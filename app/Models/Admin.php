<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\NewAccessToken;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $table = "users";

    protected $primaryKey = "id";

    protected $guarded = [];

    protected $guard_name = "admin";

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
