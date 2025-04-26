<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'randomize',
    ];
}
