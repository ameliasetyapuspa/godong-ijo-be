<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role_id',
        'username',
        'password',
        'isActive',
        'token',
    ];

    protected $hidden = [
        'password',
        'token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function generateToken()
    {
        // Set the token expiration time (1 hour from now)
        $expiration = Carbon::now()->addHour()->timestamp;

        // Create a random token and append the expiration timestamp
        $this->token = base64_encode(Str::random(40) . '|' . $expiration);
        $this->save();
    }

    public function clearToken()
    {
        $this->token = null;
        $this->save();
    }

    public function isTokenValid()
    {
        if (!$this->token) {
            return false;
        }

        // Decode the token and extract the expiration timestamp
        $tokenParts = explode('|', base64_decode($this->token));
        $tokenExpiration = $tokenParts[1] ?? 0;

        // Check if the token is still valid
        return $tokenExpiration > Carbon::now()->timestamp;
    }
}
