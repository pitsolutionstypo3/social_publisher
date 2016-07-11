<?php
error_reporting(E_ALL); 
session_start();


$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$pageURL = (@$_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
//$red_url= parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$red_url= $pageURL.$_SERVER['HTTP_HOST'] . $uri_parts[0];

if(isset($_GET['key']) && isset($_GET['secret']) && isset($_GET['xing_script']) ){
	$_SESSION['xing_key'] = $_GET['key'];
	$_SESSION['xing_secret'] = $_GET['secret'];		
	$_SESSION['red_xing_url'] =$red_url;
  $_SESSION['xing_script'] = $_GET['xing_script'];
  $_SESSION['xing_usa_id_ref'] = $_GET['xing_usa_id_ref'];

}
$key_session =$_SESSION['xing_key'];
$secret_session =$_SESSION['xing_secret'];

$redirect_session_url = urlencode($_SESSION['red_xing_url']);

$sec_array= unserialize(base64_decode($_SESSION['xing_script']));
$xing_usa_id_ref = $_SESSION['xing_usa_id_ref'];

/*XING.php*/
/**
 * oauth-php: Example OAuth client for accessing Xing profiles
 *
 * @author Rene Schmidt, based on Google Client example by BBG
 *
 *
 * The MIT License
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

include '../../../Packages/Libraries/Xing/library/OAuthStore.php';
include '../../../Packages/Libraries/Xing/library/OAuthRequester.php';

/**
 * Overloads some methods from OAuthRequester. They work now with Xing API.
 */
class XingOAuthRequester extends OAuthRequester
{

  /**
   * Request a request token from the site belonging to consumer_key
   *
   * @param string $consumer_key
   * @param int $usr_id
   * @param array $params (optional) extra arguments for when requesting the request token
   * @param string $method (optional) change the method of the request, defaults to POST (as it should be)
   * @param array $options (optional) options like name and token_ttl
   * @param array $curl_options  optional extra options for curl request
   * @exception OAuthException2 when no key could be fetched
   * @exception OAuthException2 when no server with consumer_key registered
   * @return array (authorize_uri, token)
   */
  static function requestRequestToken($consumer_key, $usr_id, $params = null, $method = 'POST', $options = array(), $curl_options = array())
  {
    OAuthRequestLogger::start();

    if (isset($options['token_ttl']) && is_numeric($options['token_ttl'])) {
      $params['xoauth_token_ttl'] = intval($options['token_ttl']);
    }

    $store = OAuthStore::instance();
    $r = $store->getServer($consumer_key, $usr_id);
    $uri = $r['request_token_uri'];

    $oauth = new OAuthRequester($uri, $method, $params);
    $oauth->sign($usr_id, $r, '', 'requestToken');
    $text = $oauth->curl_raw($curl_options);

    if (empty($text)) {
      throw new OAuthException2('No answer from the server "' . $uri . '" while requesting a request token');
    }
    $data = $oauth->curl_parse($text);
    if (!in_array((int)$data['code'], array(200, 201))) // patch for xing api
    {
      throw new OAuthException2('Unexpected result from the server "' . $uri . '" (' . $data['code'] . ') while requesting a request token');
    }
    $token = array();
    $params = explode('&', $data['body']);
    foreach ($params as $p)
    {
      @list($name, $value) = explode('=', $p, 2);
      $token[$name] = $oauth->urldecode($value);
    }

    if (!empty($token['oauth_token']) && !empty($token['oauth_token_secret'])) {
      $opts = array();
      if (isset($options['name'])) {
        $opts['name'] = $options['name'];
      }
      if (isset($token['xoauth_token_ttl'])) {
        $opts['token_ttl'] = $token['xoauth_token_ttl'];
      }
      $store->addServerToken($consumer_key, 'request', $token['oauth_token'], $token['oauth_token_secret'], $usr_id, $opts);
    }
    else
    {
      throw new OAuthException2('The server "' . $uri . '" did not return the oauth_token or the oauth_token_secret');
    }

    OAuthRequestLogger::flush();

    // Now we can direct a browser to the authorize_uri
    return array(
      'authorize_uri' => $r['authorize_uri'],
      'token' => $token['oauth_token']
    );
  }

  /**
   * Request an access token from the site belonging to consumer_key.
   * Before this we got an request token, now we want to exchange it for
   * an access token.
   *
   * @static
   * @param string consumer_key
   * @param string token
   * @param int usr_id    user requesting the access token
   * @param string method (optional) change the method of the request, defaults to POST (as it should be)
   * @param array options (optional) extra options for request, eg token_ttl
   * @param array curl_options  optional extra options for curl request
   * @return array (code=>http-code, headers=>http-headers, body=>body)
   * @throws OAuthException2 when no key could be fetched
   * @throws OAuthException2 when no server with consumer_key registered
   * @throws OAuthException2
   */
  static function requestAccessToken($consumer_key, $token, $usr_id, $method = 'POST', $options = array(), $curl_options = array())
  {
    OAuthRequestLogger::start();

    $store = OAuthStore::instance();
    $r = $store->getServerTokenSecrets($consumer_key, $token, 'request', $usr_id);
    $uri = $r['access_token_uri'];
    $token_name = $r['token_name'];

    // Delete the server request token, this one was for one use only
    $store->deleteServerToken($consumer_key, $r['token'], 0, true);

    // Try to exchange our request token for an access token
    $oauth = new OAuthRequester($uri, $method);

    if (isset($options['oauth_verifier'])) {
      $oauth->setParam('oauth_verifier', $options['oauth_verifier']);
    }
    if (isset($options['token_ttl']) && is_numeric($options['token_ttl'])) {
      $oauth->setParam('xoauth_token_ttl', intval($options['token_ttl']));
    }

    OAuthRequestLogger::setRequestObject($oauth);

    $oauth->sign($usr_id, $r, '', 'accessToken');
    $text = $oauth->curl_raw($curl_options);
    if (empty($text)) {
      throw new OAuthException2('No answer from the server "' . $uri . '" while requesting an access token');
    }
    $data = $oauth->curl_parse($text);

    if (!in_array((int)$data['code'], array(200, 201, 301, 302))) // patch for xing api
    {
      throw new OAuthException2('Unexpected result from the server "' . $uri . '" (' . $data['code'] . ') while requesting an access token');
    }

    $token = array();
    $params = explode('&', $data['body']);
    foreach ($params as $p)
    {
      @list($name, $value) = explode('=', $p, 2);
      $token[$oauth->urldecode($name)] = $oauth->urldecode($value);
    }

    if (!empty($token['oauth_token']) && !empty($token['oauth_token_secret'])) {
      $opts = array();
      $opts['name'] = $token_name;
      if (isset($token['xoauth_token_ttl'])) {
        $opts['token_ttl'] = $token['xoauth_token_ttl'];
      }
      $store->addServerToken($consumer_key, 'access', $token['oauth_token'], $token['oauth_token_secret'], $usr_id, $opts);
    }
    else
    {
      throw new OAuthException2('The server "' . $uri . '" did not return the oauth_token or the oauth_token_secret');
    }

    OAuthRequestLogger::flush();

    return $data; // patch for xing api
  }
}


/*XING.php*/



define("XING_CONSUMER_KEY", $key_session); // get it from https://dev.xing.com/
define("XING_CONSUMER_SECRET", $secret_session); // get it from https://dev.xing.com/
define("XING_CALLBACK_URL", $redirect_session_url);
define('OAUTH_TMP_DIR', "/tmp/"); // change to your local tmp dir

define("XING_OAUTH_HOST", "https://api.xing.com");
define("XING_REQUEST_TOKEN_URL", XING_OAUTH_HOST . "/v1/request_token");
define("XING_AUTHORIZE_URL", XING_OAUTH_HOST . "/v1/authorize");
define("XING_ACCESS_TOKEN_URL", XING_OAUTH_HOST . "/v1/access_token");

//  Init the OAuthStore
/*$options = array(
  'consumer_key' => XING_CONSUMER_KEY,
  'consumer_secret' => XING_CONSUMER_SECRET,
  'server_uri' => XING_OAUTH_HOST,
  'request_token_uri' => XING_REQUEST_TOKEN_URL,
  'authorize_uri' => XING_AUTHORIZE_URL,
  'access_token_uri' => XING_ACCESS_TOKEN_URL
);*/




/*New code*/
$options = array(
  'server' => $sec_array['host'],
  'username' => $sec_array['username'],
  'password' => $sec_array['password'],
  'database' => $sec_array['database']
  );

//OAuthStore::instance("MySQLi", $options);
$store = OAuthStore::instance("MySQLi", $options);
/*Added*/
$consumer_key = XING_CONSUMER_KEY;
$user_id = $xing_usa_id_ref;
 

$server = null; 

$server = $store->getServer($consumer_key, $user_id);   

 /**/if(empty($server['signature_methods'])) {
    $server = array(
        'consumer_key' => $consumer_key,
        'consumer_secret' => XING_CONSUMER_SECRET,
        'server_uri' => XING_OAUTH_HOST,
        'signature_methods' => array('HMAC-SHA1'),
        'request_token_uri' => XING_REQUEST_TOKEN_URL,
        'authorize_uri' => XING_AUTHORIZE_URL, 
        'access_token_uri' => XING_ACCESS_TOKEN_URL
    );
    $user_id =$user_id +1;
    $_SESSION['xing_usa_id_ref']=$user_id;
 
   $store->updateServer($server, $user_id);

 
}


if (empty($_GET["oauth_token"])) {
    $getAuthTokenParams = array(
      'oauth_callback' => XING_CALLBACK_URL,
      'oauth_consumer_key' => XING_CONSUMER_KEY
    );

    // get a request token
    $tokenResultParams = XingOAuthRequester::requestRequestToken(XING_CONSUMER_KEY, $user_id, $getAuthTokenParams);

    //  redirect to the google authorization page, they will redirect back
    //header("Location: " . XING_AUTHORIZE_URL . "?btmpl=mobile&oauth_token=" . $tokenResultParams['token']);
     header("Location: " . XING_AUTHORIZE_URL . "?btmpl=mobile&oauth_token=" . $tokenResultParams['token']);
     exit();
  } 
   else {

    //  STEP 2:  Get an access token
    $oauthToken = $_GET["oauth_token"];

    $tokenResultParams = $_GET;
    try
    {
     $result = XingOAuthRequester::requestAccessToken(XING_CONSUMER_KEY, $oauthToken, $user_id, 'POST', $_GET);
     }
    catch (OAuthException $e)
    {
      // Handle error
      var_dump($e);
    }
  }
/*New code ends */

   parse_str($result["body"], $responseBody);

unset($_SESSION['xing_key']);
unset($_SESSION['xing_secret']);
unset($_SESSION['red_xing_url']);
unset($_SESSION['xing_script']);


    /*echo "<script>window.opener.$('#xing_accesstoken').val('".$responseBody["oauth_token"]."');
    window.opener.$('#xing_accesstoken_secret').val('".$responseBody["oauth_token_secret"]."');
    window.opener.$('#xing_userid').val('".$responseBody["user_id"]."');    
			window.close();</script>";*/
      echo "<script>window.opener.jQuery('#xing_accesstoken').val('".$responseBody["oauth_token"]."');
    window.opener.jQuery('#xing_accesstoken_secret').val('".$responseBody["oauth_token_secret"]."');
    window.opener.jQuery('#xing_userid').val('".$responseBody["user_id"]."');
    window.opener.jQuery('#xing_usa_id_ref').val('".$user_id."');    
      window.close();</script>";
   

?>