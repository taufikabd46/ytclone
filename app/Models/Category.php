<?php

// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'name', 
        'description',
    ];
}

