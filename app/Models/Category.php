<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_category_id',
        'is_approved'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_category_id', 'id');
    }
}
