<?php

namespace evandroaugusto\MailChimp\Resources;

use evandroaugusto\MailChimp\MailChimp;

class MailChimpTemplates
{
    const endpoint = "templates";

    private $master;
    private $baseUrl;
    private $apiUrl;

    /**
     * Initialize class
     * @return {void}
     */
    public function __construct(MailChimp $master)
    {
        if (!$master) {
            throw new \Exception('Missing master class', 1);
        }

        $this->baseUrl = $master->endpoint;
        $this->apiUrl = $master->endpoint . '/' . self::endpoint;

        // set master
        $this->master = $master;
    }

    //
    // PUBLIC METHODS
    //
        
    /**
     * Return all templates
     */
    public function getAll($params = [])
    {
        if (!is_array($params)) {
            $params=[];
        }

        $apiUrl = $this->apiUrl;

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get a specific template
     */
    public function get($templateId, $params = [])
    {
        if (!isset($templateId)) {
            throw new \Exception('Missing template id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $templateId;

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Create a template
     */
    public function create($params)
    {
        $params = $this->validateTemplateParameters($params);
        $apiUrl = $this->apiUrl;

        return $this->master->call('POST', $apiUrl, $params);
    }

    /**
     * Edit a template
     */
    public function edit($templateId, $params = [])
    {
        if (!isset($templateId)) {
            throw new \Exception('Missing template id', 1);
        }

        $params = $this->validateTemplateParameters($params);
        $apiUrl = $this->apiUrl . '/' . $templateId;

        return $this->master->call('PATCH', $apiUrl, $params);
    }

    /**
     * Delete a template
     */
    public function delete($templateId)
    {
        if (!isset($templateId)) {
            throw new \Exception('Missing template id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $templateId;

        return $this->master->call('PATCH', $apiUrl);
    }


    //
    // VALIDATIONS
    //

    /**
     * Validate create parameters
     */
    protected function validateTemplateParameters($params)
    {
        $errors = [];

        if (!isset($params['name'])) {
            $errors[] = 'name';
        }

        if (!isset($params['contact'])) {
            $params['contact'] = [];
        }

        if (!isset($params['contact']['company'])) {
            $errors[] = 'contact:company';
        }

        if (!isset($params['contact']['address1'])) {
            $errors[] = 'contact:address1';
        }

        if (!isset($params['contact']['city'])) {
            $errors[] = 'contact:city';
        }

        if (!isset($params['contact']['state'])) {
            $errors[] = 'contact:state';
        }

        if (!isset($params['contact']['zip'])) {
            $errors[] = 'contact:zip';
        }

        if (!isset($params['contact']['country'])) {
            $errors[] = 'contact:country';
        }

        if (!isset($params['permission_reminder'])) {
            $params['permission_reminder'] = true;
        }

        if (!isset($params['campaign_defaults'])) {
            $params['campaign_defaults'] = [];
        }

        if (!isset($params['campaign_defaults']['from_name'])) {
            $errors[] = 'campaign_defaults:from_name';
        }

        if (!isset($params['campaign_defaults']['from_email'])) {
            $errors[] = 'campaign_defaults:from_email';
        }

        if (!isset($params['campaign_defaults']['subject'])) {
            $errors[] = 'campaign_defaults:subject';
        }

        if (!isset($params['campaign_defaults']['language'])) {
            $params['campaign_defaults']['language'] = 'pt';
        }

        if (!isset($params['email_type_option'])) {
            $params['email_type_option'] = false;
        }

        // check for errors
        if (!empty($errors)) {
            throw new \Exception('Missing fields parameters: ' . implode(', ', $errors), 1);
        }
    }
}
