<?php

namespace evandroaugusto\MailChimp\Resources;

use evandroaugusto\MailChimp\MailChimp;

class MailChimpReports
{
    const endpoint = "reports";

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
     * Return all reports
     */
    public function getAll($params = [])
    {
        $apiUrl = $this->apiUrl;
                
        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get report from campaign
     */
    public function get($campaignId, $params = [])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $campaignId;

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get open report
     */
    public function getOpenDetails($campaignId, $params = [])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $campaignId . '/open-details';

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get click report
     */
    public function getClickDetails($campaignId, $params = [])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $campaignId . '/click-details';

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get a click report
     */
    public function getUnsubscribed($campaignId, $params = [])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $campaignId . '/unsubscribed';

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get email activity
     */
    public function getEmailActivity($campaignId, $params = [])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $campaignId . '/email-activity';

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get sent to
     */
    public function getSentTo($campaignId, $params = [])
    {
        if (!isset($campaignId)) {
            throw new \Exception('Missing campaign id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $campaignId . '/sent-to';

        return $this->master->call('GET', $apiUrl, $params);
    }
}
