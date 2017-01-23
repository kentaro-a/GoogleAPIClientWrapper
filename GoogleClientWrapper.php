<?php
/*
    GoogleClientWrapper Class.
     - Overview: Using GoogleApiClient to handle any GoogleApis.
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

class GoogleClientWrapper extends Google_Client{

    // Set your values to these constants.
    const AUTH_DIR = "path/to/authfiles_dir/";
    const REDIRECT_URL_TOP = "http://yourservice.com/index.php";
    const REDIRECT_URL_CALLBACK = "http://yourservice.com/oauth2callback.php";
    const CLIENT_SECRET = "path/to/client_secrets.json";
    const ACCESS_TYPE = "offline";
    const APPROVAL_PROMPT = "force";
    public $usr_id;

    /*
        Constructor
    */
    public function __construct($usr_id) {
        parent::__construct();
        parent::setAuthConfig(self::CLIENT_SECRET);
        parent::addScope(Google_Service_Analytics::ANALYTICS_READONLY);
        parent::setAccessType(self::ACCESS_TYPE);
        parent::setApprovalPrompt(self::APPROVAL_PROMPT);
        $this->usr_id = $usr_id;
    }


    /*
        Get auth info from saved file identifired by usr_id.
    */
    public function getAuthInfo() {
        $authfile = @file_get_contents(self::AUTH_DIR .$this->usr_id);
        $auth = [];
        if ($authfile) {
        	$auth = json_decode($authfile, true);
        }
        return $auth;

    }


    /*
        Save an accessToken to file.
    */
    public function saveAuthfile() {
        // Save accesstoken to file.
        file_put_contents(self::AUTH_DIR .$this->usr_id, json_encode($this->getAccessToken()));
    }


    /*
        Refresh and save an accessToken to file with refreshToken.
    */
    public function refleshAuth() {
        if(parent::isAccessTokenExpired()) {
            //リフレッシュトークンを使ってアクセストークンの取り直し
            parent::refreshToken(parent::getAccessToken()["refresh_token"]);
            $this->saveAuthfile($this->usr_id);
        }
    }


    /*
        Authenticate with code.
    */
    public function authByCode($callback_code) {
        parent::authenticate($callback_code);
        $this->saveAuthfile($this->usr_id);
    	$this->redirectTop();
    }


    /*
        Redirect to callback url.
    */
    public function authRequest() {
    	header('Location: ' . filter_var(parent::createAuthUrl(), FILTER_SANITIZE_URL));
    }

    /*
        Redirect to callback url.
    */
    public function redirectTop() {
        header('Location: ' . filter_var(self::REDIRECT_URL_TOP, FILTER_SANITIZE_URL));
    }

    /*
        Redirect to callback url.
    */
    public function redirectCallback() {
        header('Location: ' . filter_var(self::REDIRECT_URL_CALLBACK, FILTER_SANITIZE_URL));
    }

}
