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
        'event_start_time',
        'event_end_time',
        'place_id',
        'creator_user_id',
        'description',
        'capacity_max',
        'capacity_current'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id', 'users');
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_attends_event');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_has_event');
    }
}
