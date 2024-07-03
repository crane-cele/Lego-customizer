<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomSet extends Model
{
    use HasFactory;

    protected $fillable = ['set_num', 'part_num', 'quantity'];
}