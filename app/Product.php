<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
//        'name_en',
//        'email',
//        'mobile',
//        'facebook',
//        'twitter',
//        'address',
//        'website',
//        'contact_name',
//        'lat',
//        'lng',
//        'icon',
//        'country_id',
    ];
    public function files(){
        return $this->hasMany('App\File','relation_id','id')->where('file_type','product');
    }
}
