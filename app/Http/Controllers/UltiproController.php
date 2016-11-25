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
            $newHire = [
                "AddressLine1"=>"4059 W 159th ST",
                "AddressLine2"=>"",
            ];
            $ultipro_api->sendResult($newHire);
        }  catch (Exception $ex){
            throw $ex;
        }
    }
}
