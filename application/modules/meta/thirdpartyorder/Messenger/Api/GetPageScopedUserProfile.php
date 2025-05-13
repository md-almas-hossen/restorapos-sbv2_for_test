<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\MessengerApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class GetPageScopedUserProfile extends MessengerApi
{
    /**
     * @var int
     */
    private $psid;

    /**
     * @param integer $psid
     * @return void
     */
    public function setPsId(int $psid)
    {
        $this->psid = $psid;
    }

    /**
     * Match fetched profile with existing customer
     *
     * @param array $profile
     * @return array
     */
    private function matchProfileWithRegisteredCustomer(array $profile): array
    {
        // get user email if exists
        // if not, build facebook email with profile id
        $profile['email'] = static::getProp($profile, ['email'], sprintf('%d@faceebook.com', $profile['id']));
        $profile['customer_phone'] = static::getProp($profile, ['phone'], '');
        $profile['customer_address'] = static::getProp($profile, ['address'], '');

        // check if existing customer
        $customerInfo = $this->CI->db
            ->select('customer_address, customer_phone')
            ->from('customer_info')
            ->where('customer_email', $profile['email'])
            ->get()
            ->row();

        if (!empty($customerInfo)) {
            // customer exists
            $profile['customer_phone'] = $customerInfo->customer_phone;
            $profile['customer_address'] = $customerInfo->customer_address;
        }

        return $profile;
    }

    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return null|array
     */
    public function init()
    {
        try {
            $response = $this->client->request('GET', $this->getApiEndpoint('/' . $this->psid, ['fields' => 'name,email']));
        } catch (\Throwable $e) {
            throw new ThirdPartyRequestException($e);
        }

        $profile = json_decode($response->getBody()->getContents(), true);
        return $this->matchProfileWithRegisteredCustomer($profile);
    }
}
