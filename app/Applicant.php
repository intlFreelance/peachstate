<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    public $timestamps = false;
    protected $dates = ['dateOfBirth', 'hireDate'];
    
    public static function getMaxApplicationId(){
        $max = static::max('applicationId');
        return (isset($max) ? $max : 0);
    }
}
