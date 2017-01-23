<?php
/*
    GoogleAnalytics Class.
     - Overview: Using GoogleAnalytics.
     - author: kentaro-a
     - created: 2017-01-23
     - Env-requirements:
        - php70 or later
        - composer
            ex) $ curl -sS https://getcomposer.org/installer | php
                $ mv composer.phar /usr/local/bin/composer
        - apiclient
            ex) $ composer require google/apiclient:^2.0

*/

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

class GoogleAnalytics {

    public $client;
    public $analytics;

    /*
        Constructor
    */
    public function __construct($client) {
        $this->client = $client;
    	$this->analytics = new Google_Service_Analytics($this->client);
    }


    /*
        Custom reports.
    */
    public function getCustomReport($params) {
        $ret = [];
        $requires = [
            "view_id",
            "start_date",
            "end_date",
            "metrics",
        ];
        foreach ($requires as $r) {
            if (!isset($params[$r])) return $ret;
        }

        try {
            $ids = "ga:" .$params["view_id"];
            $optParams = ["max-results" => 1000];
            if (isset($params["dimensions"])) $optParams["dimensions"] = $params["dimensions"];
            if (isset($params["sort"])) $optParams["sort"] = $params["sort"];

            $ret = $this->analytics->data_ga->get($ids, $params["start_date"], $params["end_date"], $params["metrics"], $optParams);
            return $ret["rows"];

        } catch (Google_Exception $e) {
            // TODO log.
            // echo $e->getMessage();
            return $ret;
        }

    }


}
