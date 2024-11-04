<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'username', 'password', 'isActive', 'token'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Mutator to hash password before saving it to the database
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
