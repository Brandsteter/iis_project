<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_approved',
        'event_start',
        'event_end',
        'place_id',
        'description',
        'capacity_max',
        'capacity_current'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_attends_event');
    }

}
