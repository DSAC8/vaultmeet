<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kulcsszo extends Model
{
    protected $table = 'kulcsszavak';

    public function valaszok()
    {
        return $this->hasMany(Valasz::class, 'kulcsszo_id');
    }
}
