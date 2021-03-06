<?php

use Illuminate\Foundation\Inspiring;
use App\Http\Controllers\FormSiteController;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('getNewHireResults', function () {
    try{
        $FormSiteController = new FormSiteController();
        $result = $FormSiteController->getNewHireResults();
        $result = "-----$result-----";
        $this->comment($result);
    }catch(Exception $ex){
        $this->comment($ex->getMessage());
    }
})->describe('Get new Hire Application Results');

Artisan::command('retryNewHireResults', function () {
    try{
        $FormSiteController = new FormSiteController();
        $result = $FormSiteController->retryNewHireResults();
        $result = "-----$result-----";
        $this->comment($result);
    }catch(Exception $ex){
        $this->comment($ex->getMessage());
    }
})->describe('Retry new Hire Application Results');