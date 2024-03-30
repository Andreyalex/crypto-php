<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarnApr extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset',
        'earn_apr',
    ];
}
