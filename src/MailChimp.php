<?php

namespace evandroaugusto\MailChimp;

use evandroaugusto\MailChimp\Resources;
use evandroaugusto\HttpClient\HttpClient;


class MailChimp
{
    public $endpoint = 'https://us1.api.mailchimp.com/3.0';
    public $apiKey;
    private $client;

    // APIs
    public $campaigns;
    public $lists;
    public $reports;

    /**
     * Initialize class
     * 
     * @return {void}
     */
    public function __construct($apiKey, $client = false)
    {
        $this->apiKey = $apiKey;
        $this->detectEndpoint($this->apiKey);

        $this->setClient($client);

        // set subapps
        $this->campaigns = new Resources\MailChimpCampaigns($this);
        $this->lists = new Resources\MailChimpLists($this);
        $this->templates = new Resources\MailChimpTemplates($this);
        $this->reports = new Resources\MailChimpReports($this);
    }

    /**
     * Execute API
     * @return [type] [description]
     */
    public function call($verb, $url, $params = [])
    {
        // prepare auth variables
        $auth = base64_encode('user:' . $this->apiKey);
        $httpHeader = array('Content-Type: application/json', 'Authorization: Basic '. $auth);

        $options = [];
        $verb = strtoupper($verb);

        // prepare request
        switch ($verb) {
            case 'GET':
                $request = $this->client->makeRequest(
                    $verb,
                    $url,
                    ['header' => $httpHeader],
                    $params
                );
                break;

            case 'PUT':
            case 'PATCH':
            case 'POST':
                if ($params) {
                    $params = json_encode($params);
                }
                $request = $this->client->makeRequest(
                    $verb,
                    $url,
                    ['header' => $httpHeader],
                    $params
                );
                break;

            case 'DELETE':
                if ($params) {
                    $params = json_encode($params);
                }

                $request = $this->client->makeRequest(
                    $verb,
                    $url,
                    ['header' => $httpHeader],
                    $params
                );
                break;
        }

        return $request;
    }

    /**
     * Get endpoint account
     *
     * @param  {string} $apikey
     * @return {void}
     */
    protected function detectEndpoint($apiKey)
    {
        if (! strstr($apiKey, '-')) {
            throw new \InvalidArgumentException('There seems to be an issue with your apikey. Please consult Mailchimp');
        }

        list(, $dc) = explode('-', $apiKey);
        $this->endpoint = str_replace('us1', $dc, $this->endpoint);
    }

    /**
     * Convert an email address into a 'subscriber hash' for identifying the subscriber in a method URL
     *
     * @param   string $email The subscriber's email address
     *
     * @return  string          Hashed version of the input
     */
    protected function subscriberHash($email)
    {
        return md5(strtolower($email));
    }


    //
    // GETTERS/SETTERS
    //

    /**
     * Set http client
     * @param {class} $client
     */
    protected function setClient($client)
    {
        if ($client) {
            $this->client = $client;
        } else {
            $this->client = new HttpClient();
        }
    }

    /**
     * Getters and Setters
     */
    protected function setApiKey($apiKey)
    {
        $this->detectEndpoint($apiKey);
        $this->apiKey = $apiKey;
    }
}
