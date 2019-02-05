<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'sitename_ar', 'sitename_ar','logo','icon','email','main_lang','description','keywords','message_maintanance',
        'status'
    ];
}
