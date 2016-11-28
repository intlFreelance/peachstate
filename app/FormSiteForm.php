<?php

namespace App;

use Exception;
use GuzzleHttp\Client;

class FormSiteForm
{
    /**
     * Construct the Model
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->client = new Client;

        if(!config('formsite.base_url'))
        {
            throw new Exception("The formsite config variable 'formsite.base_url' must be set.");
        }
        $this->base_url = config('formsite.base_url');

        if(!config('formsite.api_key'))
        {
            throw new Exception("The formsite config variable 'formsite.api_key' must be set.");
        }
        $this->api_key = config('formsite.api_key');
        
        if(!config('formsite.results_limit'))
        {
            throw new Exception("The formsite config variable 'formsite.results_limit' must be set.");
        }
        $this->results_limit = config('formsite.results_limit');
    }

    public function allForms()
    {
        $url = $this->base_url . 'forms?fs_api_key=' . $this->api_key;
        $response = $this->client->get($url);
        return $this->parseResponse($response);
    }

    public function getFormResults($formName, $parameters=[]){
        
        $defaultParameters = [
            'fs_api_key'=>$this->api_key,
            'fs_page'=>1,
            'fs_sort'=>'result_id',
            'fs_sort_direction'=>'asc',
            'fs_include_headings'=>'',
            'fs_limit'=> $this->results_limit
        ];
        $queryParameters = array_replace($defaultParameters, $parameters);
        $url ="{$this->base_url}forms/{$formName}/results";
        $response = $this->client->request('GET', $url,['query'=>$queryParameters]);
        return $this->parseResponse($response);
    }

    private function parseResponse($response){
        $xml = $response->getBody()->getContents();
        $dom = new \DOMDocument();
        $dom->loadXML($xml);
        return $dom;
    }
}
