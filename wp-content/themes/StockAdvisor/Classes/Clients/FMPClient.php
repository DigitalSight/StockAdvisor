<?php

/**
 * Client for Retrieving FMP Stock Data
 */
class FMPClient
{
    const baseURL = 'https://financialmodelingprep.com/api/v3';

    /**
     * @var $apiKey String
     */
    private $apiKey;

    public function __construct()
    {
        $this->setApiKey();
    }

    /**
     * Retrieves ticket profile information. For specifics check docs https://financialmodelingprep.com/developer/docs/companies-key-stats-free-api
     * @param string $ticker
     * @return array|mixed|WP_Error
     */
    public function getCompanyProfile(string $ticker)
    {
        $context = '/profile/' . $ticker;
        return $this->_get($context);
    }

    /**
     * Retrieves ticket quote information. For specifics check docs https://financialmodelingprep.com/developer/docs/stock-api
     * @param string $ticker
     * @return array|mixed|WP_Error
     */
    public function getCompanyQuote(string $ticker)
    {
        $context = '/quote/' . $ticker;
        return $this->_get($context);
    }

    /**
     * Setter for apiKey property
     */
    private function setApiKey()
    {
        //Later will be replaced by getting the value from settings page
        $this->apiKey = 'b5eaa09740668daee7f791b4b06d9278';
    }

    /**
     * @param string $context
     * @return array|mixed|WP_Error
     */
    private function _get(string $context)
    {
        $requestEndpoint = add_query_arg('apikey', $this->apiKey, self::baseURL . $context);

        $response = wp_remote_get($requestEndpoint);

        if(is_wp_error($response)) {
            return $response;
        }

        $responseBody = json_decode(wp_remote_retrieve_body($response), true);

        // API is a little annoying in that if you're un-authorized it returns a 200 rather than a 401 so I have to do
        // a catch all error message because the error returned by API only includes a message
        if(isset($responseBody["Error Message"])) {
            return new WP_Error(500, $responseBody["Error Message"]);
        }
        if($code = wp_remote_retrieve_response_code($response) != 200) {
            return new WP_Error($code, "Something went wrong");
        }

        return $responseBody;
    }
}
