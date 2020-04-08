<?php

namespace evandroaugusto\MailChimp\Resources;

use evandroaugusto\MailChimp\MailChimp;

class MailChimpLists
{
    const endpoint = "lists";

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
     * Return all campaigns
     */
    public function getAll($params = [])
    {
        $apiUrl = $this->apiUrl;
                
        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Get a specific campaign
     */
    public function get($listId, $params = [])
    {
        if (!isset($listId)) {
            throw new \Exception('Missing list id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $listId;

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Create a list
     */
    public function create($params)
    {
        $params = $this->validateListParameters($params);
        $apiUrl = $this->apiUrl;

        return $this->master->call('POST', $apiUrl, $params);
    }

    /**
     * Edit a campaign
     */
    public function update($listId, $params)
    {
        if (!isset($listId)) {
            throw new \Exception('Missing list id', 1);
        }

        //$params = $this->validateListParameters($params);
        $apiUrl = $this->apiUrl . '/' . $listId;

        return $this->master->call('PATCH', $apiUrl, $params);
    }

    /**
     * Delete a list
     */
    public function delete($listId)
    {
        if (!isset($listId)) {
            throw new \Exception('Missing list id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $listId;

        return $this->master->call('DELETE', $apiUrl);
    }

    /**
     * Load all members from list
     * @return [type] [description]
     */
    public function getMembers($listId, $params=[])
    {
        if (!isset($listId)) {
            throw new \Exception('Missing list id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $listId . '/members';

        return $this->master->call('GET', $apiUrl, $params);
    }

    /**
     * Batch subscribe users
     */
    public function batchSubscribe($listId, $params=[])
    {
        if (!isset($listId)) {
            throw new \Exception('Missing list id', 1);
        }

        $params = $this->validateBatchSubscribeParameters($params);
        $apiUrl = $this->apiUrl . '/' . $listId;

        return $this->master->call('POST', $apiUrl, $params);
    }

    /**
     * Subscribe a member
     */
    public function subscribe($listId, $params=[])
    {
        if (!isset($listId)) {
            throw new \Exception('Missing list id', 1);
        }

        $params = $this->validateSubscribe($params);
        $apiUrl = $this->apiUrl . '/' . $listId .  '/members';

        return $this->master->call('POST', $apiUrl, $params);
    }

    /**
     * Delete a member
     */
    public function deleteMember($listId, $memberId, $params=[])
    {
        if (!isset($listId)) {
            throw new \Exception('Missing list id', 1);
        }

        if (!isset($memberId)) {
            throw new \Exception('Missing member id', 1);
        }

        $apiUrl = $this->apiUrl . '/' . $listId . '/' . $memberId;

        return $this->master->call('DELETE', $apiUrl);
    }

    //
    // VALIDATIONS
    //

    /**
     * Validate create parameters
     */
    protected function validateListParameters($params)
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
            $params['permission_reminder'] = 'Você está recebendo este email por fazer parte da nossa base de contatos.';
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

        if (!isset($params['double_optin'])) {
            $params['double_optin'] = false;
        }

        // check for errors
        if (!empty($errors)) {
            throw new \Exception('Missing fields parameters: ' . implode(', ', $errors), 1);
        }

        return $params;
    }

    /**
     * Validate subscribe parameters
     */
    protected function validateBatchSubscribeParameters($params)
    {
        $errors = [];

        if (!isset($params['members'])) {
            //$errors[] = 'Missing members';
            $params = [
                                'members' => $params
                        ];
        }

        if (!isset($params['members'])) {
            $errors[] = 'Missing members';
        }

        if (!isset($params['update_existing'])) {
            $params['update_existing'] = true;
        }

        if (is_array($params['members'])) {
            foreach ($params['members'] as &$member) {
                if (!isset($member['status'])) {
                    $member['status'] = 'subscribed';
                }
            }
        }

        // check for errors
        if (!empty($errors)) {
            throw new \Exception('Missing fields parameters: ' . implode(', ', $errors), 1);
        }

        return $params;
    }

    /**
     * Validate subscribe user
     */
    protected function validateSubscribe($params)
    {
        $errors = [];

        if (!isset($params['email_address'])) {
            $errors[] = 'email_address';
        }

        if (!isset($params['status'])) {
            $params['status'] = 'subscribed';
        }

        // check for errors
        if (!empty($errors)) {
            throw new \Exception('Missing fields parameters: ' . implode(', ', $errors), 1);
        }

        return $params;
    }
}
