<?php

namespace App;

use Exception;
use Artisaninweb\SoapWrapper\Extension\SoapService;

class Ultipro extends SoapService
{
    
    public function __construct()
    {
        if(!empty(config('ultipro.login_wsdl')))
        {
            $this->wsdl(config('ultipro.login_wsdl'))
                 ->createClient();
            return;
        }
        throw new Exception("The variable 'wsdl' must be set.");
    }
    public function authenticate(){
         if(empty(config('ultipro.login_header'))){
             throw new Exception("The configuration 'ultipro.login_header' must be set.");
         }
         
        $this->header('https://service4.ultipro.com/services/loginservice','TokenRequest_Headers',config('ultipro.login_header'), true);
        return $this->call('Authenticate',[]);
    }
    public function functions()
    {
        return $this->getFunctions();
    }
}