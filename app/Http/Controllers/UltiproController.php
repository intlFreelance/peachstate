<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ultipro;
use App\Applicant;
use Exception;

class UltiproController extends Controller
{
    public function sendResult(){
        $ultipro = new Ultipro();
        $applicant = Applicant::find(5705);
        $ultiproArray = $applicant->toUltiproArray();
        //$ultipro->sendResult($ultiproArray);
    }
}
