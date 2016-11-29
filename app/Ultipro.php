<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class Ultipro
{
    private $base_url;
    private $user_api_key;
    private $session_id;
    private $client;
    public function __construct(){
        $this->client = new Client();
        $this->authenticate();
    }
    private function authenticate(){
            if(empty(config('ultipro.base_url'))){
                throw new Exception("The configuration 'ultipro.base_url' must be set.");
            }
            $this->base_url = config('ultipro.base_url');
            if(empty(config('ultipro.user_api_key'))){
                throw new Exception("The configuration 'ultipro.user_api_key' must be set.");
            }
            $this->user_api_key = config('ultipro.user_api_key');
           if(empty(config('ultipro.username'))){
                throw new Exception("The configuration 'ultipro.username' must be set.");
            }
           $username = config('ultipro.username');
           if(empty(config('ultipro.password'))){
                throw new Exception("The configuration 'ultipro.password' must be set.");
            }
           $password = config('ultipro.password');
           $credentials = base64_encode("{$username}:{$password}");
           $response = $this->client->request('GET', $this->base_url, [
               'headers' => [
                   'Authorization' => "basic {$credentials}",
                   'Accept'=>'application/json',
                   'Content-Type'=>'application/json',
                   'US-Customer-Api-Key'=> $this->user_api_key
               ]
           ]);
        $headers = $response->getHeaders();
        $this->session_id = $headers["Us-Sessionid"][0];
    }
    public function sendResult($newHire){
        try{
            $data = ["Id"=>0, "EmployeeHireData"=>$newHire];
            $response = $this->client->request("POST", $this->base_url,[
                'headers' => [
                    'US-SessionId'=>$this->session_id,
                    'US-Customer-Api-Key'=> $this->user_api_key,
                    'Content-Type'=>'application/json',
                ],
                'json'=>$data
            ]);
        }catch(Exception $e){
            $response = $e->getResponse();
        }
        return $response;
    }
}