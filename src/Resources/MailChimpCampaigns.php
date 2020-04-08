<?php

namespace evandroaugusto\MailChimp\Resources;

use evandroaugusto\MailChimp\MailChimp;

class MailChimpCampaigns
{
    const endpoint = "campaigns";

    private $master;
    private $baseUrl;
    private $apiUrl;

    /**
     * Initialize class
     *
     * @return {void}
     */
    public function __construct(MailChimp $master)
    {
        if (!$master) {
            throw new \Exception('Missing master class');
        }

        $this->baseUrl = $master->endpoint;
        $this->apiUrl = $master->endpoint . '/' . self::endpoint;

        // set master
        $this->master = $master;
    }
        
    /**
     * Return all campaigns
     */
    public function getAll($params=[])
    {
        if (!is_array($params)) {
            $params=[];
        }

        $apiUrl = $this->apiUrl;

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get a specific campaign
     */
    public function get($campaignId, $params=[])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }
        $apiUrl = $this->apiUrl . '/' . $campaignId;

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Create a campaign
     */
    public function create($params=[])
    {
        $params = $this->validateCampaignParameters($params);
        $apiUrl = $this->apiUrl;

        return $this->master->call('POST', $apiUrl, $params);
    }

    /**
     * Create a campaign
     */
    public function update($campaignId, $params=[])
    {
        $params = $this->validateCampaignParameters($params);
        $apiUrl = $this->apiUrl . '/' . $campaignId;

        return $this->master->call('PATCH', $apiUrl, $params);
    }

    /**
     * Edit a campaign
     */
    public function edit($campaignId, $params=[])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        // default parameters
        $params = $this->validateCampaignParameters($params);
        $apiUrl = $this->apiUrl . '/' . $campaignId;

        return $this->master->call('PATCH', $apiUrl, $params);
    }

    /**
     * Delete a campaign
     */
    public function delete($campaignId, $params=[])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $campaignId;

        return $this->master->call('DELETE', $apiUrl, $params);
    }

    /**
     * Send a campaign
     */
    public function send($campaignId)
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $campaignId . '/actions/send';

        return $this->master->call('POST', $apiUrl, []);
    }

    /**
     * Return all campaigns
     */
    public function getFolders($params=[])
    {
        if (!is_array($params)) {
            $params=[];
        }

        $apiUrl = $this->baseUrl . '/campaign-folders';

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Validate create parameters
     */
    private function validateCampaignParameters($params)
    {
        $errors = [];

        // set default value
        if (!isset($params['type'])) {
            $params['type'] = 'regular';
        }

        // check for list id
        if (!isset($params['recipients'])) {
            throw new \Exception('Missing recipients', 1);
        }

        if (!isset($params['recipients']['list_id'])) {
            throw new \Exception('Missing list_id', 1);
        }

        // check send paramenters
        if (!isset($params['settings'])) {
            $params['settings'] = [];
        }

        // check send paramenters
        if (!isset($params['settings']['title'])) {
            $errors[] = 'settings:title';
        }

        if (!isset($params['settings']['subject_line'])) {
            $errors[] = 'settings:subject_line';
        }

        if (!isset($params['settings']['from_name'])) {
            $errors[] = 'settings:from_name';
        }

        if (!isset($params['settings']['reply_to'])) {
            $errors[] = 'settings:reply_to';
        }

        if (!isset($params['settings']['template_id'])) {
            $errors[] = 'settings:template_id';
        }

        // check for errors
        if (!empty($errors)) {
            throw new \Exception('Missing fields parameters: ' . implode(', ', $errors), 1);
        }

        return $params;
    }
}
