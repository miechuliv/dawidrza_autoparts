<?php

/**
 * PHP library for interacting with Google Content API for Shopping.
 *
 * Copyright 2013 Google, Inc
 *
 *   Licensed under the Apache License, Version 2.0 (the "License"); you may not
 *   use this file except in compliance with the License.  You may obtain a copy
 *   of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 *   WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
 *   License for the specific language governing permissions and limitations
 *   under the License.
 *
 * @author afshar@google.com
 * @author dhermes@google.com
 * @author jbd@google.com
 * @author mscales@google.com
 * @package GShoppingContent
 * @version 1.6
 * @example examples/GetProduct.php Getting a product
 * @example examples/GetProducts.php Getting a list of products
 * @example examples/InsertProduct.php Inserting a product
 * @example examples/UpdateProduct.php Updating a product
 * @example examples/DeleteProduct.php Deleting a product
 * @example examples/UseDryRun.php Using the dry-run flag
 * @example examples/UseWarnings.php Using the warnings flag
 * @example examples/InsertBatchProduct.php Making a batch insert request
 * @example examples/UpdateBatchProduct.php Making a batch update request
 * @example examples/DeleteBatchProduct.php Making a batch delete request
 * @example examples/GetAccount.php Getting a subaccount
 * @example examples/GetAccounts.php Getting a list of subaccounts
 * @example examples/InsertAccount.php Inserting a subaccount
 * @example examples/UpdateAccount.php Updating a subaccount
 * @example examples/DeleteAccount.php Deleting a subaccount
 * @example examples/GetDatafeed.php Getting a datafeed
 * @example examples/GetDatafeeds.php Getting a list of datafeed
 * @example examples/InsertDatafeed.php Inserting a datafeed
 * @example examples/UpdateDatafeed.php Updating a datafeed
 * @example examples/DeleteDatafeed.php Deleting a datafeed
 **/


/**
 * URI for ClientLogin requests.
 *
 * @global string the URI for client login crap
 * @name CLIENTLOGIN_URI
 * @package GShoppingContent
 **/
const CLIENTLOGIN_URI = 'https://www.google.com/accounts/ClientLogin';

/**
 * Service name for ClientLogin.
 **/
const CLIENTLOGIN_SVC = 'structuredcontent';

/**
 * Auth scope for authorizing against the Content API for Shopping.
 **/
const OAUTH_SCOPE = 'https://www.googleapis.com/auth/structuredcontent';

/**
 * User Agent string for all requests.
 **/
const USER_AGENT = 'scapi-php';

/**
 * Base API URI.
 **/
const BASE = 'https://content.googleapis.com/content/v1/';

/**
 * Google's endpoint for OAuth 2.0 authentication.
 **/
const AUTH_URI = 'https://accounts.google.com/o/oauth2/auth';

/**
 * Google's endpoint for exchanging OAuth 2.0 tokens
 **/
const TOKEN_URI = 'https://accounts.google.com/o/oauth2/token';

/**
 * Google's endpoint for revoking OAuth 2.0 tokens
 **/
const REVOKE_URI = 'https://accounts.google.com/o/oauth2/revoke';



/**
 * HTTP Response
 *
 * Wraps the CURL response and information data of the response.
 *
 * @package GShoppingContent
 **/
class _GSC_Response
{

    /**
     * HTTP response body.
     *
     * @var string
     **/
    public $body;

    /**
     * HTTP response code.
     *
     * @var int
     **/
    public $code;

    /**
     * Http response content type.
     *
     * @var string
     **/
    public $content_type;

    /**
     * Create a new _GSC_Response instance.
     *
     * @param array $info The info result from CURL after making a request.
     * @param string $body The response body.
     **/
    function __construct($info, $body)
    {
        $this->code = $info['http_code'];
        $this->content_type = $info['content_type'];
        $this->body = $body;
    }

}


/**
 * HTTP client
 *
 * A thin wrapper around CURL to ease the repetitive tasks such as adding
 * Authorization headers.
 *
 * This class is entirely static, and all functions are designed to be used
 * statically. It maintains no state.
 *
 * @package GShoppingContent
 **/
class _GSC_Http
{
    /**
     * Make an unsigned HTTP GET request.
     *
     * @param string $uri The URI to request.
     * @return _GSC_Response The response to the request.
     **/
    public static function unsignedGet($uri) {
        $ch = self::ch();
        curl_setopt($ch, CURLOPT_URL, $uri);
        return _GSC_Http::req($ch);
    }

    /**
     * Make an HTTP GET request with a Google Authorization header.
     *
     * @param string $uri The URI to request.
     * @param _GSC_Token $token The authorization token.
     * @return _GSC_Response The response to the request.
     **/
    public static function get($uri, $token) {
        $ch = self::ch();
        curl_setopt($ch, CURLOPT_URL, $uri);
        return $token->makeAuthenticatedRequest($ch);
    }

    /**
     * Post fields as an HTTP form.
     *
     * @param string $uri The URI to post to.
     * @param array $fields The form fields to post.
     * @param array $headers The headers. Defaults to null.
     * @return _GSC_Response The response to the request.
     **/
    public static function postForm($uri, $fields, $headers=null)
    {
        $ch = self::ch();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        if ($headers != null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        return self::req($ch);
    }

    /**
     * Make an HTTP POST request with a Google Authorization header.
     *
     * @param string $uri The URI to post to.
     * @param string $data The data to post.
     * @param _GSC_Token $token The authorization token.
     * @return _GSC_Response The response to the request.
     **/
    public static function post($uri, $data, $token) {
        $ch = self::ch();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        return $token->makeAuthenticatedRequest($ch);
    }

    /**
     * Make an HTTP PUT request with a Google Authorization header.
     *
     * @param string $uri The URI to post to.
     * @param string $data The data to post.
     * @param _GSC_Token $token The authorization token.
     * @return _GSC_Response The response to the request.
     **/
    public static function put($uri, $data, $token) {
        $ch = self::ch();
        curl_setopt($ch, CURLOPT_URL, $uri);
        // For string data, use CURLOPT_CUSTOMREQUEST instead of CURLOPT_POST
        // Can also use memory as file-like object as described in:
        // gen-x-design.com/archives/making-restful-requests-in-php/
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        return $token->makeAuthenticatedRequest($ch);
    }

    /**
     * Make an HTTP DELETE request with a Google Authorization header.
     *
     * @param string $uri The URI to post to.
     * @param _GSC_Token $token The authorization token.
     * @return _GSC_Response The response to the request.
     **/
    public static function delete($uri, $token) {
        $ch = self::ch();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $token->makeAuthenticatedRequest($ch);
    }

    /**
     * Make an HTTP request and create a response.
     *
     * @param CURL $ch The curl session.
     * @return _GSC_Response The response to the request.
     **/
    public static function req($ch) {
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return new _GSC_Response($info, $output);
    }

    /**
     * Create and initialize a CURL session.
     *
     * @return CURL The curl session.
     **/
    private static function ch() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        return $ch;
    }
}


/**
 * Base class for token objects.
 *
 * @package GShoppingContent
 **/
abstract class _GSC_Token
{
    /**
     * Returns a token string from the object.
     *
     * @return string The authorization token string to be sent with a request.
     **/
    abstract protected function getTokenString();

    /**
     * Makes an authenticated request.
     *
     * @param CURL $ch The curl session.
     * @return _GSC_Response The response to the request.
     **/
    abstract function makeAuthenticatedRequest($ch);
}

/**
 * Handles making ClientLogin requests to authenticate and authorize.
 *
 * @package GShoppingContent
 **/
class GSC_ClientLoginToken extends _GSC_Token
{
    /**
     * Token used to access user data.
     *
     * @var string
     **/
    private $token;

    /**
     * Create a new GSC_ClientLoginToken instance.
     *
     * @param string $token The string authentication token.
     **/
    function __construct($token=null)
    {
        $this->token = $token;
    }

    /**
     * Return the token for later use.
     *
     * @return string The string authentication token.
     **/
    function getToken() {
        return $this->token;
    }

    /**
     * Log in to ClientLogin.
     *
     * @static
     * @param string $email Google account email address.
     * @param string $password Google account password.
     * @param string $userAgent The user agent. Describes application.
     *                          Defaults to constant string USER_AGENT.
     * @return string The Auth token from ClientLogin.
     **/
    public static function login($email, $password, $userAgent=USER_AGENT)
    {
        $fields = array(
            'Email' => $email,
            'Passwd' => $password,
            'service' => CLIENTLOGIN_SVC,
            'source' => $userAgent,
            'accountType' => 'GOOGLE'
        );
        $resp = _GSC_Http::postForm(CLIENTLOGIN_URI, $fields);
        $tokens = array();
        foreach (explode("\n", $resp->body) as $line) {
            $line = chop($line);
            if ($line) {
                list($key, $val) = explode('=', $line, 2);
                $tokens[$key] = $val;
            }
        }
        return new GSC_ClientLoginToken($tokens['Auth']);
    }

    /**
     * Returns a token string from the object.
     *
     * @return string The authorization token string to be sent with a request.
     **/
    protected function getTokenString() {
        return 'GoogleLogin auth=' . $this->token;
    }

    /**
     * Makes an authenticated request.
     *
     * @param CURL $ch The curl session.
     * @return _GSC_Response The response to the request.
     **/
    public function makeAuthenticatedRequest($ch) {
        $headers = array(
            'Content-Type: application/atom+xml',
            'Authorization: ' . $this->getTokenString()
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        return _GSC_Http::req($ch);
    }
}


/**
 * Handles authenticating and authorizing with OAuth 2.0.
 *
 * @package GShoppingContent
 **/
class GSC_OAuth2Token extends _GSC_Token
{
    /**
     * Client ID for the application.
     *
     * @var string
     **/
    private $clientId;

    /**
     * Client secret for the application.
     *
     * @var string
     **/
    private $clientSecret;

    /**
     * User agent for request headers. Describes application.
     *
     * @var string
     **/
    private $userAgent;

    /**
     * Scope or whitespace-delimited set of scopes for authorizing against
     * various services. This defaults to the scope for the Content API for
     * Shopping.
     *
     * @var string
     **/
    public $scope;

    /**
     * Token used to access user data.
     *
     * @var string
     **/
    private $accessToken;

    /**
     * Token used to refresh access token.
     *
     * @var string
     **/
    private $refreshToken;

    /**
     * Redirect URI for after authorization occurs.
     *
     * @var string
     **/
    private $redirectUri;

    /**
     * Flag to determine if the access token is valid.
     *
     * @var boolean
     **/
    private $invalid;

    /**
     * Create a new GSC_OAuth2Token instance.
     *
     * @param string $clientId The client ID for the token.
     * @param string $clientSecret The client secret for the token.
     * @param string $redirectUri The redirect URI.
     * @param string $userAgent The user agent. Describes application.
     **/
    function __construct($clientId, $clientSecret, $redirectUri, $userAgent)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->userAgent = $userAgent;
        $this->invalid = false;
        $this->scope = OAUTH_SCOPE;
    }

    /**
     * Create a blob encapsulating the token information.
     *
     * @return string Blob containing token data.
     **/
    public function toBlob() {
        $tokenParts = array(
            $this->clientId,
            $this->clientSecret,
            $this->userAgent,
            $this->accessToken,
            $this->refreshToken,
            $this->redirectUri
        );

        return implode('|', $tokenParts);
    }

    /**
     * Create a token from a blob.
     *
     * @param string $blob Blob containing token data.
     * @return GSC_OAuth2Token Token built from blob.
     **/
    public function fromBlob($blob) {
        $tokenParts = explode('|', $blob);

        if (count($tokenParts) != 6) {
            throw new GSC_TokenError('Blob contains wrong number of parts.');
        }

        $this->clientId = $tokenParts[0] ? $tokenParts[0] : null;
        $this->clientSecret = $tokenParts[1] ? $tokenParts[1] : null;
        $this->userAgent = $tokenParts[2] ? $tokenParts[2] : null;
        $this->accessToken = $tokenParts[3] ? $tokenParts[3] : null;
        $this->refreshToken = $tokenParts[4] ? $tokenParts[4] : null;
        $this->redirectUri = $tokenParts[5] ? $tokenParts[5] : null;

        return $this;
    }

    /**
     * Extract tokens from a response body.
     *
     * @param string $body The response body to be parsed.
     * @return void
     **/
    private function extractTokens($body) {
        $bodyDict = json_decode($body, true);
        // Will throw error if access_token not returned
        $this->accessToken = $bodyDict['access_token'];
        if (array_key_exists('refresh_token', $bodyDict)) {
            $this->refreshToken = $bodyDict['refresh_token'];
        }

        // TODO (dhermes) Cover case when 'expires_in' is in $bodyDict
    }

    /**
     * Refresh the access token.
     *
     * @return _GSC_Response The response to the refresh request.
     * @throws GSC_TokenError if the response code is not 200.
     **/
    private function refresh() {
        $body = array(
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken
        );

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'user-agent: ' . $this->userAgent
        );

        $urlEncodedBody = http_build_query($body);
        $resp = _GSC_Http::postForm(TOKEN_URI, $urlEncodedBody, $headers);

        if ($resp->code == 200) {
            $this->extractTokens($resp->body);
        }
        else {
            $this->invalid = true;
            self::raiseFromJson($resp);
        }

        return $resp;
    }

    /**
     * Generate a URI to redirect to the provider.
     *
     * @param string $approvalPrompt Value that determines if user will be
     *                               prompted to give approval. Defaults to
     *                               'auto' but 'force' is also valid.
     * @param string $redirectUri Either the string 'urn:ietf:wg:oauth:2.0:oob'
     *                            for a non-web-based application, or a URI
     *                            that handles the callback from the
     *                            authorization server.
     * @param string $responseType Either the string 'code' for server-side
     *                             or native application, or the string 'token'
     *                             for client-side application.
     * @param string $accessType Either the string 'offline' to request a
     *                           refresh token or 'online'.
     * @return string The URI to redirect to.
     **/
    public function generateAuthorizeUrl(
        $redirectUri='urn:ietf:wg:oauth:2.0:oob',
        $approvalPrompt='auto',
        $responseType='code',
        $accessType='offline') {
        $this->redirectUri = $redirectUri;

        $query = array(
            'response_type' => $responseType,
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'scope' => $this->scope,
            'approval_prompt' => $approvalPrompt,
            'access_type' => $accessType
        );

        return AUTH_URI . '?' . http_build_query($query);
    }

    /**
     * Raise an error from a JSON response object.
     *
     * @param _GSC_Response $response The response to some request.
     * @throws GSC_TokenError with contents gleaned from response.
     * @return void
     **/
    private static function raiseFromJson($response) {
        $errorMsg = 'Invalid response ' .  $response->code . '.';

        $errorDict = json_decode($response->body, true);
        if ($errorDict != null) {
            if (array_key_exists('error', $errorDict)) {
                $errorMsg = $errorDict['error'];
            }
        }

        throw new GSC_TokenError($errorMsg);
    }

    /**
     * Exchanges a code for an access token.
     *
     * @param string|array $code A string or array with 'code' as a key. This
     *                           code can be exchanged for an access token.
     * @return GSC_OAuth2Token The current token (this) after access token
     *                         is retrieved and set.
     * @throws GSC_TokenError if the response code is not 200.
     **/
    public function getAccessToken($code) {
        if (!(is_string($code))) {
            $code = $code['code'];
        }

        $body = array(
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'scope' => $this->scope
        );

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'user-agent: ' . $this->userAgent
        );

        $urlEncodedBody = http_build_query($body);
        $resp = _GSC_Http::postForm(TOKEN_URI, $urlEncodedBody, $headers);

        if ($resp->code == 200) {
            $this->extractTokens($resp->body);
            return $this;
        }
        else {
            self::raiseFromJson($resp);
        }
    }

    /**
     * Revokes access via a refresh token.
     *
     * @param $refreshToken Token used to refresh access token.
     * @return void
     * @throws GSC_TokenError if the response code is not 200.
     **/
    public function revoke($refreshToken=null) {
        if ($refreshToken == null) {
            $refreshToken = $this->refreshToken;
        }

        $query = array(
            'token' => $refreshToken
        );

        $uri = REVOKE_URI . '?' . http_build_query($query);
        $resp = _GSC_Http::unsignedGet($uri);

        if ($resp->code == 200) {
            $this->invalid = true;
        }
        else {
            self::raiseFromJson($resp);
        }
    }

    /**
     * Returns a token string from the object.
     *
     * @return string The authorization token string to be sent with a request.
     **/
    protected function getTokenString() {
        return 'Bearer ' . $this->accessToken;
    }

    /**
     * Makes an authenticated request, gets new access token if fails.
     *
     * @param CURL $ch The curl session.
     * @return _GSC_Response The response to the request.
     **/
    public function makeAuthenticatedRequest($ch) {
        $headers = array(
            'Content-Type: application/atom+xml',
            'Authorization: ' . $this->getTokenString()
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Before sending the request, we copy the curl handle
        // in case we need to send the request again.
        $newCurlHandle = curl_copy_handle($ch);

        $resp = _GSC_Http::req($ch);
        if ($resp->code == 401) {
            $this->refresh();

            $newHeaders = array(
                'Content-Type: application/atom+xml',
                'Authorization: ' . $this->getTokenString()
            );
            curl_setopt($newCurlHandle, CURLOPT_HTTPHEADER, $newHeaders);
            return _GSC_Http::req($newCurlHandle);
        }
        else {
            return $resp;
        }
    }
}


/**
 * Base class for client errors.
 *
 * @package GShoppingContent
 **/
class GSC_ClientError extends Exception { }


/**
 * Base class for token errors.
 *
 * @package GShoppingContent
 **/
class GSC_TokenError extends Exception { }


/**
 * Base class for parse errors.
 *
 * @package GShoppingContent
 **/
class GSC_ParseError extends Exception { }


/**
 * Class for request errors.
 *
 * @package GShoppingContent
 **/
class GSC_RequestError extends Exception
{
    /**
     * Errors parsed from the response.
     *
     * @var GSC_Errors
     **/
    public $errors;

    /**
     * Create a new GSC_RequestError instance.
     *
     * @param GSC_Errors $errors The errors parsed from the response.
     **/
    function __construct($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Return the string value of the exception, containing the XML.
     *
     * @return string The value of the XML in the error.
     **/
    function __toString()
    {
        return $this->errors->toXML();
    }

}


/**
 * Client for making requests to the Google Content API for Shopping.
 *
 * @package GShoppingContent
 **/
class GSC_Client
{

    /**
     * Projection for the scope. Can be 'schema' (default) or 'generic'.
     *
     * @var string
     **/
    public $projection = 'schema';

    /**
     * Authorization token for the user.
     *
     * @var _GSC_Token
     **/
    private $token;

    /**
     * Create a new client for the merchant.
     *
     * @return GSC_Client The newliy created client.
     **/
    public function __construct($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * Check that this client has been authorized and has a token.
     *
     * @throws GSC_ClientError if there is no token.
     * @return void
     */
    private function checkToken() {
        if ($this->token == null) {
            throw new GSC_ClientError('Client is not authenticated.');
        }
    }

    /**
     * Log in with ClientLogin and set the auth token.
     *
     * Included for backwards compatability purposes.
     *
     * @param string $email Google account email address.
     * @param string $password Google account password.
     * @return void
     **/
    public function login($email, $password) {
        $this->token = GSC_ClientLoginToken::login($email, $password);
    }

    /**
     * Log in with ClientLogin and set the auth token.
     *
     * @param string $email Google account email address.
     * @param string $password Google account password.
     * @param string $userAgent The user agent. Describes application.
     * @return void
     **/
    public function clientLogin($email, $password, $userAgent) {
        $this->token = GSC_ClientLoginToken::login($email, $password,
                                                   $userAgent);
    }

    /**
     * Set the token on the client with an unauthenticated OAuth2 token.
     *
     * @param string $clientId The client ID for the token.
     * @param string $clientSecret The client secret for the token.
     * @param string $redirectUri The redirect URI.
     * @param string $userAgent The user agent. Describes application.
     * @return void
     **/
    public function setOAuth2Token($clientId, $clientSecret, $redirectUri, $userAgent) {
        $this->token = new GSC_OAuth2Token($clientId, $clientSecret,
                                           $redirectUri, $userAgent);
    }

    /**
     * Set the authentication token.
     *
     * @param _GSC_Token $token The authorization token.
     * @return void
     **/
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * Get all products.
     *
     * @param string $maxResults The max results desired. Defaults to null.
     * @param string $startToken The start token for the query. Defaults to null.
     * @param string $performanceStart The start date (inclusive) of click data
     *                                 returned. Should be represented as
     *                                 YYYY-MM-DD; not appended if left as None.
     * @param string $performanceEnd The end date (inclusive) of click data
     *                               returned. Should be represented as
     *                               YYYY-MM-DD; not appended if left as None.
     * @return GSC_ProductList parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getProducts($maxResults=null, $startToken=null,
                                $performanceStart=null, $performanceEnd=null) {
        $feedUri = $this->getFeedUri();

        $queryParams = array();
        if ($maxResults != null) {
            array_push($queryParams, 'max-results=' . $maxResults);
        }
        if ($startToken != null) {
            array_push($queryParams, 'start-token=' . $startToken);
        }
        if ($performanceStart != null) {
            array_push($queryParams, 'performance.start=' . $performanceStart);
        }
        if ($performanceEnd != null) {
            array_push($queryParams, 'performance.end=' . $performanceEnd);
        }

        if (count($queryParams) > 0) {
            $feedUri .= '?' . join('&', $queryParams);
        }

        $resp = _GSC_Http::get(
            $feedUri,
            $this->token
        );
        return _GSC_AtomParser::parse($resp->body);
    }

    /**
     * Get a product from a link.
     *
     * @param string $link The edit link for the product.
     * @return GSC_Product parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getFromLink($link) {
        $resp = _GSC_Http::get(
            $link,
            $this->token
          );
        return _GSC_AtomParser::parse($resp->body);
    }

    /**
     * Get a product.
     *
     * @param string $id The product id.
     * @param string $country The country specific to the product.
     * @param string $language The language specific to the product.
     * @return GSC_Product parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getProduct($id, $country, $language) {
        $link = $this->getProductUri($id, $country, $language, 'online');
        return $this->getFromLink($link);
    }

    /**
     * Insert a product.
     *
     * @param GSC_Product $product The product to insert.
     * @param boolean $warnings A boolean to determine if the warnings should be
     *                          included. Defaults to false.
     * @param boolean $dryRun A boolean to determine if the dry-run should be
     *                        included. Defaults to false.
     * @return GSC_Product parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function insertProduct($product, $warnings=false, $dryRun=false) {
        $feedUri = $this->appendQueryParams(
            $this->getFeedUri(),
            $warnings,
            $dryRun
        );

        $resp = _GSC_Http::post(
            $feedUri,
            $product->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parse($resp->body);
    }

    /**
     * Update a product.
     *
     * @param GSC_Product $product The product to update.
     *                    Must have rel='edit' set.
     * @param boolean $warnings A boolean to determine if the warnings should be
     *                          included. Defaults to false.
     * @param boolean $dryRun A boolean to determine if the dry-run should be
     *                        included. Defaults to false.
     * @return GSC_Product parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function updateProduct($product, $warnings=false, $dryRun=false) {
        $productUri = $this->appendQueryParams(
            $product->getEditLink(),
            $warnings,
            $dryRun
        );

        $resp = _GSC_Http::put(
            $productUri,
            $product->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parse($resp->body);
    }

    /**
     * Send a delete request to a link.
     *
     * @param string $link The edit link for the product.
     * @throws GSC_ClientError if the response code is not 200.
     * @return void
     */
    public function deleteFromLink($link) {
        $resp = _GSC_Http::delete(
            $link,
            $this->token
          );

        if ($resp->code != 200) {
            throw new GSC_ClientError('Delete request failed.');
        }
    }

    /**
     * Delete a product.
     *
     * @param GSC_Product $product The product to delete.
     *                    Must have rel='edit' set.
     * @param boolean $warnings A boolean to determine if the warnings should be
     *                          included. Defaults to false.
     * @param boolean $dryRun A boolean to determine if the dry-run should be
     *                        included. Defaults to false.
     * @throws GSC_ClientError if the response code is not 200.
     * @return void
     */
    public function deleteProduct($product, $warnings=false, $dryRun=false) {
        $productUri = $this->appendQueryParams(
            $product->getEditLink(),
            $warnings,
            $dryRun
        );

        $this->deleteFromLink($productUri);
    }

    /**
     * Make a batch request.
     *
     * @param GSC_ProductList $products The list of products to batch.
     * @param boolean $warnings A boolean to determine if the warnings should be
     *                          included. Defaults to false.
     * @param boolean $dryRun A boolean to determine if the dry-run should be
     *                        included. Defaults to false.
     * @return GSC_ProductList The returned results from the batch.
     **/
    public function batch($products, $warnings=false, $dryRun=false) {
        $batchUri = $this->appendQueryParams(
            $this->getBatchUri(),
            $warnings,
            $dryRun
        );

        $resp = _GSC_Http::post(
            $batchUri,
            $products->toXML(),
            $this->token
        );
        return _GSC_AtomParser::parse($resp->body);
    }

    /**
     * Create a feed object with a specified batch operation on each element.
     *
     * @param array $entries The list of entries to add in batch.
     * @param string $operation The batch operation desired.
     * @return GSC_ProductList|GSC_InventoryEntryList The constructed batch feed.
     **/
    public function _createBatchFeed($entries, $operation, $feedType='product') {
        if ($feedType == 'inventory') {
          $entriesBatch = new GSC_InventoryEntryList();
        }
        else {
          // fallback for all unknown as well as 'product'
          $entriesBatch = new GSC_ProductList();
        }

        foreach ($entries as $entry) {
            $entry->setBatchOperation($operation);
            $entriesBatch->addEntry($entry);
        }

        return $entriesBatch;
    }

    /**
     * Insert a list of products.
     *
     * @param array $products The list of products to insert in batch.
     * @param boolean $warnings A boolean to determine if the warnings should be
     *                          included. Defaults to false.
     * @param boolean $dryRun A boolean to determine if the dry-run should be
     *                        included. Defaults to false.
     * @return GSC_ProductList The returned results from the batch.
     **/
    public function insertProducts($products, $warnings=false, $dryRun=false) {
        $productsBatch = $this->_createBatchFeed($products, 'insert');
        return $this->batch($productsBatch, $warnings, $dryRun);
    }

    /**
     * Update a list of products.
     *
     * @param array $products The list of products to update in batch.
     * @param boolean $warnings A boolean to determine if the warnings should be
     *                          included. Defaults to false.
     * @param boolean $dryRun A boolean to determine if the dry-run should be
     *                        included. Defaults to false.
     * @return GSC_ProductList The returned results from the batch.
     **/
    public function updateProducts($products, $warnings=false, $dryRun=false) {
        $productsBatch = $this->_createBatchFeed($products, 'update');
        return $this->batch($productsBatch, $warnings, $dryRun);
    }

    /**
     * Delete a list of products.
     *
     * @param array $products The list of products to delete in batch.
     * @param boolean $warnings A boolean to determine if the warnings should be
     *                          included. Defaults to false.
     * @param boolean $dryRun A boolean to determine if the dry-run should be
     *                        included. Defaults to false.
     * @return GSC_ProductList The returned results from the batch.
     **/
    public function deleteProducts($products, $warnings=false, $dryRun=false) {
        $productsBatch = $this->_createBatchFeed($products, 'delete');
        return $this->batch($productsBatch, $warnings, $dryRun);
    }

    /**
     * Get all subaccounts.
     *
     * @param string $maxResults The max results desired. Defaults to null.
     * @param string $startIndex The start index for the query. Defaults to null.
     * @return GSC_ManagedAccountList parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getAccounts($maxResults=null, $startIndex=null) {
        $accountsUri = $this->getManagedAccountsUri();

        $queryParams = array();
        if ($maxResults != null) {
            array_push($queryParams, 'max-results=' . $maxResults);
        }
        if ($startIndex != null) {
            array_push($queryParams, 'start-index=' . $startIndex);
        }

        if (count($queryParams) > 0) {
            $accountsUri .= '?' . join('&', $queryParams);
        }

        $resp = _GSC_Http::get(
            $accountsUri,
            $this->token
        );
        return _GSC_AtomParser::parseManagedAccounts($resp->body);
    }

    /**
     * Get a subaccount.
     *
     * @param string $accountId The account id.
     * @return GSC_ManagedAccount parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getAccount($accountId) {
        $resp = _GSC_Http::get(
            $this->getManagedAccountsUri($accountId),
            $this->token
          );
        return _GSC_AtomParser::parseManagedAccounts($resp->body);
    }

    /**
     * Insert a subaccount.
     *
     * @param GSC_ManagedAccount $account The account to insert.
     * @return GSC_ManagedAccount The inserted account from the response.
     */
    public function insertAccount($account) {
        $resp = _GSC_Http::post(
            $this->getManagedAccountsUri(),
            $account->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parseManagedAccounts($resp->body);
    }

    /**
     * Update a subaccount.
     *
     * @param GSC_ManagedAccount $account The account to update.
     *                                    Must have rel='edit' set.
     * @return GSC_ManagedAccount parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function updateAccount($account) {
        $resp = _GSC_Http::put(
            $account->getEditLink(),
            $account->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parseManagedAccounts($resp->body);
    }

    /**
     * Delete a subaccount.
     *
     * @param GSC_ManagedAccount $account The account to delete.
     *                                    Must have rel='edit' set.
     * @throws GSC_ClientError if the response code is not 200.
     * @return void
     */
    public function deleteAccount($account) {
        $this->deleteFromLink($account->getEditLink());
    }

    /**
     * Get all datafeeds.
     *
     * @return GSC_DatafeedList parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getDatafeeds() {
        $resp = _GSC_Http::get(
            $this->getDatafeedsUri(),
            $this->token
        );
        return _GSC_AtomParser::parseDatafeeds($resp->body);
    }

    /**
     * Get a datafeed.
     *
     * @param string $datafeedId The datafeed id.
     * @return GSC_Datafeed parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getDatafeed($datafeedId) {
        $resp = _GSC_Http::get(
            $this->getDatafeedsUri($datafeedId),
            $this->token
          );
        return _GSC_AtomParser::parseDatafeeds($resp->body);
    }

    /**
     * Insert a datafeed.
     *
     * @param GSC_Datafeed $datafeed The datafeed to insert.
     * @return GSC_Datafeed The inserted datafeed from the response.
     */
    public function insertDatafeed($datafeed) {
        $resp = _GSC_Http::post(
            $this->getDatafeedsUri(),
            $datafeed->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parseDatafeeds($resp->body);
    }

    /**
     * Update a datafeed.
     *
     * @param GSC_Datafeed $datafeed The datafeed to update.
     *                               Must have rel='edit' set.
     * @return GSC_Datafeed parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function updateDatafeed($datafeed) {
        $resp = _GSC_Http::put(
            $datafeed->getEditLink(),
            $datafeed->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parseDatafeeds($resp->body);
    }

    /**
     * Delete a datafeed.
     *
     * @param GSC_Datafeed $datafeed The datafeed to delete.
     *                               Must have rel='edit' set.
     * @throws GSC_ClientError if the response code is not 200.
     * @return void
     */
    public function deleteDatafeed($datafeed) {
        $this->deleteFromLink($datafeed->getEditLink());
    }

    /**
     * Get all users.
     *
     * @return GSC_UserList parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getUsers() {
        $resp = _GSC_Http::get(
            $this->getUsersUri(),
            $this->token
        );
        return _GSC_AtomParser::parseUsers($resp->body);
    }

    /**
     * Get a user.
     *
     * @param string $userEmail The email of a selected user.
     * @return GSC_User parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getUser($userEmail) {
        $resp = _GSC_Http::get(
            $this->getUsersUri($userEmail),
            $this->token
          );
        return _GSC_AtomParser::parseUsers($resp->body);
    }

    /**
     * Insert a user.
     *
     * @param GSC_User $user The user to insert.
     * @return GSC_User The inserted user from the response.
     */
    public function insertUser($user) {
        $resp = _GSC_Http::post(
            $this->getUsersUri(),
            $user->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parseUsers($resp->body);
    }

    /**
     * Update a user.
     *
     * @param GSC_User $user The user to update.
     *                       Must have rel='edit' set.
     * @return GSC_User parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function updateUser($user) {
        $resp = _GSC_Http::put(
            $user->getEditLink(),
            $user->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parseUsers($resp->body);
    }

    /**
     * Delete a user.
     *
     * @param GSC_User $user The user to delete.
     *                       Must have rel='edit' set.
     * @throws GSC_ClientError if the response code is not 200.
     * @return void
     */
    public function deleteUser($user) {
        $this->deleteFromLink($user->getEditLink());
    }

    /**
     * Update an inventory entry.
     *
     * @param GSC_InventoryEntry $entry The inventory entry to update.
     *                                  Must have rel='edit' set.
     * @return GSC_InventoryEntry parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function updateInventoryEntry($entry) {
        $resp = _GSC_Http::put(
            $entry->getEditLink(),
            $entry->toXML(),
            $this->token
          );
        return _GSC_AtomParser::parseInventory($resp->body);
    }

    /**
     * Update a list of inventory entries.
     *
     * Each entry must have rel='edit' set. To generate edit URI's for each product, first create
     * a feed URI specific to the store:
     * $storeBase = $client->getInventoryUri($storeId);
     * then for each individual product, create an product specific URI using the base:
     * $localProductUri = $client->getProductUri($id, $country, $language, 'local', $feedUri=$storeBase)
     *
     * Once you have a URI of this form, you can set it via:
     * $entry->setEditLink($localProductUri);
     *
     * @param array $entries The list of inventory entries to update in batch.
     * @return GSC_InventoryEntryList The returned results from the batch.
     **/
    public function updateInventoryFeed($entries) {
        $entriesBatch = $this->_createBatchFeed($entries, 'update', 'inventory');

        $resp = _GSC_Http::post(
            $this->getInventoryUri(null, true),
            $entriesBatch->toXML(),
            $this->token
        );

        return _GSC_AtomParser::parseInventory($resp->body);
    }

    /**
     * Get the data quality report for an individual account.
     *
     * @param array $secondaryAccountId The (optional) ID of a subaccount.
     *                                  If not specified, the merchant ID will
     *                                  be re-used.
     * @return GSC_DataQualityEntry parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getDataQualityEntry($secondaryAccountId=null) {
        if ($secondaryAccountId == null) {
          $secondaryAccountId = $this->merchantId;
        }
        $resp = _GSC_Http::get(
            $this->getDataQualityUri($secondaryAccountId),
            $this->token
        );
        return _GSC_AtomParser::parseDataQuality($resp->body);
    }

    /**
     * Get the data quality feed.
     *
     * @param string $maxResults The max results desired. Defaults to null.
     * @param string $startIndex The start index for the query. Defaults to null.
     * @return GSC_DataQualityFeed parsed from the response.
     * @throws GSC_RequestError if the response is an errors element.
     */
    public function getDataQualityFeed($maxResults=null, $startIndex=null) {
        $dataQualityUri = $this->getDataQualityUri();

        $queryParams = array();
        if ($maxResults != null) {
            array_push($queryParams, 'max-results=' . $maxResults);
        }
        if ($startIndex != null) {
            array_push($queryParams, 'start-index=' . $startIndex);
        }

        if (count($queryParams) > 0) {
            $dataQualityUri .= '?' . join('&', $queryParams);
        }

        $resp = _GSC_Http::get(
            $dataQualityUri,
            $this->token
          );
        return _GSC_AtomParser::parseDataQuality($resp->body);
    }

    /**
     * Create a URI for the feed for this merchant.
     *
     * @return string The feed URI.
     **/
    public function getFeedUri() {
        return (BASE . $this->merchantId . '/items/products/' .
                $this->projection . '/');
    }

    /**
     * Create a URI for an individual product.
     *
     * @param string $id The product id.
     * @param string $country The country specific to the product.
     * @param string $language The language specific to the product.
     * @return string The product URI.
     **/
    public function getProductUri($id, $country, $language, $channel = 'online', $feedUri=null) {

        if ($feedUri == null) {
          $feedUri = $this->getFeedUri();
        }
        return sprintf(
            '%s%s:%s:%s:%s',
            $feedUri,
            $channel,
            $language,
            $country,
            $id
        );

    }

    /**
     * Create a URI for the batch feed for this merchant.
     *
     * @return string The batch feed URI.
     **/
    public function getBatchUri() {
        return $this->getFeedUri() . 'batch';
    }

    /**
     * Create a URI for the managed accounts feed for this merchant.
     *
     * @param string $accountId The account id. Defaults to null.
     * @return string The managedaccounts URI.
     **/
    public function getManagedAccountsUri($accountId=null) {
        $result = BASE . $this->merchantId . '/managedaccounts';
        if ($accountId != null) {
            $result .= '/' . $accountId;
        }
        return $result;
    }

    /**
     * Create a URI for the datafeeds feed for this merchant.
     *
     * @param string $accountId The account id. Defaults to null.
     * @return string The datafeeds URI.
     **/
    public function getDatafeedsUri($accountId=null) {
        $result = BASE . $this->merchantId . '/datafeeds/products';
        if ($accountId != null) {
            $result .= '/' . $accountId;
        }
        return $result;
    }

    /**
     * Create a URI for the users feed for this merchant.
     *
     * @param string $userEmail The email of a selected user. Defaults to null.
     * @return string The users URI.
     **/
    public function getUsersUri($userEmail=null) {
        $result = BASE . $this->merchantId . '/users';

        if ($userEmail != null) {
            $result .= '/' . $userEmail;
        }
        return $result;
    }

    /**
     * Create a URI for the users feed for this merchant.
     *
     * @param string $userEmail The email of a selected user. Defaults to null.
     * @return string The users URI.
     **/
    public function getInventoryUri($storeCode=null, $batch=false) {
        $result = BASE . $this->merchantId . '/inventory';

        if ($storeCode != null) {
            $result .= '/' . $storeCode . '/items/';
        }
        else if ($batch) {
            $result .= '/batch';
        }
        return $result;
    }

    /**
     * Create a URI for the data quality feed for this merchant.
     *
     * @param string $secondaryAccountId The (optional) ID of a subaccount.
     * @return string The data quality URI.
     **/
    public function getDataQualityUri($secondaryAccountId=null) {
        $result = BASE . $this->merchantId . '/dataquality';

        if ($secondaryAccountId != null) {
            $result .= '/' . $secondaryAccountId;
        }
        return $result;
    }

    /**
     * Build a URI with warnings and dry-run query parameters.
     *
     * @param string $uri The URI to have parameters appended to.
     * @param boolean $warnings A boolean to determine if the warnings should be
     *                          included. Defaults to false.
     * @param boolean $dryRun A boolean to determine if the dry-run should be
     *                        included. Defaults to false.
     * @return string The URI with parameters included
     **/
    public function appendQueryParams($uri, $warnings=false, $dryRun=false) {
        $queryParams = array();
        if ($warnings) {
            array_push($queryParams, 'warnings');
        }
        if ($dryRun) {
            array_push($queryParams, 'dry-run');
        }

        if (count($queryParams) > 0) {
            $uri .= '?' . join('&', $queryParams);
        }

        return $uri;
    }
}


/**
 * Namespaces used by GSC
 *
 * @package GShoppingContent
**/
class _GSC_Ns {
    /**
     * Atom namespace.
     **/
    const atom = 'http://www.w3.org/2005/Atom';

    /**
     * Atom Publishing Protocol namespace.
     **/
    const app = 'http://www.w3.org/2007/app';

    /**
     * OpenSearch namespace.
     **/
    const openSearch = 'http://a9.com/-/spec/opensearch/1.1/';

    /**
     * Google Data namespace.
     **/
    const gd = 'http://schemas.google.com/g/2005';

    /**
     * GData Batch namespace.
     **/
    const batch = 'http://schemas.google.com/gdata/batch';

    /**
     * Structured Content namespace.
     **/
    const sc = 'http://schemas.google.com/structuredcontent/2009';

    /**
     * Structured Content Products namespace.
     **/
    const scp = 'http://schemas.google.com/structuredcontent/2009/products';
}


/**
 * Tags used by GSC.
 *
 * Each tag is available as an array of two elements, the namespace and the tag
 * name'
 *
 * @package GShoppingContent
**/
class _GSC_Tags {
    /**
     * The <batch:id> tag.
     *
     * @var array
     * @see _GSC_AtomElement::setBatchId(), _GSC_AtomElement::getBatchId()
     **/
    public static $batchId = array(_GSC_Ns::batch, 'id');

    /**
     * The <batch:interrupted> tag.
     *
     * @var array
     * @see _GSC_AtomElement::getBatchInterruptedAttribute()
     **/
    public static $interrupted = array(_GSC_Ns::batch, 'interrupted');

    /**
     * The <batch:operation> tag.
     *
     * @var array
     * @see _GSC_AtomElement::setBatchOperation(), _GSC_AtomElement::getBatchOperation()
     **/
    public static $operation = array(_GSC_Ns::batch, 'operation');

    /**
     * The <batch:status> tag.
     *
     * @var array
     * @see _GSC_AtomElement::getBatchStatus(), _GSC_AtomElement::getBatchStatusReason()
     **/
    public static $status = array(_GSC_Ns::batch, 'status');

    /**
     * The <atom:entry> tag.
     *
     * @var array
     * @see GSC_Product::createModel(), _GSC_AtomParser::parse()
     **/
    public static $entry = array(_GSC_Ns::atom, 'entry');

    /**
     * The <atom:title> tag.
     *
     * @var array
     * @see _GSC_AtomElement::setTitle(), _GSC_AtomElement::getTitle()
     **/
    public static $title = array(_GSC_Ns::atom, 'title');

    /**
     * The <atom:id> tag.
     *
     * @var array
     * @see _GSC_AtomElement::getAtomId(), _GSC_AtomElement::setAtomId()
     **/
    public static $atomId = array(_GSC_Ns::atom, 'id');

    /**
     * The <atom:content> tag.
     *
     * @var array
     * @see _GSC_AtomElement::setDescription(),
     *      _GSC_AtomElement::getDescription()
     **/
    public static $content = array(_GSC_Ns::atom, 'content');

    /**
     * <atom:link> element
     *
     * @var array
     * @see GSC_Product::setProductLink(), GSC_Product::getProductLink()
     **/
    public static $link = array(_GSC_Ns::atom, 'link');

    /**
     * <atom:published> element
     *
     * @var array
     * @see _GSC_AtomElement::getPublished()
     **/
    public static $published = array(_GSC_Ns::atom, 'published');

    /**
     * <atom:updated> element
     *
     * @var array
     * @see _GSC_AtomElement::getUpdated()
     **/
    public static $updated = array(_GSC_Ns::atom, 'updated');

    /**
     * <atom:author> element
     *
     * @var array
     * @see _GSC_AtomElement::getAtomAuthor()
     **/
    public static $atomAuthor = array(_GSC_Ns::atom, 'author');

    /**
     * <atom:name> element
     *
     * @var array
     * @see _GSC_AtomElement::getAuthorName()
     **/
    public static $name = array(_GSC_Ns::atom, 'name');

    /**
     * <atom:email> element
     *
     * @var array
     * @see _GSC_AtomElement::getAuthorEmail()
     **/
    public static $email = array(_GSC_Ns::atom, 'email');

    /**
     * <gd:errors> element
     *
     * @var array
     * @see GSC_Errors
     **/
    public static $errors = array(_GSC_Ns::gd, 'errors');

    /**
     * <gd:error> element
     *
     * @var array
     * @see GSC_Errors::getErrors()
     **/
    public static $error = array(_GSC_Ns::gd, 'error');

    /**
     * <gd:domain> element
     *
     * @var array
     * @see GSC_ErrorElement::getDomain()
     **/
    public static $domain = array(_GSC_Ns::gd, 'domain');

    /**
     * <gd:code> element
     *
     * @var array
     * @see GSC_ErrorElement::getCode()
     **/
    public static $code = array(_GSC_Ns::gd, 'code');

    /**
     * <gd:location> element
     *
     * @var array
     * @see GSC_ErrorElement::getLocation(), GSC_ErrorElement::getLocationType()
     **/
    public static $location = array(_GSC_Ns::gd, 'location');

    /**
     * <gd:internalReason> element
     *
     * @var array
     * @see GSC_ErrorElement::getInternalReason()
     **/
    public static $internalReason = array(_GSC_Ns::gd, 'internalReason');

    /**
     * <gd:debugInfo> element
     *
     * @var array
     * @see GSC_ErrorElement::getDebugInfo()
     **/
    public static $debugInfo = array(_GSC_Ns::gd, 'debugInfo');

    /**
     * <gd:etag> element
     *
     * @var array
     **/
    public static $etag = array(_GSC_Ns::gd, 'etag');

    /**
     * <gd:kind> element
     *
     * @var array
     **/
    public static $kind = array(_GSC_Ns::gd, 'kind');

    /**
     * <gd:fields> element
     *
     * @var array
     **/
    public static $fields = array(_GSC_Ns::gd, 'fields');

    /**
     * <openSearch:startIndex> element
     *
     * @var array
     * @see _GSC_AtomElement::getStartIndex()
     **/
    public static $startIndex = array(_GSC_Ns::openSearch, 'startIndex');

    /**
     * <openSearch:totalResults> element
     *
     * @var array
     * @see _GSC_AtomElement::getTotalResults()
     **/
    public static $totalResults = array(_GSC_Ns::openSearch, 'totalResults');

    /**
     * <openSearch:itemsPerPage> element
     *
     * @var array
     * @see _GSC_AtomElement::getItemsPerPage()
     **/
    public static $itemsPerPage = array(_GSC_Ns::openSearch, 'itemsPerPage');

    /**
     * <app:edited> element
     *
     * @var array
     * @see _GSC_AtomElement::getEdited()
     **/
    public static $edited = array(_GSC_Ns::app, 'edited');

    /**
     * <app:control> element
     *
     * @var array
     * @see GSC_Product::add*Destination(), GSC_Product::clearAllDestinations()
     **/
    public static $control = array(_GSC_Ns::app, 'control');

    /**
     * <sc:required_destination> element
     *
     * @var array
     * @see GSC_Product::addRequiredDestination(), GSC_Product::clearAllDestinations()
     **/
    public static $required_destination = array(_GSC_Ns::sc, 'required_destination');

    /**
     * <sc:validate_destination> element
     *
     * @var array
     * @see GSC_Product::addValidateDestination(), GSC_Product::clearAllDestinations()
     **/
    public static $validate_destination = array(_GSC_Ns::sc, 'validate_destination');

    /**
     * <sc:excluded_destination> element
     *
     * @var array
     * @see GSC_Product::addExcludedDestination(), GSC_Product::clearAllDestinations()
     **/
    public static $excluded_destination = array(_GSC_Ns::sc, 'excluded_destination');

    /**
     * <sc:status> element
     *
     * @var array
     * @see GSC_Product::getDestinationStatus()
     **/
    public static $destinationStatus = array(_GSC_Ns::sc, 'status');

    /**
     * <sc:id> element
     *
     * @var array
     * @see GSC_Product::setSKU(), GSC_Product::getSKU()
     **/
    public static $id = array(_GSC_Ns::sc, 'id');

    /**
     * <sc:attribute> element
     *
     * @var array
     * @see GSC_Product::setAttribute(), GSC_Product::getAttribute(),
     *      GSC_Product::getAttributeType(), GSC_Product::getAttributeUnit()
     **/
    public static $attribute = array(_GSC_Ns::sc, 'attribute');

    /**
     * <sc:group> element
     *
     * @var array
     * @see GSC_Product::setGroup(), GSC_Product::getGroup(),
     *      GSC_Product::getGroups()
     **/
    public static $group = array(_GSC_Ns::sc, 'group');

    /**
     * <sc:performance> element
     *
     * @var array
     * @see GSC_Product::getDatapoints()
     **/
    public static $performance = array(_GSC_Ns::sc, 'performance');

    /**
     * <sc:datapoint> element
     *
     * @var array
     * @see GSC_Product::getDatapoints(), GSC_Product::getDatapointClicks(),
     *      GSC_Product::getDatapointDate(),
     *      GSC_Product::getDatapointPaidClicks()
     **/
    public static $datapoint = array(_GSC_Ns::sc, 'datapoint');

    /**
     * <sc:warnings> element
     *
     * @var array
     * @see GSC_Product::getWarnings()
     **/
    public static $warnings = array(_GSC_Ns::sc, 'warnings');

    /**
     * <sc:warning> element
     *
     * @var array
     * @see GSC_Product::getWarnings(), GSC_Product::getWarningCode(),
     *      GSC_Product::getWarningDomain(), GSC_Product::getWarningLocation(),
     *      GSC_Product::getWarningMessage()
     **/
    public static $warning = array(_GSC_Ns::sc, 'warning');

    /**
     * <sc:code> element
     *
     * @var array
     * @see GSC_Product::getWarningCode()
     **/
    public static $warningCode = array(_GSC_Ns::sc, 'code');

    /**
     * <sc:domain> element
     *
     * @var array
     * @see GSC_Product::getWarningDomain()
     **/
    public static $warningDomain = array(_GSC_Ns::sc, 'domain');

    /**
     * <sc:location> element
     *
     * @var array
     * @see GSC_Product::getWarningLocation()
     **/
    public static $warningLocation = array(_GSC_Ns::sc, 'location');

    /**
     * <sc:message> element
     *
     * @var array
     * @see GSC_Product::getWarningMessage()
     **/
    public static $message = array(_GSC_Ns::sc, 'message');

    /**
     * <sc:disapproved> element
     *
     * @var array
     * @see GSC_Product::getDisapproved()
     **/
    public static $disapproved = array(_GSC_Ns::sc, 'disapproved');

    /**
     * <sc:adult> element
     *
     * @var array
     * @see GSC_Product::setAdult(), GSC_Product::getAdult()
     **/
    public static $adult = array(_GSC_Ns::sc, 'adult');

    /**
     * <sc:target_country> element
     *
     * @var array
     * @see GSC_Product::setTargetCountry(), GSC_Product::getTargetCountry()
     **/
    public static $target_country = array(_GSC_Ns::sc, 'target_country');

    /**
     * <sc:content_language> element
     *
     * @var array
     * @see GSC_Product::setContentLanguage(), GSC_Product::getContentLanguage()
     **/
    public static $content_language = array(_GSC_Ns::sc, 'content_language');

    /**
     * <sc:image_link> element
     *
     * @var array
     * @see GSC_Product::setImageLink(), GSC_Product::getImageLink()
     **/
    public static $image_link = array(_GSC_Ns::sc, 'image_link');

    /**
     * <sc:additional_image_link> element
     *
     * @var array
     * @see GSC_Product::addAdditionalImageLink(), GSC_Product::clearAllAdditionalImageLinks()
     **/
    public static $additional_image_link = array(_GSC_Ns::sc, 'additional_image_link');

    /**
     * <sc:expiration_date> element
     *
     * @var array
     * @see GSC_Product::setExpirationDate(), GSC_Product::getExpirationDate()
     **/
    public static $expiration_date = array(_GSC_Ns::sc, 'expiration_date');

    /**
     * <sc:account_status> element
     *
     * @var array
     * @see GSC_ManagedAccount::getAccountStatus()
     **/
    public static $account_status = array(_GSC_Ns::sc, 'account_status');

    /**
     * <sc:adult_content> element
     *
     * @var array
     * @see GSC_ManagedAccount::setAdultContent(),
     *      GSC_ManagedAccount::getAdultContent()
     **/
    public static $adult_content = array(_GSC_Ns::sc, 'adult_content');

    /**
     * <sc:internal_id> element
     *
     * @var array
     * @see GSC_ManagedAccount::setInternalId(),
     *      GSC_ManagedAccount::getInternalId()
     **/
    public static $internal_id = array(_GSC_Ns::sc, 'internal_id');

    /**
     * <sc:reviews_url> element
     *
     * @var array
     * @see GSC_ManagedAccount::setReviewsUrl(),
     *      GSC_ManagedAccount::getReviewsUrl()
     **/
    public static $reviews_url = array(_GSC_Ns::sc, 'reviews_url');

    /**
     * <sc:adwords_account> element
     *
     * @var array
     * @see GSC_ManagedAccount::addAdwordsAccount(),
     *      GSC_ManagedAccount::clearAdwordsAccounts()
     **/
    public static $adwords_account = array(_GSC_Ns::sc, 'adwords_account');

    /**
     * <sc:adwords_accounts> element
     *
     * @var array
     * @see GSC_ManagedAccount::addAdwordsAccount(),
     *      GSC_ManagedAccount::clearAdwordsAccounts()
     **/
    public static $adwords_accounts = array(_GSC_Ns::sc, 'adwords_accounts');

    /**
     * <sc:feed_file_name> element
     *
     * @var array
     * @see GSC_Datafeed::setFeedFileName(), GSC_Datafeed::getFeedFileName()
     **/
    public static $feed_file_name = array(_GSC_Ns::sc, 'feed_file_name');

    /**
     * <sc:attribute_language> element
     *
     * @var array
     * @see GSC_Datafeed::setAttributeLanguage(),
     *      GSC_Datafeed::getAttributeLanguage()
     **/
    public static $attribute_language = array(_GSC_Ns::sc, 'attribute_language');

    /**
     * <sc:file_format> element
     *
     * @var array
     * @see GSC_Datafeed::setFileFormat(), GSC_Datafeed::getFileFormat()
     **/
    public static $file_format = array(_GSC_Ns::sc, 'file_format');

    /**
     * <sc:encoding> element
     *
     * @var array
     * @see GSC_Datafeed::setEncoding(), GSC_Datafeed::getEncoding()
     **/
    public static $encoding = array(_GSC_Ns::sc, 'encoding');

    /**
     * <sc:delimiter> element
     *
     * @var array
     * @see GSC_Datafeed::setDelimiter(), GSC_Datafeed::getDelimiter()
     **/
    public static $delimiter = array(_GSC_Ns::sc, 'delimiter');

    /**
     * <sc:use_quoted_fields> element
     *
     * @var array
     * @see GSC_Datafeed::setUseQuotedFields(),
     *      GSC_Datafeed::getUseQuotedFields()
     **/
    public static $use_quoted_fields = array(_GSC_Ns::sc, 'use_quoted_fields');

    /**
     * <sc:feed_type> element
     *
     * @var array
     * @see GSC_Datafeed::getFeedType()
     **/
    public static $feed_type = array(_GSC_Ns::sc, 'feed_type');

    /**
     * <sc:processing_status> element
     *
     * @var array
     * @see GSC_Datafeed::getProcessingStatus()
     **/
    public static $processing_status = array(_GSC_Ns::sc, 'processing_status');

    /**
     * <sc:feed_destination> element
     *
     * @var array
     * @see GSC_Datafeed::addFeedDestination(),
     *      GSC_Datafeed::getFeedDestinations(),
     *      GSC_Datafeed::getFeedDestination(),
     *      GSC_Datafeed::getFeedDestinationEnabled(),
     *      GSC_Datafeed::clearAllFeedDestinations()
     **/
    public static $feed_destination = array(_GSC_Ns::sc, 'feed_destination');

    /**
     * <sc:channel> element
     *
     * @var array
     * @see GSC_Product::setChannel(), GSC_Product::getChannel(),
     *      GSC_Datafeed::setChannel(), GSC_Datafeed::getChannel()
     **/
    public static $channel = array(_GSC_Ns::sc, 'channel');

    /**
     * <sc:link> element
     *
     * @var array
     * @see GSC_ExampleItem::getLink()
     **/
    public static $exampleItemLink = array(_GSC_Ns::sc, 'link');

    /**
     * <sc:title> element
     *
     * @var array
     * @see GSC_ExampleItem::getTitle()
     **/
    public static $exampleItemTitle = array(_GSC_Ns::sc, 'title');

    /**
     * <sc:item_id> element
     *
     * @var array
     * @see GSC_ExampleItem::getItemId()
     **/
    public static $item_id = array(_GSC_Ns::sc, 'item_id');

    /**
     * <sc:submitted_value> element
     *
     * @var array
     * @see GSC_ExampleItem::getSubmittedValue()
     **/
    public static $submitted_value = array(_GSC_Ns::sc, 'submitted_value');

    /**
     * <sc:value_on_landing_page> element
     *
     * @var array
     * @see GSC_ExampleItem::getValueOnLandingPage()
     **/
    public static $value_on_landing_page = array(_GSC_Ns::sc, 'value_on_landing_page');

    /**
     * <sc:example_item> element
     *
     * @var array
     * @see GSC_Issue::getExampleItems()
     **/
    public static $example_item = array(_GSC_Ns::sc, 'example_item');

    /**
     * <sc:issue> element
     *
     * @var array
     * @see GSC_IssueGroup::getIssues()
     **/
    public static $issue = array(_GSC_Ns::sc, 'issue');

    /**
     * <sc:issue_group> element
     *
     * @var array
     * @see GSC_DataQualityEntry::getIssueGroups()
     **/
    public static $issue_group = array(_GSC_Ns::sc, 'issue_group');

    /**
     * <sc:issue_groups> element
     *
     * @var array
     * @see GSC_DataQualityEntry::getIssueGroups()
     **/
    public static $issue_groups = array(_GSC_Ns::sc, 'issue_groups');

    /**
     * <scp:sale_price> element
     *
     * @var array
     * @see GSC_InventoryEntry::setSalePrice(), GSC_InventoryEntry::getSalePrice(),
     *      GSC_InventoryEntry::getSalePriceUnit()
     **/
    public static $sale_price = array(_GSC_Ns::scp, 'sale_price');

    /**
     * <scp:sale_price_effective_date> element
     *
     * @var array
     * @see GSC_InventoryEntry::setSalePriceEffectiveDate(),
     *      GSC_InventoryEntry::getSalePriceEffectiveDate()
     **/
    public static $sale_price_effective_date = array(_GSC_Ns::scp, 'sale_price_effective_date');

    /**
     * <sc:admin> element
     *
     * @var array
     * @see GSC_User::setAdmin(), GSC_User::getAdmin()
     **/
    public static $admin = array(_GSC_Ns::sc, 'admin');

    /**
     * <sc:permission> element
     *
     * @var array
     * @see GSC_User::addPermission(), GSC_User::clearAllPermissions()
     **/
    public static $permission = array(_GSC_Ns::sc, 'permission');

    /**
     * <scp:price> element
     *
     * @var array
     * @see GSC_Product::setPrice(), GSC_Product::getPrice(), GSC_Product::getPriceUnit(),
     *      GSC_InventoryEntry::setPrice(), GSC_InventoryEntry::getPrice(),
     *      GSC_InventoryEntry::getPriceUnit()
     **/
    public static $price = array(_GSC_Ns::scp, 'price');

    /**
     * <scp:condition> element
     *
     * @var array
     * @see GSC_Product::setCondition(), GSC_Product::getCondition()
     **/
    public static $condition = array(_GSC_Ns::scp, 'condition');

    /**
     * <scp:shipping> element
     *
     * @var array
     * @see GSC_Product::addShipping(), GSC_Product::clearAllShippings()
     **/
    public static $shipping = array(_GSC_Ns::scp, 'shipping');

    /**
     * <scp:shipping_country> element
     *
     * @var array
     * @see GSC_Product::addShipping(), GSC_Product::clearAllShippings()
     **/
    public static $shipping_country = array(_GSC_Ns::scp, 'shipping_country');

    /**
     * <scp:shipping_region> element
     *
     * @var array
     * @see GSC_Product::addShipping(), GSC_Product::clearAllShippings()
     **/
    public static $shipping_region = array(_GSC_Ns::scp, 'shipping_region');

    /**
     * <scp:shipping_price> element
     *
     * @var array
     * @see GSC_Product::addShipping(), GSC_Product::clearAllShippings()
     **/
    public static $shipping_price = array(_GSC_Ns::scp, 'shipping_price');

    /**
     * <scp:shipping_service> element
     *
     * @var array
     * @see GSC_Product::addShipping(), GSC_Product::clearAllShippings()
     **/
    public static $shipping_service = array(_GSC_Ns::scp, 'shipping_service');

    /**
     * <scp:tax> element
     *
     * @var array
     * @see GSC_Product::addTax(), GSC_Product::clearAllTaxes()
     **/
    public static $tax = array(_GSC_Ns::scp, 'tax');

    /**
     * <scp:tax_country> element
     *
     * @var array
     * @see GSC_Product::addTax(), GSC_Product::clearAllTaxes()
     **/
    public static $tax_country = array(_GSC_Ns::scp, 'tax_country');

    /**
     * <scp:tax_region> element
     *
     * @var array
     * @see GSC_Product::addTax(), GSC_Product::clearAllTaxes()
     **/
    public static $tax_region = array(_GSC_Ns::scp, 'tax_region');

    /**
     * <scp:tax_rate> element
     *
     * @var array
     * @see GSC_Product::addTax(), GSC_Product::clearAllTaxes()
     **/
    public static $tax_rate = array(_GSC_Ns::scp, 'tax_rate');

    /**
     * <scp:tax_ship> element
     *
     * @var array
     * @see GSC_Product::addTax(), GSC_Product::clearAllTaxes()
     **/
    public static $tax_ship = array(_GSC_Ns::scp, 'tax_ship');

    /**
     * <scp:age_group> element
     *
     * @var array
     * @see GSC_Product::setAgeGroup(), GSC_Product::getAgeGroup()
     **/
    public static $age_group = array(_GSC_Ns::scp, 'age_group');

    /**
     * <scp:author> element
     *
     * @var array
     * @see GSC_Product::setAuthor(), GSC_Product::getAuthor()
     **/
    public static $author = array(_GSC_Ns::scp, 'author');

    /**
     * <scp:availability> element
     *
     * @var array
     * @see GSC_Product::setAvailability(), GSC_Product::getAvailability(),
     *      GSC_InventoryEntry::setAvailability(), GSC_InventoryEntry::getAvailability()
     **/
    public static $availability = array(_GSC_Ns::scp, 'availability');

    /**
     * <scp:brand> element
     *
     * @var array
     * @see GSC_Product::setBrand(), GSC_Product::getBrand()
     **/
    public static $brand = array(_GSC_Ns::scp, 'brand');

    /**
     * <scp:color> element
     *
     * @var array
     * @see GSC_Product::setColor(), GSC_Product::getColor()
     **/
    public static $color = array(_GSC_Ns::scp, 'color');

    /**
     * <scp:edition> element
     *
     * @var array
     * @see GSC_Product::setEdition(), GSC_Product::getEdition()
     **/
    public static $edition = array(_GSC_Ns::scp, 'edition');

    /**
     * <scp:feature> element
     *
     * @var array
     * @see GSC_Product::addFeature(), GSC_Product::clearAllFeatures()
     **/
    public static $feature = array(_GSC_Ns::scp, 'feature');

    /**
     * <scp:featured_product> element
     *
     * @var array
     * @see GSC_Product::setFeaturedProduct(), GSC_Product::getFeaturedProduct()
     **/
    public static $featured_product = array(_GSC_Ns::scp, 'featured_product');

    /**
     * <scp:manufacturer> element
     *
     * @var array
     * @see GSC_Product::setManufacturer(), GSC_Product::getManufacturer()
     **/
    public static $manufacturer = array(_GSC_Ns::scp, 'manufacturer');

    /**
     * <scp:mpn> element
     *
     * @var array
     * @see GSC_Product::setMpn(), GSC_Product::getMpn()
     **/
    public static $mpn = array(_GSC_Ns::scp, 'mpn');

    /**
     * <scp:online_only> element
     *
     * @var array
     * @see GSC_Product::setOnlineOnly(), GSC_Product::getOnlineOnly()
     **/
    public static $online_only = array(_GSC_Ns::scp, 'online_only');

    /**
     * <scp:gtin> element
     *
     * @var array
     * @see GSC_Product::setGtin(), GSC_Product::getGtin()
     **/
    public static $gtin = array(_GSC_Ns::scp, 'gtin');

    /**
     * <scp:product_type> element
     *
     * @var array
     * @see GSC_Product::setProductType(), GSC_Product::getProductType()
     **/
    public static $product_type = array(_GSC_Ns::scp, 'product_type');

    /**
     * <scp:product_review_average> element
     *
     * @var array
     * @see GSC_Product::setProductReviewAverage(), GSC_Product::getProductReviewAverage()
     **/
    public static $product_review_average = array(_GSC_Ns::scp, 'product_review_average');

    /**
     * <scp:product_review_count> element
     *
     * @var array
     * @see GSC_Product::setProductReviewCount(),
     *      GSC_Product::getProductReviewCount()
     **/
    public static $product_review_count = array(_GSC_Ns::scp, 'product_review_count');

    /**
     * <scp:quantity> element
     *
     * @var array
     * @see GSC_Product::setQuantity(), GSC_Product::getQuantity(),
     *      GSC_InventoryEntry::setQuantity(), GSC_InventoryEntry::getQuantity()
     **/
    public static $quantity = array(_GSC_Ns::scp, 'quantity');

    /**
     * <scp:shipping_weight> element
     *
     * @var array
     * @see GSC_Product::setShippingWeight(), GSC_Product::getShippingWeight()
     **/
    public static $shipping_weight = array(_GSC_Ns::scp, 'shipping_weight');

    /**
     * <scp:size> element
     *
     * @var array
     * @see GSC_Product::addSize(), GSC_Product::clearAllSizes()
     **/
    public static $size = array(_GSC_Ns::scp, 'size');

    /**
     * <scp:year> element
     *
     * @var array
     * @see GSC_Product::setYear(), GSC_Product::getYear()
     **/
    public static $year = array(_GSC_Ns::scp, 'year');

    /**
     * <scp:gender> element
     *
     * @var array
     * @see GSC_Product::setGender(), GSC_Product::getGender()
     **/
    public static $gender = array(_GSC_Ns::scp, 'gender');

    /**
     * <scp:genre> element
     *
     * @var array
     * @see GSC_Product::setGenre(), GSC_Product::getGenre()
     **/
    public static $genre = array(_GSC_Ns::scp, 'genre');

    /**
     * <scp:item_group_id> element
     *
     * @var array
     * @see GSC_Product::setItemGroupId(), GSC_Product::getItemGroupId()
     **/
    public static $item_group_id = array(_GSC_Ns::scp, 'item_group_id');

    /**
     * <scp:google_product_category> element
     *
     * @var array
     * @see GSC_Product::setGoogleProductCategory(), GSC_Product::getGoogleProductCategory()
     **/
    public static $google_product_category = array(_GSC_Ns::scp, 'google_product_category');

    /**
     * <scp:material> element
     *
     * @var array
     * @see GSC_Product::setMaterial(), GSC_Product::getMaterial()
     **/
    public static $material = array(_GSC_Ns::scp, 'material');

    /**
     * <scp:pattern> element
     *
     * @var array
     * @see GSC_Product::setPattern(), GSC_Product::getPattern()
     **/
    public static $pattern = array(_GSC_Ns::scp, 'pattern');

    /**
     * <scp:adwords_grouping> element
     *
     * @var array
     * @see GSC_Product::setAdwordsGrouping(), GSC_Product::getAdwordsGrouping()
     **/
    public static $adwords_grouping = array(_GSC_Ns::scp, 'adwords_grouping');

    /**
     * <scp:adwords_labels> element
     *
     * @var array
     * @see GSC_Product::setAdwordsLabels(), GSC_Product::getAdwordsLabels()
     **/
    public static $adwords_labels = array(_GSC_Ns::scp, 'adwords_labels');

    /**
     * <scp:adwords_redirect> element
     *
     * @var array
     * @see GSC_Product::setAdwordsRedirect(), GSC_Product::getAdwordsRedirect()
     **/
    public static $adwords_redirect = array(_GSC_Ns::scp, 'adwords_redirect');

    /**
     * <scp:adwords_queryparam> element
     *
     * @var array
     * @see GSC_Product::setAdwordsQueryparam(), GSC_Product::getAdwordsQueryparam()
     **/
    public static $adwords_queryparam = array(_GSC_Ns::scp, 'adwords_queryparam');

    /**
     * <scp:identifier_exists> element
     *
     * @var array
     * @see GSC_Product::setIdentifierExistsQueryParam(), GSC_Product::getIdentifierExistsQueryParam()
     **/
    public static $identifier_exists = array(_GSC_Ns::scp, 'identifier_exists');

    /**
     * <scp:unit_pricing_measure> element
     *
     * @var array
     * @see GSC_Product::setUnitPricingMeasure(), GSC_Product::getUnitPricingMeasure()
     **/
    public static $unit_pricing_measure = array(_GSC_Ns::scp, 'unit_pricing_measure');

     /**
     * <scp:unit_pricing_base_measure> element
     *
     * @var array
     * @see GSC_Product::setUnitPricingBaseMeasure(), GSC_Product::getUnitPricingBaseMeasure()
     **/
    public static $unit_pricing_base_measure = array(_GSC_Ns::scp, 'unit_pricing_base_measure');

    /**
     * <scp:energy_efficiency_class> element
     *
     * @var array
     * @see GSC_Product::setEnergyEfficiencyClass(), GSC_Product::getEnergyEfficiencyClass()
     **/
    public static $energy_efficiency_class = array(_GSC_Ns::scp, 'energy_efficiency_class');

    /**
     * <scp:merchant_multipack_quantity> element
     *
     * @var array
     * @see GSC_Product::setMultipack(), GSC_Product::getMultipack()
     **/
    public static $multipack = array(_GSC_Ns::scp, 'multipack');

    /**
     * <sc:fetch_schedule> element
     *
     * @var array
     * @see GSC_Datafeed::getFetchDayOfMonth(),
     *      GSC_Datafeed::setFetchDayOfMonth(),
     *      GSC_Datafeed::getFetchUrl(),
     *      GSC_Datafeed::getFetchUsername(),
     *      GSC_Datafeed::getFetchPassword(),
     *      GSC_Datafeed::setFetchUrl(),
     *      GSC_Datafeed::getFetchHour(),
     *      GSC_Datafeed::getFetchTimezone(),
     *      GSC_Datafeed::setFetchHour(),
     *      GSC_Datafeed::getFetchWeekday(),
     *      GSC_Datafeed::setFetchWeekday()
     **/
    public static $fetchSchedule = array(_GSC_Ns::sc, 'fetch_schedule');

    /**
     * <sc:day_of_month> element
     *
     * @var array
     * @see GSC_Datafeed::getFetchDayOfMonth(), GSC_Datafeed::setFetchDayOfMonth()
     **/
    public static $fetchDayOfMonth = array(_GSC_Ns::sc, 'day_of_month');

    /**
     * <sc:fetch_url> element
     *
     * @var array
     * @see GSC_Datafeed::getFetchUrl(),
     *      GSC_Datafeed::getFetchUsername(),
     *      GSC_Datafeed::getFetchPassword(),
     *      GSC_Datafeed::setFetchUrl()
     **/
    public static $fetchUrl = array(_GSC_Ns::sc, 'fetch_url');

    /**
     * <sc:hour> element
     *
     * @var array
     * @see GSC_Datafeed::getFetchHour(),
     *      GSC_Datafeed::getFetchTimezone(),
     *      GSC_Datafeed::setFetchHour()
     **/
    public static $fetchHour = array(_GSC_Ns::sc, 'hour');

    /**
     * <sc:weekday> element
     *
     * @var array
     * @see GSC_Datafeed::getFetchWeekday(), GSC_Datafeed::setFetchWeekday()
     **/
    public static $fetchWeekday = array(_GSC_Ns::sc, 'weekday');
}


/**
 * Atom Parser
 *
 * @package GShoppingContent
 **/
class _GSC_AtomParser {

    /**
     * Parse some XML into a DOM Element.
     *
     * @param string $xml The XML to parse.
     * @return DOMElement A DOM element appropriate to the XML.
     **/
    public static function _xmlToDOM($xml) {
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXML($xml);
        return $doc;
    }

    /**
     * Parse some XML into our data model.
     *
     * @param string $xml The XML to parse.
     * @return _GSC_AtomElement An Atom element appropriate to the XML.
     * @throws GSC_ParseError|GSC_RequestError If the XML is a gd:errors
     *                                         element, a GSC_RequestError
     *                                         is thrown with the contents of
     *                                         the XML. Otherwise, if the XML
     *                                         is not a feed or entry, a
     *                                         GSC_ParseError is thrown.
     **/
    public static function parse($xml) {
        $doc = _GSC_AtomParser::_xmlToDOM($xml);
        $root = $doc->documentElement;
        if ($root->tagName == 'entry') {
            return new GSC_Product($doc, $root);
        }
        else if ($root->tagName == 'feed') {
            return new GSC_ProductList($doc, $root);
        }
        else if ($root->tagName == 'errors') {
            $errors = new GSC_Errors($doc, $root);
            throw new GSC_RequestError($errors);
        }

        throw new GSC_ParseError($xml);
    }

    /**
     * Parse some XML into our data model for the managedaccounts feed.
     *
     * @param string $xml The XML to parse.
     * @return _GSC_AtomElement An Atom element appropriate to the XML.
     * @throws GSC_ParseError|GSC_RequestError If the XML is a gd:errors
     *                                         element, a GSC_RequestError
     *                                         is thrown with the contents of
     *                                         the XML. Otherwise, if the XML
     *                                         is not a feed or entry, a
     *                                         GSC_ParseError is thrown.
     **/
    public static function parseManagedAccounts($xml) {
        $doc = _GSC_AtomParser::_xmlToDOM($xml);
        $root = $doc->documentElement;
        if ($root->tagName == 'entry') {
            return new GSC_ManagedAccount($doc, $root);
        }
        else if ($root->tagName == 'feed') {
            return new GSC_ManagedAccountList($doc, $root);
        }
        else if ($root->tagName == 'errors') {
            $errors = new GSC_Errors($doc, $root);
            throw new GSC_RequestError($errors);
        }

        throw new GSC_ParseError($xml);
    }

    /**
     * Parse some XML into our data model for the datafeeds feed.
     *
     * @param string $xml The XML to parse.
     * @return _GSC_AtomElement An Atom element appropriate to the XML.
     * @throws GSC_ParseError|GSC_RequestError If the XML is a gd:errors
     *                                         element, a GSC_RequestError
     *                                         is thrown with the contents of
     *                                         the XML. Otherwise, if the XML
     *                                         is not a feed or entry, a
     *                                         GSC_ParseError is thrown.
     **/
    public static function parseDatafeeds($xml) {
        $doc = _GSC_AtomParser::_xmlToDOM($xml);
        $root = $doc->documentElement;
        if ($root->tagName == 'entry') {
            return new GSC_Datafeed($doc, $root);
        }
        else if ($root->tagName == 'feed') {
            return new GSC_DatafeedList($doc, $root);
        }
        else if ($root->tagName == 'errors') {
            $errors = new GSC_Errors($doc, $root);
            throw new GSC_RequestError($errors);
        }

        throw new GSC_ParseError($xml);
    }

    /**
     * Parse some XML into our data model for the users feed.
     *
     * @param string $xml The XML to parse.
     * @return _GSC_AtomElement An Atom element appropriate to the XML.
     * @throws GSC_ParseError|GSC_RequestError If the XML is a gd:errors
     *                                         element, a GSC_RequestError
     *                                         is thrown with the contents of
     *                                         the XML. Otherwise, if the XML
     *                                         is not a feed or entry, a
     *                                         GSC_ParseError is thrown.
     **/
    public static function parseUsers($xml) {
        $doc = _GSC_AtomParser::_xmlToDOM($xml);
        $root = $doc->documentElement;
        if ($root->tagName == 'entry') {
            return new GSC_User($doc, $root);
        }
        else if ($root->tagName == 'feed') {
            return new GSC_UserList($doc, $root);
        }
        else if ($root->tagName == 'errors') {
            $errors = new GSC_Errors($doc, $root);
            throw new GSC_RequestError($errors);
        }

        throw new GSC_ParseError($xml);
    }

    /**
     * Parse some XML into our data model for the users feed.
     *
     * @param string $xml The XML to parse.
     * @return _GSC_AtomElement An Atom element appropriate to the XML.
     * @throws GSC_ParseError|GSC_RequestError If the XML is a gd:errors
     *                                         element, a GSC_RequestError
     *                                         is thrown with the contents of
     *                                         the XML. Otherwise, if the XML
     *                                         is not a feed or entry, a
     *                                         GSC_ParseError is thrown.
     **/
    public static function parseInventory($xml) {
        $doc = _GSC_AtomParser::_xmlToDOM($xml);
        $root = $doc->documentElement;
        if ($root->tagName == 'entry') {
            return new GSC_InventoryEntry($doc, $root);
        }
        else if ($root->tagName == 'feed') {
            return new GSC_InventoryEntryList($doc, $root);
        }
        else if ($root->tagName == 'errors') {
            $errors = new GSC_Errors($doc, $root);
            throw new GSC_RequestError($errors);
        }

        throw new GSC_ParseError($xml);
    }

    /**
     * Parse some XML into our data model for the data quality feed.
     *
     * @param string $xml The XML to parse.
     * @return _GSC_AtomElement An Atom element appropriate to the XML.
     * @throws GSC_ParseError|GSC_RequestError If the XML is a gd:errors
     *                                         element, a GSC_RequestError
     *                                         is thrown with the contents of
     *                                         the XML. Otherwise, if the XML
     *                                         is not a feed or entry, a
     *                                         GSC_ParseError is thrown.
     **/
    public static function parseDataQuality($xml) {
        $doc = _GSC_AtomParser::_xmlToDOM($xml);
        $root = $doc->documentElement;
        if ($root->tagName == 'entry') {
            return new GSC_DataQualityEntry($doc, $root);
        }
        else if ($root->tagName == 'feed') {
            return new GSC_DataQualityFeed($doc, $root);
        }
        else if ($root->tagName == 'errors') {
            $errors = new GSC_Errors($doc, $root);
            throw new GSC_RequestError($errors);
        }

        throw new GSC_ParseError($xml);
    }

}


/**
 * The base implementation for retrieving and setting values from a chunk of
 * XML.
 *
 * This class, and concrete implementations will store no internal state. Their
 * entire data is stored in the $model as XML, and is controlled using the owner
 * $doc.
 *
 * @package GShoppingContent
 **/
abstract class _GSC_AtomElement
{
    /**
     * DOMDocument for saving model to XML and creating elements with
     * no parents. Defaults to the return value of createDoc.
     *
     * @var DOMDocument
     **/
    public $doc;

    /**
     * Base DOMElement for the _GSC_AtomElement being built. Defaults
     * to the return value of createModel.
     *
     * @var DOMElement
     **/
    public $model;

    /**
     * Create a new _GSC_AtomElement
     *
     * The data for this element can come from one of two places. Either some
     * XML from the API, or created from scratch. If the $model and the $doc are
     * not provided, empty versions are created. The default $model creation
     * should be controlled by overriding _GSC_AtomElement::createModel().
     *
     * @param DOMDocument $doc An existing DOM Document.
     * @param DOMElement $model An existing DOM Element.
     * @return _GSC_AtomElement
     **/
    function __construct($doc=null, $model=null) {
        // ternerahay!
        $this->doc = $doc ? $doc : $this->createDoc();
        $this->model = $model ? $model : $this->createModel();
    }

    /**
     * Get the first element of a tag type.
     *
     * @param array $tag The tag describing the attribute we seek.
     * @param DOMElement $parent An optional parent element to define where
     *                           to search. Defaults to null and is replaced
     *                           by $this->model if set to null.
     * @return Element.
     **/
    protected function getFirst($tag, $parent=null) {
        $el = $parent ? $parent : $this->model;
        $list = $el->getElementsByTagNameNS($tag[0], $tag[1]);
        if ($list->length > 0) {
            $el = $list->item(0);
            return $el;
        }
        else {
            return null;
        }
    }

    /**
     * Get the first element of a tag type or create it if it doesn't exist.
     *
     * @param array $tag The tag describing the attribute we seek.
     * @param DOMElement $el An optional parent element to be passed in to
     *                       getFirst. Defaults to null.
     * @return DOMElement that was created.
     **/
    protected function getCreateFirst($tag, $parent=null) {
        $el = $parent ? $parent : $this->model;
        $child = $this->getFirst($tag, $parent);
        if ($child == null) {
            $child = $this->create($tag);
            $el->appendChild($child);
            return $child;
        }
        else {
            return $child;
        }
    }

    /**
     * Get the value of the first element matching the tag.
     *
     * @param array $tag The tag describing the attribute we seek.
     * @param DOMElement $el An optional parent element to be passed in to
     *                       getFirst. Defaults to null.
     * @return string Node value of the first element matching the tag, or
     *                empty string if no match.
     **/
    protected function getFirstValue($tag, $el=null) {
        $child = $this->getFirst($tag, $el);
        if ($child) {
            return $child->nodeValue;
        }
        else {
            return '';
        }
    }

    /**
     * Set the value of the first element matching the tag.
     *
     * @param array $tag The tag describing the attribute we seek to find
     *                   or create.
     * @param array $val The value we want to set.
     * @param DOMElement $parent An optional parent element to be passed in to
     *                           getCreateFirst. Defaults to null.
     * @return DOMElement The element that was changed or created.
     **/
    protected function setFirstValue($tag, $val, $parent=null) {
        $child = $this->getCreateFirst($tag, $parent);
        $textNode = $this->doc->createTextNode($val);

        // Remove any existing text nodes that are children
        while ($child->hasChildNodes()) {
            $child->removeChild($child->firstChild);
        }

        $child->appendChild($textNode);
        return $child;
    }

    /**
     * Get all elements matching the tag.
     *
     * @param array $tag The tag describing the attribute.
     * @param DOMElement $parent An optional parent element. Defaults to null.
     * @return DOMNodeList A list of all matching DOMElements.
     **/
    function getAll($tag, $parent=null) {
        $el = $parent ? $parent : $this->model;
        $list = $el->getElementsByTagNameNS($tag[0], $tag[1]);
        return $list;
    }

    /**
     * Delete all elements matching the tag.
     *
     * @param array $tag The tag describing the attribute.
     * @param DOMElement $parent An optional parent element. Defaults to null.
     * @return void
     **/
    function deleteAll($tag, $parent=null) {
        $el = $parent ? $parent : $this->model;
        $list = $el->getElementsByTagNameNS($tag[0], $tag[1]);
        while($list->length) {
            $child = $list->item(0);
            $el->removeChild($child);
        }
    }

    /**
     * Get the first atom link attribute with a specified rel= value.
     *
     * @param string $rel The value of rel= we seek to find.
     * @return DOMElement The atom link attribute matching the rel value,
     *                    else null if there is no match.
     **/
    function getLink($rel) {
        $list = $this->model->getElementsByTagNameNS(_GSC_Ns::atom, 'link');
        $count = $list->length;
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            if ($child->getAttribute('rel') == $rel) {
                return $child;
            }
        }
        return null;
    }

    /**
     * Get the edit link.
     *
     * @return string The edit link.
     **/
    function getEditLink() {
        $el = $this->getLink('edit');
        if ($el == null) {
            return '';
        }
        else {
            return $el->getAttribute('href');
        }
    }

    /**
     * Set the edit link.
     *
     * @param string $link The edit link to add.
     * @param string $type The type of the added link.
     * @return DOMElement The element that was changed or created.
     **/
    function setEditLink($link, $type) {
        $el = $this->getLink('edit');
        if ($el == null) {
            $el = $this->create(_GSC_Tags::$link);
            $el->setAttribute('rel', 'edit');
            $this->model->appendChild($el);
        }

        $el->setAttribute('href', $link);
        $el->setAttribute('type', $type);
    }

    /**
     * Get the atom ID.
     *
     * @return string The atom ID.
     **/
    function getAtomId() {
        return $this->getFirstValue(_GSC_Tags::$atomId);
    }

    /**
     * Set the atom ID.
     *
     * @param string $atomId The atom ID to set.
     * @return DOMElement The element that was changed.
     **/
    function setAtomId($atomId) {
        return $this->setFirstValue(_GSC_Tags::$atomId, $atomId);
    }

    /**
     * Get the published date.
     *
     * @return string The published date.
     **/
    function getPublished() {
        return $this->getFirstValue(_GSC_Tags::$published);
    }

    /**
     * Get the updated date.
     *
     * @return string The updated date.
     **/
    function getUpdated() {
        return $this->getFirstValue(_GSC_Tags::$updated);
    }

    /**
     * Get the atom author.
     *
     * @return string The atom author.
     **/
    function getAtomAuthor() {
        return $this->getFirst(_GSC_Tags::$atomAuthor);
    }

    /**
     * Get the author name.
     *
     * @return string The author name.
     **/
    function getAuthorName() {
        $author = $this->getAtomAuthor();
        return $this->getFirstValue(_GSC_Tags::$name, $author);
    }

    /**
     * Get the author email.
     *
     * @return string The author email.
     **/
    function getAuthorEmail() {
        $author = $this->getAtomAuthor();
        return $this->getFirstValue(_GSC_Tags::$email, $author);
    }
    /**
     * Get the title.
     *
     * @return string The title.
     **/
    public function getTitle() {
        return $this->getFirstValue(_GSC_Tags::$title);
    }

    /**
     * Set the title.
     *
     * @param string $title The title to set.
     * @return DOMElement The element that was changed.
     **/
    public function setTitle($title) {
        return $this->setFirstValue(_GSC_Tags::$title, $title);
    }

    /**
     * Get the description.
     *
     * @return string The description.
     **/
    function getDescription() {
        return $this->getFirstValue(_GSC_Tags::$content);
    }

    /**
     * Set the description.
     *
     * @param string $description The description to set.
     * @return DOMElement The element that was changed.
     **/
    function setDescription($description) {
        $el = $this->setFirstValue(_GSC_Tags::$content, $description);
        $el->setAttribute('type', 'text');
        return $el;
    }

    /**
     * Get the start index of search results.
     *
     * @return string The start index of search results.
     **/
    function getStartIndex() {
        return $this->getFirstValue(_GSC_Tags::$startIndex);
    }

    /**
     * Get the total number of search results.
     *
     * @return string The total number of search results.
     **/
    function getTotalResults() {
        return $this->getFirstValue(_GSC_Tags::$totalResults);
    }

    /**
     * Get the items per page of search results.
     *
     * @return string The items per page of search results.
     **/
    function getItemsPerPage() {
        return $this->getFirstValue(_GSC_Tags::$itemsPerPage);
    }

    /**
     * Get the time of last edit.
     *
     * @return string The time of the last edit.
     **/
    function getEdited() {
        return $this->getFirstValue(_GSC_Tags::$edited);
    }

    /**
     * Get the batch id of the product.
     *
     * @return string The batch id of the product.
     **/
    function getBatchId() {
        return $this->getFirstValue(_GSC_Tags::$batchId);
    }

    /**
     * Set the batch id of the product.
     *
     * @param string $batchId The id to set.
     * @return DOMElement The element that was changed.
     **/
    function setBatchId($batchId) {
        return $this->setFirstValue(_GSC_Tags::$batchId, $batchId);
    }

    /**
     * Get the desired attribute from the batch interrupted element.
     *
     * @param string $attribute The desired attribute from interrupted element.
     *                          Possible values include 'reason', 'success',
     *                          'failures' and 'parsed'.
     * @return string The value of the attribute.
     **/
    function getBatchInterruptedAttribute($attribute) {
        $el = $this->getFirst(_GSC_Tags::$interrupted);
        if ($el) {
            return $el->getAttribute($attribute);
        }
        else {
            return '';
        }
    }

    /**
     * Get the batch operation type of the product.
     *
     * @return string The operation type of the product.
     **/
    function getBatchOperation() {
        $el = $this->getFirst(_GSC_Tags::$operation);
        return $el->getAttribute('type');
    }

    /**
     * Set the batch operation type of the product.
     *
     * @param string $operation The operation to set.
     * @return DOMElement The element that was changed.
     **/
    function setBatchOperation($operation) {
        $el = $this->setFirstValue(_GSC_Tags::$operation, null);
        $el->setAttribute('type', $operation);
        return $el;
    }

    /**
     * Get the batch status code.
     *
     * @return string The status code for this batch operation
     **/
    function getBatchStatus() {
        $el = $this->getFirst(_GSC_Tags::$status);
        if ($el) {
            return $el->getAttribute('code');
        }
        else {
            return '';
        }
    }

    /**
     * Get the batch status reason.
     *
     * @return string The status reason for this batch operation
     **/
    function getBatchStatusReason() {
        $el = $this->getFirst(_GSC_Tags::$status);
        if ($el) {
            return $el->getAttribute('reason');
        }
        else {
            return '';
        }
    }

    /**
     * Create a default DOMDocument for creating DOMElements.
     *
     * @return DOMDocument The default DOM factory document.
     **/
    function createDoc() {
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        return $doc;
    }

    /**
     * Get a string representation of the XML DOM in the model.
     *
     * @return string The XML in $this->model as string.
     **/
    function toXML() {
        return $this->doc->saveXML($this->model);
    }

    /**
     * Use the DOC factory to create a DOMElement corresponding to the tag.
     *
     * @param array $tag The tag describing the attribute we seek.
     * @param string $content The value to be placed in the created attribute.
     *                        Defaults to null.
     * @return DOMElement The DOM Element holding the created attribute.
     **/
    function create($tag, $content=null) {
        return $this->doc->createElementNS($tag[0], $tag[1], $content);
    }

    /**
     * Create a default DOM Element for the atom element being built.
     *
     * @return DOMElement The default DOM element parent for the atom element.
     **/
    abstract function createModel();
}


/**
 * GSC_Product
 *
 * @package GShoppingContent
 **/
class GSC_Product extends _GSC_AtomElement {

    /**
     * Get a named generic attribute as a DOMElement.
     *
     * @param string $attributeName The generic attribute name.
     * @return DOMElement The DOM Element containing the generic attribute,
     *                    if it exists, else null.
     **/
    public function _getAttributeElement($attributeName) {
        $list = $this->getAll(_GSC_Tags::$attribute);
        $count = $list->length;

        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);

            if ($child->getAttribute('name') == $attributeName) {
                return $child;
            }
        }

        return null;
    }

    /**
     * Get the value of a named generic attribute.
     *
     * @param string $attributeName The generic attribute name.
     * @return string The value of the generic attribute.
     **/
    public function getAttribute($attributeName) {
        $child = $this->_getAttributeElement($attributeName);
        if ($child == null) {
            return null;
        } else {
            return $child->nodeValue;
        }
    }

    /**
     * Get the type of a named generic attribute.
     *
     * @param string $attributeName The generic attribute name.
     * @return string The type of the generic attribute.
     **/
    public function getAttributeType($attributeName) {
        $child = $this->_getAttributeElement($attributeName);
        if ($child == null) {
            return null;
        } else {
            return $child->getAttribute('type');
        }
    }

    /**
     * Get the unit of a named generic attribute.
     *
     * @param string $attributeName The generic attribute name.
     * @return string The unit of the generic attribute.
     **/
    public function getAttributeUnit($attributeName) {
        $child = $this->_getAttributeElement($attributeName);
        if ($child == null) {
            return null;
        } else {
            return $child->getAttribute('unit');
        }
    }

    /**
     * Create a generic attribute DOM Element.
     *
     * @param string $value The generic attribute value.
     * @param string $attributeName The generic attribute name.
     * @param string $attributeType The generic attribute type.
     * @param string $unit The generic attribute units.
     * @return DOMElement The element (with no parent) that was created.
     **/
    public function _createAttribute($value, $attributeName, $attributeType=null, $unit=null) {
        $el = $this->create(_GSC_Tags::$attribute, $value);
        $el->setAttribute('name', $attributeName);

        if ($attributeType != null) {
            $el->setAttribute('type', $attributeType);
        }

        if ($unit != null) {
            $el->setAttribute('unit', $unit);
        }

        return $el;
    }

    /**
     * Set the value of a named generic attribute.
     *
     * @param string $value The generic attribute value.
     * @param string $attributeName The generic attribute name.
     * @param string $attributeType The generic attribute type.
     * @param string $unit The generic attribute units.
     * @return DOMElement The element that was changed.
     **/
    public function setAttribute($value, $attributeName, $attributeType=null, $unit=null) {
        $el = $this->_createAttribute(
            $value,
            $attributeName,
            $attributeType,
            $unit
        );
        $this->model->appendChild($el);
        return $el;
    }

    /**
     * Get a list of all named generic groups.
     *
     * @return DOMElement DOM Element containing list of generic groups.
     **/
    public function getGroups() {
        $groupTag = _GSC_Tags::$group;
        return $this->model->getElementsByTagNameNS($groupTag[0], $groupTag[1]);
    }

    /**
     * Get the named generic group.
     *
     * @param string $groupName The generic group name.
     * @return DOMElement DOM Element of specific attribute in the case of
     *                    a match, else null.
     **/
    public function getGroup($groupName) {
        $groups = $this->getGroups();
        $count = $groups->length;

        for($pos=0; $pos<$count; $pos++) {
            $child = $groups->item($pos);
            if ($child->getAttribute('name') == $groupName) {
                return $child;
            }
        }
        return null;
    }

    /**
     * Set the value of a named generic group.
     *
     * @param string $groupName The generic group name.
     * @param array $attributes The list of generic attributes in the group.
     * @return DOMElement The element that was changed.
     **/
    public function setGroup($groupName, $attributes) {
        $group = $this->getGroup($groupName);
        if ($group == null) {
            $group = $this->create(_GSC_Tags::$group);
            $group->setAttribute('name', $groupName);
            $this->model->appendChild($group);
        }
        $this->deleteAll(_GSC_Tags::$attribute, $group);

        foreach ($attributes as $attribute) {
            $group->appendChild($attribute);
        }
    }

    /**
     * Get the performance datapoints.
     *
     * @return DOMNodeList The list of datapoints as DOM Elements.
     **/
    public function getDatapoints() {
        $performance = $this->getFirst(_GSC_Tags::$performance);
        return $this->getAll(_GSC_Tags::$datapoint, $performance);
    }

    /**
     * Get the datapoint clicks.
     *
     * @param DOMElement $datapoint The DOM Element containing the datapoint.
     * @return string The datapoint clicks.
     **/
    public function getDatapointClicks($datapoint) {
        return $datapoint->getAttribute('clicks');
    }

    /**
     * Get the datapoint date.
     *
     * @param DOMElement $datapoint The DOM Element containing the datapoint.
     * @return string The datapoint date.
     **/
    public function getDatapointDate($datapoint) {
        return $datapoint->getAttribute('date');
    }

    /**
     * Get the datapoint paid clicks.
     *
     * @param DOMElement $datapoint The DOM Element containing the datapoint.
     * @return string The datapoint paid clicks.
     **/
    public function getDatapointPaidClicks($datapoint) {
        return $datapoint->getAttribute('paid_clicks');
    }

    /**
     * Get the warnings.
     *
     * @return DOMNodeList The list of warnings as DOM Elements.
     **/
    public function getWarnings() {
        $appControl = $this->getFirst(_GSC_Tags::$control);
        $warnings = $this->getFirst(_GSC_Tags::$warnings, $appControl);
        return $this->getAll(_GSC_Tags::$warning, $warnings);
    }

    /**
     * Get the warning code.
     *
     * @param DOMElement $warning The DOM Element containing the warning.
     * @return string The warning code.
     **/
    public function getWarningCode($warning) {
        return $this->getFirstValue(_GSC_Tags::$warningCode, $warning);
    }

    /**
     * Get the warning domain.
     *
     * @param DOMElement $warning The DOM Element containing the warning.
     * @return string The warning domain.
     **/
    public function getWarningDomain($warning) {
        return $this->getFirstValue(_GSC_Tags::$warningDomain, $warning);
    }

    /**
     * Get the warning location.
     *
     * @param DOMElement $warning The DOM Element containing the warning.
     * @return string The warning location.
     **/
    public function getWarningLocation($warning) {
        return $this->getFirstValue(_GSC_Tags::$warningLocation, $warning);
    }

    /**
     * Get the warning message.
     *
     * @param DOMElement $warning The DOM Element containing the warning.
     * @return string The warning message.
     **/
    public function getWarningMessage($warning) {
        return $this->getFirstValue(_GSC_Tags::$message, $warning);
    }

    /**
     * Get the price of the product.
     *
     * @return string The price of the product.
     **/
    public function getPrice() {
        return $this->getFirstValue(_GSC_Tags::$price);
    }

    /**
     * Get the price currency of the product.
     *
     * @return string The price currency of the product.
     **/
    public function getPriceUnit() {
        $el = $this->getFirst(_GSC_Tags::$price);
        return $el->getAttribute('unit');
    }

    /**
     * Set the price of the product.
     *
     * @param string $price The price to set.
     * @param string $unit The currency of the price to set.
     * @return DOMElement The element that was changed.
     **/
    public function setPrice($price, $unit) {
        $el = $this->setFirstValue(_GSC_Tags::$price, $price);
        $el->setAttribute('unit', $unit);
        return $el;
    }

    /**
     * Get the SKU of the product.
     *
     * @return string The SKU of the product.
     **/
    function getSKU() {
        return $this->getFirstValue(_GSC_Tags::$id);
    }

    /**
     * Set the SKU of the product.
     *
     * @param string $sku The SKU to set.
     * @return DOMElement The element that was changed.
     **/
    function setSKU($sku) {
        $this->setFirstValue(_GSC_Tags::$id, $sku);
    }

    /**
     * Get the target country of the product.
     *
     * @return string The target country of the product.
     **/
    function getTargetCountry() {
        return $this->getFirstValue(_GSC_Tags::$target_country);
    }

    /**
     * Set the target country of the product.
     *
     * @param string $country The target country to set.
     * @return DOMElement The element that was changed.
     **/
    function setTargetCountry($country) {
        return $this->setFirstValue(_GSC_Tags::$target_country, $country);
    }

    /**
     * Get the content language of the product.
     *
     * @return string The target country of the product.
     **/
    function getContentLanguage($language) {
        return $this->getFirstValue(_GSC_Tags::$content_language);
    }

    /**
     * Set the content language of the product.
     *
     * @param string $language The language to set.
     * @return DOMElement The element that was changed.
     **/
    function setContentLanguage($language) {
        return $this->setFirstValue(_GSC_Tags::$content_language, $language);
    }

    /**
     * Get the condition of the product.
     *
     * @return string The condition of the product.
     **/
    function getCondition() {
        return $this->getFirstValue(_GSC_Tags::$condition);
    }

    /**
     * Set the condition of the product.
     *
     * @param string $condition The condition to set ('new', 'used', 'refurbished').
     * @return DOMElement The element that was changed.
     **/
    function setCondition($condition) {
        return $this->setFirstValue(_GSC_Tags::$condition, $condition);
    }

    /**
     * Get the Expiration Date for the product.
     *
     * @return string The expiration date in YYYY-MM-DD.
     **/
    public function getExpirationDate() {
        return $this->getFirstValue(_GSC_Tags::$expiration_date);
    }

    /**
     * Set the Expiration Date for the product.
     *
     * @param string $date The date to set in YYYY-MM-DD format.
     * @return DOMElement The element that was changed.
     **/
    public function setExpirationDate($date) {
        return $this->setFirstValue(_GSC_Tags::$expiration_date, $date);
    }

    /**
     * Get the link for the product.
     *
     * @return string The link for the product.
     **/
    function getProductLink() {
        $el = $this->getLink('alternate');
        if ($el == null) {
            return '';
        }
        else {
            return $el->getAttribute('href');
        }
    }

    /**
     * Set the Link for the product.
     *
     * @param string $link The product link to add.
     * @return DOMElement The element that was changed or created.
     **/
    function setProductLink($link) {
        $el = $this->getLink('alternate');
        if ($el == null) {
            $el = $this->create(_GSC_Tags::$link);
            $el->setAttribute('rel', 'alternate');
            $el->setAttribute('type', 'text/html');
            $this->model->appendChild($el);
        }

        $el->setAttribute('href', $link);
    }

    /**
     * Get the adult status for this product.
     *
     * @return string The adult status of the product.
     **/
    function getAdult() {
        return $this->getFirstValue(_GSC_Tags::$adult);
    }

    /**
     * Set the adult status for the product.
     *
     * @param string $adult The adult status of the product: 'true' or 'false'.
     * @return DOMElement The element that was changed.
     **/
    function setAdult($adult) {
        return $this->setFirstValue(_GSC_Tags::$adult, $adult);
    }

    /**
     * Get the Adwords Grouping of the product.
     *
     * @return string The Adwords Grouping of the product.
     **/
    public function getAdwordsGrouping() {
        return $this->getFirstValue(_GSC_Tags::$adwords_grouping);
    }

    /**
     * Set the Adwords Grouping of the product.
     *
     * @param string $adwords_grouping The Adwords Grouping to set.
     * @return DOMElement The element that was changed.
     **/
    public function setAdwordsGrouping($adwords_grouping) {
        return $this->setFirstValue(_GSC_Tags::$adwords_grouping, $adwords_grouping);
    }

    /**
     * Get the Adwords Labels of the product.
     *
     * @return string The Adwords Label of the product.
     **/
    public function getAdwordsLabels() {
        return $this->getFirstValue(_GSC_Tags::$adwords_labels);
    }

    /**
     * Set the Adwords Labels of the product.
     *
     * @param string $adwords_labels The Adwords Labels to set.
     * @return DOMElement The element that was changed.
     **/
    public function setAdwordsLabels($adwords_labels) {
        return $this->setFirstValue(_GSC_Tags::$adwords_labels, $adwords_labels);
    }

    /**
     * Get the Adwords Query Parameter of the product.
     *
     * @return string The Adwords Query Parameter of the product.
     **/
    public function getAdwordsQueryparam() {
        return $this->getFirstValue(_GSC_Tags::$adwords_queryparam);
    }

    /**
     * Set the Adwords Query Parameter of the product.
     *
     * @param string $adwords_queryparam The Adwords Query Parameter to set.
     * @return DOMElement The element that was changed.
     **/
    public function setAdwordsQueryParam($adwords_queryparam) {
        return $this->setFirstValue(_GSC_Tags::$adwords_queryparam, $adwords_queryparam);
    }

    /**
     * Get the Adwords Redirect of the product.
     *
     * @return string The Adwords Redirect of the product.
     **/
    public function getAdwordsRedirect() {
        return $this->getFirstValue(_GSC_Tags::$adwords_redirect);
    }

    /**
     * Set the Adwords Redirect of the product.
     *
     * @param string $adwords_redirect The Adwords Redirect to set.
     * @return DOMElement The element that was changed.
     **/
    public function setAdwordsRedirect($adwords_redirect) {
        return $this->setFirstValue(_GSC_Tags::$adwords_redirect, $adwords_redirect);
    }

    /**
     * Get the age group of the product.
     *
     * @return string The Age Group of the product.
     **/
    public function getAgeGroup() {
        return $this->getFirstValue(_GSC_Tags::$age_group);
    }

    /**
     * Set the age group of the product.
     *
     * @param string $age_group The age group to set.
     * @return DOMElement The element that was changed.
     **/
    public function setAgeGroup($age_group) {
        return $this->setFirstValue(_GSC_Tags::$age_group, $age_group);
    }

    /**
     * Get the author of the product.
     *
     * @return string The Author of the product.
     **/
    public function getAuthor() {
        return $this->getFirstValue(_GSC_Tags::$author);
    }

    /**
     * Set the author of the product.
     *
     * @param string $author The author to set.
     * @return DOMElement The element that was changed.
     **/
    public function setAuthor($author) {
        return $this->setFirstValue(_GSC_Tags::$author, $author);
    }

    /**
     * Get the brand of the product.
     *
     * @return string The brand of the product.
     **/
    public function getBrand() {
        return $this->getFirstValue(_GSC_Tags::$brand);
    }

    /**
     * Set the brand of the product.
     *
     * @param string $brand the brand to set.
     * @return DOMElement The element that was changed.
     **/
    public function setBrand($brand) {
        return $this->setFirstValue(_GSC_Tags::$brand, $brand);
    }

    /**
     * Get the availability of the product.
     *
     * @return string The availability of the product.
     **/
    public function getAvailability() {
        return $this->getFirstValue(_GSC_Tags::$availability);
    }

    /**
     * Set the availability of the product.
     *
     * @param string $availability the availability to set.
     * @return DOMElement The element that was changed.
     **/
    public function setAvailability($availability) {
        return $this->setFirstValue(_GSC_Tags::$availability, $availability);
    }

    /**
     * Get the color of the product.
     *
     * @return string The color of the product.
     **/
    public function getColor() {
        return $this->getFirstValue(_GSC_Tags::$color);
    }

    /**
     * Set the color of the product.
     *
     * @param string $color The color to set.
     * @return DOMElement The element that was changed.
     **/
    public function setColor($color) {
        return $this->setFirstValue(_GSC_Tags::$color, $color);
    }

    /**
     * Get the edition of the product.
     *
     * @return string The edition of the product.
     **/
    public function getEdition() {
        return $this->getFirstValue(_GSC_Tags::$edition);
    }

    /**
     * Set the edition of the product.
     *
     * @param string $edition The edition to set.
     * @return DOMElement The element that was changed.
     **/
    public function setEdition($edition) {
        return $this->setFirstValue(_GSC_Tags::$edition, $edition);
    }

    /**
     * Get the featured status of the product.
     *
     * @return string Whether the product is featured.
     **/
    public function getFeaturedProduct() {
        return $this->getFirstValue(_GSC_Tags::$featured_product);
    }

    /**
     * Set the featured status of the product.
     *
     * @param string $featured_product The featured status to set.
     * @return DOMElement The element that was changed.
     **/
    public function setFeaturedProduct($featured_product) {
        return $this->setFirstValue(_GSC_Tags::$featured_product, $featured_product);
    }

    /**
     * Get the genre of the product.
     *
     * @return string The genre of the product.
     **/
    public function getGenre() {
        return $this->getFirstValue(_GSC_Tags::$genre);
    }

    /**
     * Set the genre of the product.
     *
     * @param string $genre the genre to set.
     * @return DOMElement The element that was changed.
     **/
    public function setGenre($genre) {
        return $this->setFirstValue(_GSC_Tags::$genre, $genre);
    }

    /**
     * Get the manufacturer of the product.
     *
     * @return string The manufacturer of the product.
     **/
    public function getManufacturer() {
        return $this->getFirstValue(_GSC_Tags::$manufacturer);
    }

    /**
     * Set the manufacturer of the product.
     *
     * @param string $manufacturer The manufacturer to set.
     * @return DOMElement The element that was changed.
     **/
    public function setManufacturer($manufacturer) {
        return $this->setFirstValue(_GSC_Tags::$manufacturer, $manufacturer);
    }

    /**
     * Get the manufacturer's part number.
     *
     * @return string The manufacturer's part number.
     **/
    public function getMpn() {
        return $this->getFirstValue(_GSC_Tags::$mpn);
    }

    /**
     * Set the manufacturer's part number.
     *
     * @param $mpn The manufacturer's part number to set.
     * @return DOMElement The element that was changed.
     **/
    public function setMpn($mpn) {
        return $this->setFirstValue(_GSC_Tags::$mpn, $mpn);
    }

    /**
     * Get the online only status of the product.
     *
     * @return string The online only status of the product.
     **/
    public function getOnlineOnly() {
        return $this->getFirstValue(_GSC_Tags::$online_only);
    }

    /**
     * Set the online only status of the product.
     *
     * @param string $online_only The online only value to set.
     * @return DOMElement The element that was changed.
     **/
    public function setOnlineOnly($online_only) {
        return $this->setFirstValue(_GSC_Tags::$online_only, $online_only);
    }

    /**
     * Get the GTIN of the product.
     *
     * @return string The GTIN of the product.
     **/
    public function getGtin() {
        return $this->getFirstValue(_GSC_Tags::$gtin);
    }

    /**
     * Set the GTIN of the product.
     *
     * @param string $gtin The GTIN to set.
     * @return DOMElement The element that was changed.
     **/
    public function setGtin($gtin) {
        return $this->setFirstValue(_GSC_Tags::$gtin, $gtin);
    }

    /**
     * Get the product type.
     *
     * @return string The product type.
     **/
    public function getProductType() {
        return $this->getFirstValue(_GSC_Tags::$product_type);
    }

    /**
     * Set the product type.
     *
     * @param string $product_type The product type to set.
     * @return DOMElement The element that was changed.
     **/
    public function setProductType($product_type) {
        return $this->setFirstValue(_GSC_Tags::$product_type, $product_type);
    }

    /**
     * Get the product review average.
     *
     * @return string The product review average.
     **/
    public function getProductReviewAverage() {
        return $this->getFirstValue(_GSC_Tags::$product_review_average);
    }

    /**
     * Set the product review average.
     *
     * @param string $product_review_average The product review average to set.
     * @return DOMElement The element that was changed.
     **/
    public function setProductReviewAverage($product_review_average) {
        return $this->setFirstValue(_GSC_Tags::$product_review_average, $product_review_average);
    }

    /**
     * Get the product review count.
     *
     * @return string The product review count.
     **/
    public function getProductReviewCount() {
        return $this->getFirstValue(_GSC_Tags::$product_review_count);
    }

    /**
     * Set the product review count.
     *
     * @param string $product_review_count The product review count to set.
     * @return DOMElement The element that was changed.
     **/
    public function setProductReviewCount($product_review_count) {
        return $this->setFirstValue(_GSC_Tags::$product_review_count, $product_review_count);
    }

    /**
     * Get the quantity (inventory) of the product.
     *
     * @return string The quantity of the product.
     **/
    public function getQuantity() {
        return $this->getFirstValue(_GSC_Tags::$quantity);
    }

    /**
     * Set the quantity (inventory) of the product.
     *
     * @param string $quantity The quantity to set.
     * @return DOMElement The element that was changed.
     **/
    public function setQuantity($quantity) {
        return $this->setFirstValue(_GSC_Tags::$quantity, $quantity);
    }

    /**
     * Get the shipping weight of the product.
     *
     * @return string The shipping weight of the product.
     **/
    public function getShippingWeight() {
        return $this->getFirstValue(_GSC_Tags::$shipping_weight);
    }

    /**
     * Get the shipping weight unit of the product.
     *
     * @return string The shipping weight unit of the product.
     **/
    public function getShippingWeightUnit() {
        $el = $this->getFirst(_GSC_Tags::$shipping_weight);
        return $el->getAttribute('unit');
    }

    /**
     * Set the shipping weight of the product.
     *
     * @param string $shipping_weight The shipping weight to set.
     * @param string $unit The unit of the weight to set. Defaults to null.
     * @return DOMElement The element that was changed.
     **/
    public function setShippingWeight($shipping_weight, $unit=null) {
        $el = $this->setFirstValue(_GSC_Tags::$shipping_weight, $shipping_weight);
        if ($unit != null) {
            // In the name of backwards compatibility
            $el->setAttribute('unit', $unit);
            return $el;
        }
    }

    /**
     * Get the year of the product.
     *
     * @return string The year of the product.
     **/
    public function getYear() {
        return $this->getFirstValue(_GSC_Tags::$year);
    }

    /**
     * Set the year of the product.
     *
     * @param string $year The year to set.
     * @return DOMElement The element that was changed.
     **/
    public function setYear($year) {
        return $this->setFirstValue(_GSC_Tags::$year, $year);
    }

    /**
     * Get the image link.
     *
     * @return string The link to the main image for the product.
     **/
    public function getImageLink() {
        return $this->getFirstValue(_GSC_Tags::$image_link);
    }

    /**
     * Set the image link.
     *
     * @param string $image_link The image link to set.
     * @return DOMElement The element that was changed.
     **/
    public function setImageLink($image_link) {
        return $this->setFirstValue(_GSC_Tags::$image_link, $image_link);
    }

    /**
     * Get the channel of the product.
     *
     * @return string The channel of the product.
     **/
    public function getChannel() {
        return $this->getFirstValue(_GSC_Tags::$channel);
    }

    /**
     * Set the channel of the product.
     *
     * @param string $channel The channel to set.
     * @return DOMElement The element that was changed.
     **/
    public function setChannel($channel) {
        return $this->setFirstValue(_GSC_Tags::$channel, $channel);
    }

    /**
     * Get the gender of the product.
     *
     * @return string The gender of the product.
     **/
    public function getGender() {
        return $this->getFirstValue(_GSC_Tags::$gender);
    }

    /**
     * Set the gender of the product.
     *
     * @param string $gender The gender to set.
     * @return DOMElement The element that was changed.
     **/
    public function setGender($gender) {
        return $this->setFirstValue(_GSC_Tags::$gender, $gender);
    }

    /**
     * Get the item group id of the product.
     *
     * @return string The item group id of the product.
     **/
    public function getItemGroupId() {
        return $this->getFirstValue(_GSC_Tags::$item_group_id);
    }

    /**
     * Set the item group id of the product.
     *
     * @param string $item_group_id The item group id to set.
     * @return DOMElement The element that was changed.
     **/
    public function setItemGroupId($item_group_id) {
        return $this->setFirstValue(_GSC_Tags::$item_group_id, $item_group_id);
    }

    /**
     * Get the google product category of the product.
     *
     * @return string The google product category of the product.
     **/
    public function getGoogleProductCategory() {
        return $this->getFirstValue(_GSC_Tags::$google_product_category);
    }

    /**
     * Set the google product category of the product.
     *
     * @param string $google_product_category The google product category to set.
     * @return DOMElement The element that was changed.
     **/
    public function setGoogleProductCategory($google_product_category) {
        return $this->setFirstValue(_GSC_Tags::$google_product_category, $google_product_category);
    }

    /**
     * Get the material of the product.
     *
     * @return string The material of the product.
     **/
    public function getMaterial() {
        return $this->getFirstValue(_GSC_Tags::$material);
    }

    /**
     * Set the material of the product.
     *
     * @param string $material The material to set.
     * @return DOMElement The element that was changed.
     **/
    public function setMaterial($material) {
        return $this->setFirstValue(_GSC_Tags::$material, $material);
    }

    /**
     * Get the pattern of the product.
     *
     * @return string The pattern of the product.
     **/
    public function getPattern() {
        return $this->getFirstValue(_GSC_Tags::$pattern);
    }

    /**
     * Set the pattern of the product.
     *
     * @param string $pattern The pattern to set.
     * @return DOMElement The element that was changed.
     **/
    public function setPattern($pattern) {
        return $this->setFirstValue(_GSC_Tags::$pattern, $pattern);
    }

    /**
     * Get whether identifier exists or not.
     *
     * @return DOMElement The element that was changed.
     **/
    public function getIdentifierExists() {
        return $this->getFirstValue(_GSC_Tags::$identifier_exists);
    }

    /**
     * Set whether identifier exists or not..
     *
     * @param string $value 'true' or 'false'.
     * @return DOMElement The element that was changed.
     **/
    public function setIdentifierExists($value) {
        return $this->setFirstValue(_GSC_Tags::$identifier_exists, $value);
    }

    /**
     * Get the unit pricing base measure.
     *
     * @return string The unit pricing base measure.
     **/
    public function getUnitPricingMeasure() {
        return $this->getFirstValue(_GSC_Tags::$unit_pricing_measure);
    }

    /**
     * Get Get the unit of the unit pricing base measure.
     *
     * @return string The unit of the pricing base measure.
     **/
    public function getUnitPricingMeasureUnit() {
        $el = $this->getFirst(_GSC_Tags::$unit_pricing_measure);
        return $el->getAttribute('unit');
    }

    /**
     * Set the unit pricing measure.
     *
     * @param string $value The unit pricing measure..
     * @return DOMElement The element that was changed.
     **/
    public function setUnitPricingMeasure($value, $unit) {
        $el = $this->setFirstValue(_GSC_Tags::$unit_pricing_measure, $value);
        $el->setAttribute('unit', $unit);
        return $el;
    }

    /**
     * Get unit pricing base measure.
     *
     * @return string The unit pricing base measure.
     **/
    public function getUnitPricingBaseMeasure() {
        return $this->getFirstValue(_GSC_Tags::$unit_pricing_base_measure);
    }

    /**
     * Get the unit of the unit pricing base measure.
     *
     * @return string The unit of the pricing base measure.
     **/
    public function getUnitPricingBaseMeasureUnit() {
        $el = $this->getFirst(_GSC_Tags::$unit_pricing_base_measure);
        return $el->getAttribute('unit');
    }

    /**
     * Set unit pricing base measure.
     *
     * @param string $value The unit pricing base measure..
     * @return DOMElement The element that was changed.
     **/
    public function setUnitPricingBaseMeasure($value, $unit) {
        $el = $this->setFirstValue(_GSC_Tags::$unit_pricing_base_measure, $value);
        $el->setAttribute('unit', $unit);
        return $el;
    }

    /**
     * Get the energy efficiency class.
     *
     * @return string The energy efficiency class.
     **/
    public function getEnergyEfficiencyClass() {
        return $this->getFirstValue(_GSC_Tags::$energy_efficiency_class);
    }

    /**
     * Set the energy efficiency class.
     *
     * @param string $value The energy efficiency class.
     * @return DOMElement The element that was changed.
     **/
    public function setEnergyEfficiencyClass($value) {
        return $this->setFirstValue(_GSC_Tags::$energy_efficiency_class, $value);
    }

    /**
     * Get the merchant multipack quantity.
     *
     * @return DOMElement The element that was changed.
     **/
    public function getMultipack() {
        return $this->getFirstValue(_GSC_Tags::$multipack);
    }

    /**
     * Set the merchant multipack quantity.
     *
     * @param string $value The merchant multipack quantity.
     * @return DOMElement The element that was changed.
     **/
    public function setMultipack($value) {
        return $this->setFirstValue(_GSC_Tags::$multipack, $value);
    }

    /**
     * Add a shipping rule to the product.
     *
     * @param string $country The shipping country to set. Defaults to null.
     * @param string $region The shipping region to set. Defaults to null.
     * @param string $price The shipping price to set. Defaults to null.
     * @param string $priceUnit The shipping price currency to set. Defaults
     *                          to null.
     * @param string $service The shipping service to set. Defaults to null.
     * @return DOMElement The element that was created.
     **/
    function addShipping($country=null, $region=null, $price=null, $priceUnit=null, $service=null) {
        $el = $this->create(_GSC_Tags::$shipping);

        if ($country != null) {
            $this->setFirstValue(_GSC_Tags::$shipping_country, $country, $el);
        }

        if ($region != null) {
            $this->setFirstValue(_GSC_Tags::$shipping_region, $region, $el);
        }

        if ($price != null && $priceUnit != null) {
            $priceEl = $this->setFirstValue(_GSC_Tags::$shipping_price, $price, $el);
            $priceEl->setAttribute('unit', $priceUnit);
        }

        if ($service != null) {
            $this->setFirstValue(_GSC_Tags::$shipping_service, $service, $el);
        }

        $this->model->appendChild($el);
        return $el;
    }

    /**
     * Clear all the shipping rules from this product.
     *
     * @return void
     **/
    function clearAllShippings() {
        $this->deleteAll(_GSC_Tags::$shipping);
    }

    /**
     * Add a tax rule to the product.
     *
     * @param string $country The tax country to set. Defaults to null.
     * @param string $region The tax region to set. Defaults to null.
     * @param string $rate The tax rate to set. Defaults to null.
     * @param string $ship The tax on shipping to set. Defaults to null.
     * @return DOMElement The element that was created.
     **/
    function addTax($country=null, $region=null, $rate=null, $ship=null) {
        $el = $this->create(_GSC_Tags::$tax);

        if ($country != null) {
            $this->setFirstValue(_GSC_Tags::$tax_country, $country, $el);
        }

        if ($region != null) {
            $this->setFirstValue(_GSC_Tags::$tax_region, $region, $el);
        }

        if ($rate != null) {
            $this->setFirstValue(_GSC_Tags::$tax_rate, $rate, $el);
        }

        if ($ship != null) {
            $this->setFirstValue(_GSC_Tags::$tax_ship, $ship, $el);
        }

        $this->model->appendChild($el);
        return $el;
    }

    /**
     * Clear all the tax rules from this product.
     *
     * @return void
     **/
    function clearAllTaxes() {
        $this->deleteAll(_GSC_Tags::$tax);
    }

    /**
     * Get the disapproved status of the item.
     *
     * @return DOMElement The disapproved status of the item.
     **/
    function getDisapproved() {
        return $this->getFirstValue(_GSC_Tags::$disapproved);
    }

    /**
     * Add a required destination to the product.
     *
     * @param string $destination The destination to add.
     * @return DOMElement The element that was created.
     **/
    function addRequiredDestination($destination) {
        $el = $this->getCreateFirst(_GSC_Tags::$control);
        $child = $this->create(_GSC_Tags::$required_destination);
        $child->setAttribute('dest', $destination);
        $el->appendChild($child);
        return $child;
    }

    /**
     * Add a validate destination to the product.
     *
     * @param string $destination The destination to add.
     * @return DOMElement The element that was created.
     **/
    function addValidateDestination($destination) {
        $el = $this->getCreateFirst(_GSC_Tags::$control);
        $child = $this->create(_GSC_Tags::$validate_destination);
        $child->setAttribute('dest', $destination);
        $el->appendChild($child);
        return $child;
    }

    /**
     * Add an excluded destination to the product.
     *
     * @param string $destination The destination to add.
     * @return DOMElement The element that was created.
     **/
    function addExcludedDestination($destination) {
        $el = $this->getCreateFirst(_GSC_Tags::$control);
        $child = $this->create(_GSC_Tags::$excluded_destination);
        $child->setAttribute('dest', $destination);
        $el->appendChild($child);
        return $child;
    }

    /**
     * Clear all the destinations from this product.
     *
     * @return void
     **/
    function clearAllDestinations() {
        $this->deleteAll(_GSC_Tags::$control);
    }

    /**
     * Get the status of insertion into a destination.
     *
     * @param string $destination The destination to be checked.
     * @return string The status of insertion into a destination.
     **/
    function getDestinationStatus($destination) {
        $control = $this->getFirst(_GSC_Tags::$control);

        $statuses = $this->getAll(_GSC_Tags::$destinationStatus, $control);
        $count = $statuses->length;
        for($pos=0; $pos<$count; $pos++) {
            $child = $statuses->item($pos);
            if ($child->getAttribute('dest') == $destination) {
                return $child->getAttribute('status');
            }
        }
        return '';
    }

    /**
     * Add an additional image link to the product.
     *
     * @param string $link The link to add.
     * @return DOMElement The element that was created.
     **/
    function addAdditionalImageLink($link) {
        $el = $this->create(_GSC_Tags::$additional_image_link, $link);
        $this->model->appendChild($el);
        return $el;
    }

    /**
     * Clear all the additional image links from this product.
     *
     * @return void
     **/
    function clearAllAdditionalImageLinks() {
        $this->deleteAll(_GSC_Tags::$additional_image_link);
    }

    /**
     * Add a feature to the product.
     *
     * @param string $feature The feature to add.
     * @return DOMElement The element that was created.
     **/
    function addFeature($feature) {
        $el = $this->create(_GSC_Tags::$feature, $feature);
        $this->model->appendChild($el);
        return $el;
    }

    /**
     * Clear all the features from this product.
     *
     * @return void
     **/
    function clearAllFeatures() {
        $this->deleteAll(_GSC_Tags::$feature);
    }

    /**
     * Add a size to the product.
     *
     * @param string $size The size to add.
     * @return DOMElement The element that was created.
     **/
    function addSize($size) {
        $el = $this->create(_GSC_Tags::$size, $size);
        $this->model->appendChild($el);
        return $el;
    }

    /**
     * Clear all the sizes from this product.
     *
     * @return void
     **/
    function clearAllSizes() {
        $this->deleteAll(_GSC_Tags::$size);
    }

    /**
     * Get the content tag containing batch errors.
     *
     * @return DOMElement The content tag containing batch errors. If
     *                    no matching tag is found, returns null.
     **/
    function _getContentErrorTag() {
        $errorType = 'application/vnd.google.gdata.error+xml';

        $list = $this->getAll(_GSC_Tags::$content);
        $count = $list->length;
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            if ($child->getAttribute('type') == $errorType) {
                return $child;
            }
        }
        return null;
    }

    /**
     * Get the errors element from a batch entry.
     *
     * @return GSC_Errors The errors element from a batch entry. If
     *                    no matching tag is found, returns null.
     **/
    function getErrorsFromBatch() {
        $content = $this->_getContentErrorTag();
        if ($content == null) {
            return null;
        }

        $errors = $this->getFirst(_GSC_Tags::$errors, $content);
        return new GSC_Errors($this->doc, $errors);
    }

    /**
     * Create the initial model when none is provided.
     *
     * @return DOMElement The model created.
     **/
    public function createModel() {
        $s = '<entry '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:scp="http://schemas.google.com/structuredcontent/2009/products" '.
             'xmlns:batch="http://schemas.google.com/gdata/batch" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_ProductList
 *
 * @package GShoppingContent
 **/
class GSC_ProductList extends _GSC_AtomElement {

    /**
     * Add a product to this list.
     *
     * This method imports the DOM elements.
     *
     * @param GSC_Product $product The product to add to this list.
     * @return void
     **/
    public function addEntry($product) {
        $clone = $this->doc->importNode($product->model, true);
        $this->model->appendChild($clone);
    }

    /**
     * Get the list of products.
     *
     * @return array List of GSC_Products from the feed.
     **/
    public function getProducts() {
        $list = $this->getAll(_GSC_Tags::$entry);
        $count = $list->length;
        $products = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $product = new GSC_Product($this->doc, $child);
            array_push($products, $product);
        }
        return $products;
    }

    /**
     * Get a specified query param value.
     *
     * @param string $href The link to be parsed.
     * @param string $desiredKey The key to be parsed from the query parameters.
     * @return string The query parameter if it is contained in the link,
     *                else the empty string.
     **/
    function _parseQueryParam($href, $desiredKey) {
        if (substr_count($href, '?') != 1) {
            return '';
        }

        list($throwAway, $queryParams) = explode('?', $href, 2);
        $params = array($desiredKey => ''); // In case not found
        foreach (explode('&', $queryParams) as $param) {
            if ($param) {
                list($key, $val) = explode('=', $param, 2);
                $params[$key] = $val;
            }
        }
        return $params[$desiredKey];
    }

    /**
     * Get the start token from the feed (for paging).
     *
     * @return string The start token from the rel='next' link.
     **/
    public function getStartToken() {
        $el = $this->getLink('next');
        if ($el == null) {
            return '';
        }
        else {
            return $this->_parseQueryParam(
                $el->getAttribute('href'),
                'start-token'
            );
        }
    }

    /**
     * Get the request size.
     *
     * @return integer The request size in KB.
     **/
    public function getRequestSize() {
        $length = strlen($this->toXML());
        return (integer) ceil($length/1024);
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<feed '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:scp="http://schemas.google.com/structuredcontent/2009/products" '.
             'xmlns:batch="http://schemas.google.com/gdata/batch" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_ManagedAccount
 *
 * @package GShoppingContent
 **/
class GSC_ManagedAccount extends _GSC_AtomElement {

    /**
     * Get the account status.
     *
     * @return string The account status.
     **/
    function getAccountStatus() {
        return $this->getFirstValue(_GSC_Tags::$account_status);
    }

    /**
     * Get the adult content.
     *
     * @return string The adult content.
     **/
    function getAdultContent() {
        return $this->getFirstValue(_GSC_Tags::$adult_content);
    }

    /**
     * Set the adult content.
     *
     * @param string $adult_content The adult content.
     * @return DOMElement The element that was changed.
     **/
    public function setAdultContent($adult_content) {
        return $this->setFirstValue(_GSC_Tags::$adult_content, $adult_content);
    }

    /**
     * Get the internal id.
     *
     * @return string The internal id.
     **/
    function getInternalId() {
        return $this->getFirstValue(_GSC_Tags::$internal_id);
    }

    /**
     * Set the internal id.
     *
     * @param string $internal_id The internal id.
     * @return DOMElement The element that was changed.
     **/
    public function setInternalId($internal_id) {
        return $this->setFirstValue(_GSC_Tags::$internal_id, $internal_id);
    }

    /**
     * Get the reviews url.
     *
     * @return string The url with reviews.
     **/
    function getReviewsUrl() {
        return $this->getFirstValue(_GSC_Tags::$reviews_url);
    }

    /**
     * Set the review url
     *
     * @param string $reviews_url The url with reviews.
     * @return DOMElement The element that was changed.
     **/
    public function setReviewsUrl($reviews_url) {
        return $this->setFirstValue(_GSC_Tags::$reviews_url, $reviews_url);
    }

    /**
     * Get the link for the subaccount.
     *
     * @return string The link for the subaccount.
     **/
    function getAccountLink() {
        $el = $this->getLink('alternate');
        if ($el == null) {
            return '';
        }
        else {
            return $el->getAttribute('href');
        }
    }

    /**
     * Set the Link for the subaccount.
     *
     * @param string $link The subaccount link to add.
     * @return DOMElement The element that was changed or created.
     **/
    function setAccountLink($link) {
        $el = $this->getLink('alternate');
        if ($el == null) {
            $el = $this->create(_GSC_Tags::$link);
            $el->setAttribute('rel', 'alternate');
            $el->setAttribute('type', 'text/html');
            $this->model->appendChild($el);
        }

        $el->setAttribute('href', $link);
    }

    /**
     * Add an Adwords Account to the subaccount.
     *
     * @param string $adwordsAccountId The Adwords Account ID being added.
     * @param string $status The status (active or inactive) of the account.
     * @return DOMElement The element that was created.
     **/
    function addAdwordsAccount($adwordsAccountId, $status) {
        $el = $this->getCreateFirst(_GSC_Tags::$adwords_accounts);
        $child = $this->create(_GSC_Tags::$adwords_account, $adwordsAccountId);
        $child->setAttribute('status', $status);
        $el->appendChild($child);
        return $child;
    }

    /**
     * Clear all Adwords Accounts from the subaccount.
     *
     * First retrieves the sc:adwords_accounts element or creates one if it
     * doesn't exist and then removes all sc:adwords_account children.
     *
     * @return void
     **/
    function clearAllAdwordsAccounts() {
        $el = $this->getCreateFirst(_GSC_Tags::$adwords_accounts);
        $this->deleteAll(_GSC_Tags::$adwords_account, $el);
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<entry '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:gd="http://schemas.google.com/g/2005" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_ManagedAccountList
 *
 * @package GShoppingContent
 **/
class GSC_ManagedAccountList extends _GSC_AtomElement {

    /**
     * Get the list of accounts.
     *
     * @return array List of GSC_ManagedAccount from the feed.
     **/
    public function getAccounts() {
        $list = $this->getAll(_GSC_Tags::$entry);
        $count = $list->length;
        $accounts = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $account = new GSC_ManagedAccount($this->doc, $child);
            array_push($accounts, $account);
        }
        return $accounts;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<feed '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:gd="http://schemas.google.com/g/2005" '.
             'xmlns:openSearch="http://a9.com/-/spec/opensearch/1.1/" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}



/**
 * GSC_Datafeed
 *
 * @package GShoppingContent
 **/
class GSC_Datafeed extends _GSC_AtomElement {

    /**
     * Get the target country of the product.
     *
     * @return string The target country of the product.
     **/
    function getTargetCountry() {
        return $this->getFirstValue(_GSC_Tags::$target_country);
    }

    /**
     * Set the target country of the product.
     *
     * @param string $country The target country to set.
     * @return DOMElement The element that was changed.
     **/
    function setTargetCountry($country) {
        return $this->setFirstValue(_GSC_Tags::$target_country, $country);
    }

    /**
     * Get the content language of the product.
     *
     * @return string The target country of the product.
     **/
    function getContentLanguage() {
        return $this->getFirstValue(_GSC_Tags::$content_language);
    }

    /**
     * Set the content language of the product.
     *
     * @param string $language The language to set.
     * @return DOMElement The element that was changed.
     **/
    function setContentLanguage($language) {
        return $this->setFirstValue(_GSC_Tags::$content_language, $language);
    }

    /**
     * Get the feed file name.
     *
     * @return string The feed file name.
     **/
    function getFeedFileName() {
        return $this->getFirstValue(_GSC_Tags::$feed_file_name);
    }

    /**
     * Set the feed file name.
     *
     * @param string $feed_file_name The feed file name.
     * @return DOMElement The element that was changed.
     **/
    function setFeedFileName($feed_file_name) {
        return $this->setFirstValue(
            _GSC_Tags::$feed_file_name,
            $feed_file_name
        );
    }

    /**
     * Get the attribute language.
     *
     * @return string The attribute language.
     **/
    function getAttributeLanguage() {
        return $this->getFirstValue(_GSC_Tags::$attribute_language);
    }

    /**
     * Set the attribute language.
     *
     * @param string $attribute_language The attribute language.
     * @return DOMElement The element that was changed.
     **/
    function setAttributeLanguage($attribute_language) {
        return $this->setFirstValue(
            _GSC_Tags::$attribute_language,
            $attribute_language
        );
    }

    /**
     * Get the file format.
     *
     * @return string The file format.
     **/
    function getFileFormat() {
        $el = $this->getFirst(_GSC_Tags::$file_format);
        return $el->getAttribute('format');
    }

    /**
     * Set the file format.
     *
     * @param string $format The file format.
     * @return DOMElement The element that was changed.
     **/
    function setFileFormat($format) {
        $el = $this->getCreateFirst(_GSC_Tags::$file_format);
        $el->setAttribute('format', $format);
        return $el;
    }

    /**
     * Get the encoding.
     *
     * @return string The encoding.
     **/
    function getEncoding() {
        $format = $this->getFirst(_GSC_Tags::$file_format);
        if ($format == null) {
            return null;
        } else {
            return $this->getFirstValue(_GSC_Tags::$encoding, $format);
        }
    }

    /**
     * Set the encoding.
     *
     * @param string $encoding The encoding.
     * @return DOMElement The element that was changed.
     **/
    function setEncoding($encoding) {
        $format = $this->getCreateFirst(_GSC_Tags::$file_format);
        return $this->setFirstValue(_GSC_Tags::$encoding, $encoding, $format);
    }

    /**
     * Get the delimiter.
     *
     * @return string The delimiter.
     **/
    function getDelimiter() {
        $format = $this->getFirst(_GSC_Tags::$file_format);
        if ($format == null) {
            return null;
        } else {
            return $this->getFirstValue(_GSC_Tags::$delimiter, $format);
        }
    }

    /**
     * Set the delimiter.
     *
     * @param string $delimiter The delimiter.
     * @return DOMElement The element that was changed.
     **/
    function setDelimiter($delimiter) {
        $format = $this->getCreateFirst(_GSC_Tags::$file_format);
        return $this->setFirstValue(_GSC_Tags::$delimiter, $delimiter, $format);
    }

    /**
     * Get the "use quoted fields" value.
     *
     * @return string The "use quoted fields" value.
     **/
    function getUseQuotedFields() {
        $format = $this->getFirst(_GSC_Tags::$file_format);
        if ($format) {
            return $this->getFirstValue(_GSC_Tags::$use_quoted_fields, $format);
        } else {
            return null;
        }
    }

    /**
     * Set the "use quoted fields" value.
     *
     * @param string $use_quoted_fields The "use quoted fields" value.
     * @return DOMElement The element that was changed.
     **/
    function setUseQuotedFields($use_quoted_fields) {
        $format = $this->getCreateFirst(_GSC_Tags::$file_format);
        return $this->setFirstValue(
            _GSC_Tags::$use_quoted_fields,
            $use_quoted_fields,
            $format
        );
    }

    /**
     * Get the feed type.
     *
     * @return string The feed type.
     **/
    function getFeedType() {
        return $this->getFirstValue(_GSC_Tags::$feed_type);
    }

    /**
     * Get the processing status.
     *
     * @return string The processing status.
     **/
    function getProcessingStatus() {
        $el = $this->getFirst(_GSC_Tags::$processing_status);
        if ($el) {
            return $el->getAttribute('status');
        } else {
            return '';
        }
    }

    /**
     * Get the channel of the datafeed.
     *
     * @return string The channel of the datafeed.
     **/
    public function getChannel() {
        return $this->getFirstValue(_GSC_Tags::$channel);
    }

    /**
     * Set the channel of the datafeed.
     *
     * @param string $channel The channel to set.
     * @return DOMElement The element that was changed.
     **/
    public function setChannel($channel) {
        return $this->setFirstValue(_GSC_Tags::$channel, $channel);
    }

    /**
     * Add a feed destination to the product.
     *
     * @param string $destination The destination to add.
     * @param string $enabled The enabled status.
     * @return DOMElement The element that was created.
     **/
    function addFeedDestination($destination, $enabled) {
        $el = $this->create(_GSC_Tags::$feed_destination);
        $el->setAttribute('dest', $destination);
        $el->setAttribute('enabled', $enabled);
        $this->model->appendChild($el);
        return $el;
    }

    /**
     * Get the feed destinations.
     *
     * @return DOMNodeList A list of all matching DOMElements.
     **/
    function getFeedDestinations() {
        return $this->getAll(_GSC_Tags::$feed_destination);
    }

    /**
     * Get the destination from the feed destination element.
     *
     * @param DOMElement $destinationNode A DOM Element from
     *                                    getFeedDestinations.
     * @return string The destination of the element.
     **/
    function getFeedDestination($destinationNode) {
        return $destinationNode->getAttribute('dest');
    }

    /**
     * Get the enabled status from the feed destination element.
     *
     * @param DOMElement $destinationNode A DOM Element from
     *                                    getFeedDestinations.
     * @return string The enabled status of the element.
     **/
    function getFeedDestinationEnabled($destinationNode) {
        return $destinationNode->getAttribute('enabled');
    }

    /**
     * Clear all the destinations from this datafeed.
     *
     * @return void
     **/
    function clearAllFeedDestinations() {
        $this->deleteAll(_GSC_Tags::$feed_destination);
    }

    /**
     * Get the day of the month on which to fetch the file.
     *
     * @return string The day of the month.
     **/
    function getFetchDayOfMonth() {
        $fetch_schedule = $this->getFirst(_GSC_Tags::$fetchSchedule);
        if ($fetch_schedule == null) {
            return null;
        } else {
            return $this->getFirstValue(_GSC_Tags::$fetchDayOfMonth, $fetch_schedule);
        }
    }

    /**
     * Set the day of the month on which to fetch the file.
     *
     * @param string $day_of_month The day of the month.
     * @return DOMElement The element that was changed.
     **/
    function setFetchDayOfMonth($day_of_month) {
        $fetch_schedule = $this->getCreateFirst(_GSC_Tags::$fetchSchedule);
        return $this->setFirstValue(_GSC_Tags::$fetchDayOfMonth, $day_of_month, $fetch_schedule);
    }

    /**
     * Get the url to fetch the file from.
     *
     * @return string The url of the file.
     **/
    function getFetchUrl() {
        $fetch_schedule = $this->getFirst(_GSC_Tags::$fetchSchedule);
        if ($fetch_schedule == null) {
            return null;
        } else {
            return $this->getFirstValue(_GSC_Tags::$fetchUrl, $fetch_schedule);
        }
    }

    /**
     * Set the url to fetch the file from.
     *
     * @param string $fetch_url The url of the file.
     * @param string $username The username to use when accessing the URL.
     * @param string $password The password to use when accessing the URL.
     * @return DOMElement The element that was changed.
     **/
    function setFetchUrl($fetch_url, $username=null, $password=null) {
        $fetch_schedule = $this->getCreateFirst(_GSC_Tags::$fetchSchedule);
        $el = $this->setFirstValue(_GSC_Tags::$fetchUrl, $fetch_url, $fetch_schedule);

        if ($username != null) {
            $el->setAttribute("username", $username);
        }

        if ($password != null) {
            $el->setAttribute("password", $password);
        }

        return $el;
    }

    /**
     * Get the password to use when fetching the file.
     *
     * @return string The password.
     **/
    function getFetchPassword() {
        $fetch_schedule = $this->getFirst(_GSC_Tags::$fetchSchedule);
        if ($fetch_schedule == null) {
            return null;
        } else {
            $fetch_url = $this->getFirst(_GSC_Tags::$fetchUrl, $fetch_schedule);
            if ($fetch_url == null) {
                return null;
            } else {
                return $fetch_url->getAttribute('password');
            }
        }
    }

    /**
     * Get the username to use when fetching the file.
     *
     * @return string The username.
     **/
    function getFetchUsername() {
        $fetch_schedule = $this->getFirst(_GSC_Tags::$fetchSchedule);
        if ($fetch_schedule == null) {
            return null;
        } else {
            $fetch_url = $this->getFirst(_GSC_Tags::$fetchUrl, $fetch_schedule);
            if ($fetch_url == null) {
                return null;
            } else {
                return $fetch_url->getAttribute('username');
            }
        }
    }

    /**
     * Get the hour to fetch the file.
     *
     * @return string The hour to fetch the file.
     **/
    function getFetchHour() {
        $fetch_schedule = $this->getFirst(_GSC_Tags::$fetchSchedule);
        if ($fetch_schedule == null) {
            return null;
        } else {
            return $this->getFirstValue(_GSC_Tags::$fetchHour, $fetch_schedule);
        }
    }

    /**
     * Get the timezone in which the fetch time is specified.
     *
     * @return string The timezone.
     **/
    function getFetchTimezone() {
        $fetch_schedule = $this->getFirst(_GSC_Tags::$fetchSchedule);
        if ($fetch_schedule == null) {
            return null;
        } else {
            $hour = $this->getFirst(_GSC_Tags::$fetchHour, $fetch_schedule);
            if ($hour == null) {
                return null;
            } else {
                return $hour->getAttribute('hour');
            }
        }
    }

    /**
     * Set the hour to fetch the file.
     *
     * @param string $hour The hour to fetch the file.
     * @param string $timezone The timezone in which the hour is specified.
     * @return DOMElement The element that was changed.
     **/
    function setFetchHour($hour, $timezone=null) {
        $fetch_schedule = $this->getCreateFirst(_GSC_Tags::$fetchSchedule);
        $el = $this->setFirstValue(_GSC_Tags::$fetchHour, $hour, $fetch_schedule);

        if ($timezone != null) {
            $el->setAttribute("timezone", $timezone);
        }

        return $el;
    }

    /**
     * Get the weekday on which to fetch the file.
     *
     * @return string The weekday.
     **/
    function getFetchWeekday() {
        $fetch_schedule = $this->getFirst(_GSC_Tags::$fetchSchedule);
        if ($fetch_schedule == null) {
            return null;
        } else {
            return $this->getFirstValue(_GSC_Tags::$fetchWeekday, $fetch_schedule);
        }
    }

    /**
     * Set the weekday on which to fetch the file.
     *
     * @param string $weekday The weekday.
     * @return DOMElement The element that was changed.
     **/
    function setFetchWeekday($weekday) {
        $fetch_schedule = $this->getCreateFirst(_GSC_Tags::$fetchSchedule);
        return $this->setFirstValue(_GSC_Tags::$fetchWeekday, $weekday, $fetch_schedule);
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<entry '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_DatafeedList
 *
 * @package GShoppingContent
 **/
class GSC_DatafeedList extends _GSC_AtomElement {

    /**
     * Get the list of datafeeds.
     *
     * @return array List of GSC_Datafeed from the feed.
     **/
    public function getDatafeeds() {
        $list = $this->getAll(_GSC_Tags::$entry);
        $count = $list->length;
        $datafeeds = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $datafeed = new GSC_Datafeed($this->doc, $child);
            array_push($datafeeds, $datafeed);
        }
        return $datafeeds;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<feed '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:openSearch="http://a9.com/-/spec/opensearch/1.1/" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_User
 *
 * @package GShoppingContent
 **/
class GSC_User extends _GSC_AtomElement {
    /**
     * Get the admin status of the user.
     *
     * @return string The admin status of the user.
     **/
    function getAdmin() {
        return $this->getFirstValue(_GSC_Tags::$admin);
    }

    /**
     * Set the admin status of the user.
     *
     * @param string $admin The admin status to set.
     * @return DOMElement The element that was changed.
     **/
    function setAdmin($admin) {
        return $this->setFirstValue(_GSC_Tags::$admin, $admin);
    }

    /**
     * Add a permission to the user.
     *
     * @param string $permission The permission to add.
     * @param string $scope The scope of the permission.
     * @return DOMElement The element that was created.
     **/
    function addPermission($permission, $scope) {
        $el = $this->create(_GSC_Tags::$permission, $permission);
        $el->setAttribute('scope', $scope);
        $this->model->appendChild($el);
        return $el;
    }

    /**
     * Clear all the permissions from this user.
     *
     * @return void
     **/
    function clearAllPermissions() {
        $this->deleteAll(_GSC_Tags::$permission);
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<entry '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_UserList
 *
 * @package GShoppingContent
 **/
class GSC_UserList extends _GSC_AtomElement {

    /**
     * Get the list of users.
     *
     * @return array List of GSC_User from the feed.
     **/
    public function getUsers() {
        $list = $this->getAll(_GSC_Tags::$entry);
        $count = $list->length;
        $users = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $user = new GSC_User($this->doc, $child);
            array_push($users, $user);
        }
        return $users;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<feed '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:openSearch="http://a9.com/-/spec/opensearch/1.1/" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_InventoryEntry
 *
 * @package GShoppingContent
 **/
class GSC_InventoryEntry extends _GSC_AtomElement {

    /**
     * Get the availability of the inventory entry.
     *
     * @return string The availability of the inventory entry.
     **/
    public function getAvailability() {
        return $this->getFirstValue(_GSC_Tags::$availability);
    }

    /**
     * Set the availability of the inventory entry.
     *
     * @param string $availability the availability to set.
     * @return DOMElement The element that was changed.
     **/
    public function setAvailability($availability) {
        return $this->setFirstValue(_GSC_Tags::$availability, $availability);
    }

    /**
     * Get the price of the inventory entry.
     *
     * @return string The price of the inventory entry.
     **/
    public function getPrice() {
        return $this->getFirstValue(_GSC_Tags::$price);
    }

    /**
     * Get the price currency of the inventory entry.
     *
     * @return string The price currency of the inventory entry.
     **/
    public function getPriceUnit() {
        $el = $this->getFirst(_GSC_Tags::$price);
        return $el->getAttribute('unit');
    }

    /**
     * Set the price of the inventory entry.
     *
     * @param string $price The price to set.
     * @param string $unit The currency of the price to set.
     * @return DOMElement The element that was changed.
     **/
    public function setPrice($price, $unit) {
        $el = $this->setFirstValue(_GSC_Tags::$price, $price);
        $el->setAttribute('unit', $unit);
        return $el;
    }

    /**
     * Get the quantity (inventory) of the inventory entry.
     *
     * @return string The quantity of the inventory entry.
     **/
    public function getQuantity() {
        return $this->getFirstValue(_GSC_Tags::$quantity);
    }

    /**
     * Set the quantity (inventory) of the inventory entry.
     *
     * @param string $quantity The quantity to set.
     * @return DOMElement The element that was changed.
     **/
    public function setQuantity($quantity) {
        return $this->setFirstValue(_GSC_Tags::$quantity, $quantity);
    }

    /**
     * Get the sale price of the inventory entry.
     *
     * @return string The sale price of the inventory entry.
     **/
    public function getSalePrice() {
        return $this->getFirstValue(_GSC_Tags::$sale_price);
    }

    /**
     * Get the sale price currency of the inventory entry.
     *
     * @return string The sale price currency of the inventory entry.
     **/
    public function getSalePriceUnit() {
        $el = $this->getFirst(_GSC_Tags::$sale_price);
        return $el->getAttribute('unit');
    }

    /**
     * Set the sale price of the inventory entry.
     *
     * @param string $price The price to set.
     * @param string $unit The currency of the price to set.
     * @return DOMElement The element that was changed.
     **/
    public function setSalePrice($price, $unit) {
        $el = $this->setFirstValue(_GSC_Tags::$sale_price, $price);
        $el->setAttribute('unit', $unit);
        return $el;
    }

    /**
     * Get the Sale Price Effective Date for the inventory entry.
     *
     * @return string The sale price effective date in YYYY-MM-DD.
     **/
    public function getSalePriceEffectiveDate() {
        return $this->getFirstValue(_GSC_Tags::$sale_price_effective_date);
    }

    /**
     * Set the Sale Price Effective Date for the inventory entry.
     *
     * @param string $date The date to set in YYYY-MM-DD format.
     * @return DOMElement The element that was changed.
     **/
    public function setSalePriceEffectiveDate($date) {
        return $this->setFirstValue(_GSC_Tags::$sale_price_effective_date, $date);
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<entry '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:scp="http://schemas.google.com/structuredcontent/2009/products" '.
             'xmlns:batch="http://schemas.google.com/gdata/batch" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_InventoryEntryList
 *
 * @package GShoppingContent
 **/
class GSC_InventoryEntryList extends _GSC_AtomElement {

    /**
     * Add an inventory entry to this list.
     *
     * @param GSC_InventoryEntry $entry The entry to add to this list.
     * @return void
     **/
    public function addEntry($entry) {
        $clone = $this->doc->importNode($entry->model, true);
        $this->model->appendChild($clone);
    }

    /**
     * Get the list of inventory entries.
     *
     * @return array List of GSC_InventoryEntry from the feed.
     **/
    public function getInventoryEntries() {
        $list = $this->getAll(_GSC_Tags::$entry);
        $count = $list->length;
        $users = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $user = new GSC_InventoryEntry($this->doc, $child);
            array_push($users, $user);
        }
        return $users;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<feed '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:scp="http://schemas.google.com/structuredcontent/2009/products" '.
             'xmlns:batch="http://schemas.google.com/gdata/batch" '.
             'xmlns:openSearch="http://a9.com/-/spec/opensearch/1.1/" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_ExampleItem
 *
 * @package GShoppingContent
 **/
class GSC_ExampleItem extends _GSC_AtomElement {
    /**
     * Get the item id of the example item.
     *
     * @return string The item id of the example item.
     **/
    function getItemId() {
        return $this->getFirstValue(_GSC_Tags::$item_id);
    }

    /**
     * Get the link of the example item.
     *
     * @return string The link of the example item.
     **/
    function getLink() {
        return $this->getFirstValue(_GSC_Tags::$exampleItemLink);
    }

    /**
     * Get the title of the example item.
     *
     * @return string The title of the example item.
     **/
    function getTitle() {
        return $this->getFirstValue(_GSC_Tags::$exampleItemTitle);
    }

    /**
     * Get the submitted value of the example item.
     *
     * @return string The submitted value of the example item.
     **/
    function getSubmittedValue() {
        return $this->getFirstValue(_GSC_Tags::$submitted_value);
    }

    /**
     * Get the value of the example item from the landing page.
     *
     * @return string The value of the example item from the landing page.
     **/
    function getValueOnLandingPage() {
        return $this->getFirstValue(_GSC_Tags::$value_on_landing_page);
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<example_item '.
             'xmlns="http://schemas.google.com/structuredcontent/2009" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_Issue
 *
 * @package GShoppingContent
 **/
class GSC_Issue extends _GSC_AtomElement {
    /**
     * Get the ID of the issue.
     *
     * @return string The ID of the issue.
     **/
    function getId() {
        return $this->model->getAttribute('id');
    }

    /**
     * Get the last checked of the issue.
     *
     * @return string The last checked of the issue.
     **/
    function getLastChecked() {
        return $this->model->getAttribute('last_checked');
    }

    /**
     * Get the number of items of the issue.
     *
     * @return string The number of items of the issue.
     **/
    function getNumItems() {
        return $this->model->getAttribute('num_items');
    }

    /**
     * Get the offending term of the issue.
     *
     * @return string The offending term of the issue.
     **/
    function getOffendingTerm() {
        return $this->model->getAttribute('offending_term');
    }

    /**
     * Get the example items contained in the issue.
     *
     * @return array The example items contained in the issue. All elements
     *               in the list will be of type GSC_ExampleItem.
     **/
    function getExampleItems() {
        $list = $this->getAll(_GSC_Tags::$example_item);
        $count = $list->length;
        $exampleItems = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $exampleItem = new GSC_ExampleItem($this->doc, $child);
            array_push($exampleItems, $exampleItem);
        }
        return $exampleItems;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<issue '.
             'xmlns="http://schemas.google.com/structuredcontent/2009" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_IssueGroup
 *
 * @package GShoppingContent
 **/
class GSC_IssueGroup extends _GSC_AtomElement {
    /**
     * Get the ID of the issue group.
     *
     * @return string The ID of the issue group.
     **/
    function getId() {
        return $this->model->getAttribute('id');
    }

    /**
     * Get the country of the issue group.
     *
     * @return string The country of the issue group.
     **/
    function getCountry() {
        return $this->model->getAttribute('country');
    }

    /**
     * Get the issues contained in the issue group.
     *
     * @return array The issues contained in the issue group. All elements
     *               in the list will be of type GSC_Issue.
     **/
    function getIssues() {
        $list = $this->getAll(_GSC_Tags::$issue);
        $count = $list->length;
        $issues = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $issue = new GSC_Issue($this->doc, $child);
            array_push($issues, $issue);
        }
        return $issues;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<issue_group '.
             'xmlns="http://schemas.google.com/structuredcontent/2009" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_DataQualityEntry
 *
 * @package GShoppingContent
 **/
class GSC_DataQualityEntry extends _GSC_AtomElement {
    /**
     * Get the issue groups contained in the data quality entry.
     *
     * @return array The issue groups contained in the data quality entry. All
     *               elements in the list will be of type GSC_IssueGroup.
     **/
    function getIssueGroups() {
        $issueGroupsElement = $this->getFirst(_GSC_Tags::$issue_groups);
        $list = $this->getAll(_GSC_Tags::$issue_group, $issueGroupsElement);

        $count = $list->length;
        $issueGroups = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $issueGroup = new GSC_IssueGroup($this->doc, $child);
            array_push($issueGroups, $issueGroup);
        }
        return $issueGroups;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<entry '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_DataQualityFeed
 *
 * @package GShoppingContent
 **/
class GSC_DataQualityFeed extends _GSC_AtomElement {

    /**
     * Get the list of data quality entries.
     *
     * @return array The list of data quality entries.
     **/
    public function getDataQualityEntries() {
        $list = $this->getAll(_GSC_Tags::$entry);
        $count = $list->length;
        $entries = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $entry = new GSC_DataQualityEntry($this->doc, $child);
            array_push($entries, $entry);
        }
        return $entries;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<feed '.
             'xmlns="http://www.w3.org/2005/Atom" '.
             'xmlns:app="http://www.w3.org/2007/app" '.
             'xmlns:sc="http://schemas.google.com/structuredcontent/2009" '.
             'xmlns:openSearch="http://a9.com/-/spec/opensearch/1.1/" '.
             '/>';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_ErrorElement
 *
 * @package GShoppingContent
 **/
class GSC_ErrorElement extends _GSC_AtomElement {

    /**
     * Get the domain of the error.
     *
     * @return string The domain of the error.
     **/
    function getDomain() {
        return $this->getFirstValue(_GSC_Tags::$domain);
    }

    /**
     * Get the code of the error.
     *
     * @return string The code of the error.
     **/
    function getCode() {
        return $this->getFirstValue(_GSC_Tags::$code);
    }

    /**
     * Get the location of the error.
     *
     * @return string The location of the error.
     **/
    function getLocation() {
        return $this->getFirstValue(_GSC_Tags::$location);
    }

    /**
     * Get the location type of the error.
     *
     * @return string The location type of the error.
     **/
    public function getLocationType() {
        $el = $this->getFirst(_GSC_Tags::$location);
        if ($el) {
            return $el->getAttribute('type');
        } else {
            return '';
        }
    }

    /**
     * Get the internal reason of the error.
     *
     * @return string The internal reason of the error.
     **/
    function getInternalReason() {
        return $this->getFirstValue(_GSC_Tags::$internalReason);
    }

    /**
     * Get the debug info of the error.
     *
     * @return string The debug info of the error.
     **/
    function getDebugInfo() {
        return $this->getFirstValue(_GSC_Tags::$debugInfo);
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<error xmlns="http://schemas.google.com/g/2005" />';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}


/**
 * GSC_Errors
 *
 * @package GShoppingContent
 **/
class GSC_Errors extends _GSC_AtomElement {

    /**
     * Get the list of errors.
     *
     * @return array List of GSC_ErrorElement's from the feed.
     **/
    public function getErrors() {
        $list = $this->getAll(_GSC_Tags::$error);
        $count = $list->length;
        $errors = array();
        for($pos=0; $pos<$count; $pos++) {
            $child = $list->item($pos);
            $error = new GSC_ErrorElement($this->doc, $child);
            array_push($errors, $error);
        }
        return $errors;
    }

    /**
     * Create the default model for this element
     *
     * @return DOMElement The newly created element.
     **/
    public function createModel() {
        $s = '<errors xmlns="http://schemas.google.com/g/2005" />';
        $this->doc->loadXML($s);
        return $this->doc->documentElement;
    }
}

?>
