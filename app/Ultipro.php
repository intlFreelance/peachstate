<?php

namespace App;

use Exception;
use SoapVar;
use Artisaninweb\SoapWrapper\Service;

class Ultipro
{
    protected $name = 'ultipro';
    protected $trace = true;
    protected $options = [
        'soap_version'=>SOAP_1_2,
        'trace'=>true
    ];
    private $token;
    private $base_url;
    private $service;
    public function __construct(){
        $this->authenticate();
    }
    private function authenticate(){
        $service = new Service();
        if(empty(config('ultipro.login_wsdl'))){
            throw new Exception("The variable 'ultipro.login_wsdl' must be set.");
        }
        if(empty(config('ultipro.login_header'))){
             throw new Exception("The configuration 'ultipro.login_header' must be set.");
         }
         if(empty(config('ultipro.base_url'))){
             throw new Exception("The configuration 'ultipro.base_url' must be set.");
         }
        $this->base_url = config('ultipro.base_url');
        $service->wsdl(config('ultipro.login_wsdl'));
        $service->options($this->options);
        $service->createClient();
        $service->header('http://www.w3.org/2005/08/addressing','Action', $this->base_url.'/loginservice/ILoginService/Authenticate', true);
        foreach(config('ultipro.login_header') as $key => $value){
            $service->header($this->base_url.'/loginservice',$key, $value);
        }
        $response = $service->call('Authenticate',[]);
        if($response->Status != "Ok"){
            throw new Exception($response->StatusMessage);
        }
        $this->token = $response->Token;
    }
    public function sendResult(){
        ini_set('max_execution_time', 600);
        $service = new Service();
        if(empty(config('ultipro.newHire_wsdl'))){
            throw new Exception("The variable 'ultipro.newHire_wsdl' must be set.");
        }
        $login_header = config('ultipro.login_header');
        $service->header = [];
        $service->wsdl(config('ultipro.newHire_wsdl'));
        $service->options($this->options);
        $service->createClient();
        $service->header('http://www.w3.org/2005/08/addressing','Action', $this->base_url.'/employeenewhire/IEmployeeNewHire/NewHireUsa', true);
        $service->header('http://www.ultimatesoftware.com/foundation/authentication/ultiprotoken', 'UltiProToken', $this->token);
        $service->header('http://www.ultimatesoftware.com/foundation/authentication/clientaccesskey','ClientAccessKey',$login_header["ClientAccessKey"]);
        echo var_dump($service->getFunctions());
        echo "<br><br>";
        
        $newHireXML = '<NewHireUsa xmlns="http://www.ultipro.com/services/employeenewhire">
            <entities xmlns:b="http://www.ultipro.com/contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                <b:Employee>
                    <b:AddressLine1>123 Maple Ln.</b:AddressLine1>
                    <b:AddressLine2/>
                    <b:AlternateEmailAddress/>
                </b:Employee>
            </entities>
        </NewHireUsa>';

        $e = new SoapVar($newHireXML, XSD_ANYXML);
        
        $response = $service->call('NewHireUsa',[$e]);
        echo htmlentities($service->getLastRequest());
        echo "<br><br>";
        dd($response);
        //return $response;
    }
}