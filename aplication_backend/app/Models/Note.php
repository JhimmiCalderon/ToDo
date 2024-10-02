<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    public $table  = "notes";
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'image',
        'user_id'
    ];

    
}
