<?php

namespace App;

use Exception;
use Artisaninweb\SoapWrapper\Extension\SoapService;

class Ultipro extends SoapService
{
    protected $name = 'ultipro';
    protected $trace = true;
    protected $options = [
        'soap_version'=>SOAP_1_2
    ];
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
        $this->header('http://www.w3.org/2005/08/addressing','Action', 'http://www.ultipro.com/services/loginservice/ILoginService/Authenticate', true);
        $this->header('http://www.ultipro.com/services/loginservice','TokenRequest', ["Headers"=>config('ultipro.login_header')]);
        //return $this->client->__getTypes();
         $response = $this->call('Authenticate',[]);
        return [
            'response' => $response,
            'headers'=> $this->client->__getLastRequestHeaders(),
            'request'=>$this->client->__getLastRequest() 
        ];
    }
    public function functions()
    {
        return $this->getFunctions();
    }
}