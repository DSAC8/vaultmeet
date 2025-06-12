<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valasz extends Model
{
    protected $table = 'valaszok';

    public function kulcsszo()
    {
        return $this->belongsTo(Kulcsszo::class, 'kulcsszo_id');
    }
}
