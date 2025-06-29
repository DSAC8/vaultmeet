<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'creator',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
    ];

    public function creatorUser()
    {
        return $this->belongsTo(\App\Models\gallery_users::class, 'creator');
    }
}
