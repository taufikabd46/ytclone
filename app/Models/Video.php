<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'title',
        'description',
        'url',
        'thumbnail_url',
        'category_id', // Foreign key to the categories table
        'platform', // Platform of the video (Instagram, Facebook, YouTube)
        'username', // Username of the video owner
        'order', // Order of the video in the list
    ];

    // Define the relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class); // A video belongs to a single category
    }
}
