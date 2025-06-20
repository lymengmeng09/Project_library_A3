<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    use HasFactory;



    // Fields that can be filled with data
    protected $fillable = [
        'title',
        'author',
        'published_date',
    ];

    
    // created_at and updated_at will be handled automatically
    public $timestamps = true;
}


