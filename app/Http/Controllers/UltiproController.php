<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ultipro;
use Exception;

class UltiproController extends Controller
{
    public function sendResult(){
        try{
            $ultipro_api = new Ultipro();
            $ultipro_api->sendResult();
        }  catch (Exception $ex){
            throw $ex;
        }
    }
}
