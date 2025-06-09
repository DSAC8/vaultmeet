<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class gallery_users extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'pfp', 'location', 'pass_token'];

    protected $hidden = [
        'password'
    ];
    protected $primaryKey = 'id';
    public $timestamps = true;
}


