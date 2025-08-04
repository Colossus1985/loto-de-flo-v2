<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeux extends Model
{
    use HasFactory;
    protected $table = 'jeux';
    protected $guarded = [];
}
