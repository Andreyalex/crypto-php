<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'asset',
        'interval',
        'time',
        'o',
        'h',
        'l',
        'c',
        'volume',
        'quote_volume',
        'buy_base_volume',
        'buy_quote_volume',
    ];
}
