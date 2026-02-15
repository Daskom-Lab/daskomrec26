<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'clue',
        'answer',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $attributes = [
        'status' => false,
    ];
}
