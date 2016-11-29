<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultLog extends Model
{
    protected $table = 'results_log';
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    public static function getMaxApplicationId(){
        $max = static::max('applicationId');
        return (isset($max) ? $max : 0);
    }
}
