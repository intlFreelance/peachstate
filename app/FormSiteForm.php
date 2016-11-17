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
            throw new Exception("The formsite config variable 'base_url' must be set.");
        }
        $this->base_url = config('formsite.base_url');

        if(!config('formsite.api_key'))
        {
            throw new Exception("The formsite config variable 'api_key' must be set.");
        }
        $this->api_key = config('formsite.api_key');
    }

    public function allForms()
    {
        $response = $this->client->get($this->base_url . 'forms?fs_api_key=' . $this->api_key);
        return $this->parseResponse($response);
    }

    public function getFormResults($formName, $page)
    {
        $response = $this->client->get($this->base_url . 'forms/' . $formName . '/results?fs_api_key=' . $this->api_key . '&fs_page=' . $page . '&fs_include_headings=');
        return $this->parseResponse($response);
    }

    private function parseResponse($response)
    {
        $xml = $response->getBody()->getContents();
        $dom = new \DOMDocument();
        $dom->loadXML($xml);
        return $dom;
    }
}
