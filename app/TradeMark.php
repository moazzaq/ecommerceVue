<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TradeMark extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'logo',
    ];
}
