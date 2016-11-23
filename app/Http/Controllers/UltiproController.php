<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ultipro;
use Exception;

class UltiproController extends Controller
{
    public function sendResult(){
        try{
            $ultipro_api = new Ultipro;
            //echo var_dump($ultipro_api->functions());

            echo var_dump($ultipro_api->authenticate());
        }  catch (Exception $ex){
            throw $ex;
        }
    }
}
