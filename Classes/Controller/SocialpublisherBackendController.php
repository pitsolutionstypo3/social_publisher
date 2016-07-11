<?php
namespace PITSolutions\SocialPublisher\Controller;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

include PATH_typo3conf.'/ext/social_publisher/Packages/Libraries/Facebook/autoload.php';
use Facebook\Facebook;

include PATH_typo3conf.'/ext/social_publisher/Packages/Libraries/Twitter/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;


include PATH_typo3conf.'/ext/social_publisher/Packages/Libraries/Linkedin/linkedin_app.php';

include PATH_typo3conf.'/ext/social_publisher/Packages/Libraries/Xing/xing.php';


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Siju E Raju <siju.er@pitsolutions.com>, PIT Solutions
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * SocialpublisherBackendController
 */
class SocialpublisherBackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * socialpublisherRepository
	 *
	 * @var \PITSolutions\SocialPublisher\Domain\Repository\SocialpublisherRepository
	 * @inject
	 */
	protected $socialpublisherRepository = NULL;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
	
		$db_arr =   $GLOBALS['TYPO3_CONF_VARS']['DB'];
		$serialized_array = serialize($db_arr);
		$encoded=base64_encode($serialized_array);

		/*\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($GLOBALS['TCA']['pages']['types'][(string)\TYPO3\CMS\Frontend\Page\PageRepository::DOKTYPE_DEFAULT]['showitem']);*/
				
		if ($this->request->hasArgument('socail_settings')) {
			$form_data = $this->request->getArgument('socail_settings');						
			$reports = $this->socialpublisherRepository->savesocialSettings($form_data);			
		}

		$socialpublishers = $this->socialpublisherRepository->getSocialSettings();
		$socialpublishers['crd_array'] = $encoded;

		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($socialpublishers);
		$this->view->assign('socialpublishers', $socialpublishers);	
	}

	/**
	 * action show
	 *
	 * @param \PITSolutions\SocialPublisher\Domain\Model\Socialpublisher $socialpublisher
	 * @return void
	 */
	public function showAction(\PITSolutions\SocialPublisher\Domain\Model\Socialpublisher $socialpublisher) {
		$this->view->assign('socialpublisher', $socialpublisher);
	}

	/**
	 * action list
	 *
	 * @param array $params Array of parameters from the AJAX interface, currently unused
	 * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj Object of type AjaxRequestHandler
	 * @return void
	 */
	public function publishtoAction($params = array(), \TYPO3\CMS\Core\Http\AjaxRequestHandler &$ajaxObj = NULL) {	
		$fb_publish= 0;
		$twitter_publish=0;
		$linkedin_publish=0;
		$xing_publish=0;
		$form_data = GeneralUtility:: _GET("tx_socialpublisher");
		$page_settings = json_decode($form_data, true);

		$obj_manager=GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$db_obj=$obj_manager->get('PITSolutions\\SocialPublisher\\Domain\\Repository\\SocialpublisherRepository');
		$common_settings = $db_obj->getSocialSettings();

						

		/*Twitter*/
		if(!$page_settings ['tx_socialpublisher_disable_twitter'] && $common_settings['social_settings_twitter']) {
			$twitter_response = $this->publishTwitter($page_settings,$common_settings);
			if(!$twitter_response->errors) {
							$twitter_publish=1;
			}
			else
			{
				/*Save error log*/
						$error_log['platform']="Twitter";						
						if($twitter_response->errors[0]->message){
							$error_log['response']=$twitter_response->errors[0]->message;
						}
						else
						{
							$error_log['response']="Internal server error";							
						}
						$error = $db_obj->saveErrorlog($error_log);
						/*Save error log ENDs*/
				
						$twitter_publish=1;/*Save error in log*/
			}
		}	


		/*Linkedin*/
		if(!$page_settings ['tx_socialpublisher_disable_linkedin'] && $common_settings['social_settings_linkedin']) {
			$linkedin_response = $this->publishLinkedin($page_settings,$common_settings);

				if($linkedin_response['updateKey'] && $linkedin_response['updateUrl']) {
								$linkedin_publish=1;
				}
				else
				{

					/*Save error log*/
						$error_log['platform']="Linkedin";						
						if($linkedin_response['errorCode'] == 0 && $linkedin_response['message']){
							$error_log['response']=$linkedin_response['message'];
						}
						else
						{
							$error_log['response']="Internal server error";							
						}
						$error = $db_obj->saveErrorlog($error_log);
						/*Save error log ENDs*/
							$linkedin_publish=1;/*Save error in log*/
				}
		}	


		/*Facebook*/
		if(!$page_settings ['tx_socialpublisher_disable_facebook'] && $common_settings['social_settings_facebook']) {
			$fb_response = $this->publishFacebook($page_settings,$common_settings);			
				/*If success set value to 1 else 2*/
				if(($fb_response->getHttpStatusCode() == 200) &&  !($fb_response->isError())) {
					$fb_publish = 1;
				}
				else{
						/*Save error log*/
						$error_log['platform']="Facebook";						
						if($fb_response->getMessage()){
							$error_log['response']=$fb_response->getMessage();
						}
						else
						{
							$error_log['response']="500 - Internal server error";							
						}
						$error = $db_obj->saveErrorlog($error_log);
						/*Save error log ENDs*/

					$fb_publish = 2;
				}
		}


		/*Xing*/
		if(!$page_settings ['tx_socialpublisher_disable_xing'] && $common_settings['social_settings_xing']) {


			$xingresponse = $this->publishXing($page_settings,$common_settings);
				if($xingresponse['code'] == 201) {
								$xing_publish = 1;
							}
				else{
						/*Save error log*/
						$error_log['platform']="Xing";						
						/*if($xing_response){
							$error_log['response']=$linkedin_response['message'];
						}
						else
						{*/
							$error_log['response']="Internal server error";							
						//}
						$error = $db_obj->saveErrorlog($error_log);
						/*Save error log ENDs*/

							$xing_publish=1;/*Save error in log*/
				}
		}	






		/*FINAL RETURN*/
		if($fb_publish == 1 || $twitter_publish == 1 || $linkedin_publish == 1 || $xing_publish== 1){
			echo 1;

		}
		else
		{
			echo 0;
		}
		
		
	}

	/**
	 * Publish to facebook
	 *
	 * @param 
	 * @param
	 * @return void
	 */
	public function publishFacebook($page_settings,$common_settings) {		
		
		if($page_settings['uid']){
				$objectManager=GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
				$db_obj=$objectManager->get('PITSolutions\\SocialPublisher\\Controller\\UrlBackendController');
				$post_link = $db_obj->createLink($page_settings['uid']);
				$site_name = $db_obj->createTtle();

				if($page_settings['tx_socialpublisher_file']){
		       $file_name = $db_obj->getfiledetailsbyId($page_settings['tx_socialpublisher_file']);
		       }
		       else{  $file_name = ''; }
   		}
   		else{
   			/*IF IT IS A TEST POST*/
   			$post_link = $page_settings['test_link'];
   			$file_name = '';

   		}
	  

		/*Custom message*/
		if($common_settings['facebook_message']){
			$message = $common_settings['facebook_message']; 

			$matches = array();
			$start="##";
			$end="##";
		    $regex = "/$start([a-zA-Z0-9_]*)$end/";
		    preg_match_all($regex, $common_settings['facebook_message'], $matches);
		    

		    if (in_array("TITLE", $matches[1])) {
			   $message = str_replace('##TITLE##', $page_settings['title'], $message); 
			}
			 if (in_array("SITENAME", $matches[1])) {
			    $message = str_replace('##SITENAME##', $site_name, $message);
			}
			 if (in_array("URL", $matches[1])) {
			    $message = str_replace('##URL##', $post_link, $message);
			}
			 if (in_array("DESCRIPTION", $matches[1])) {
			     $message = str_replace('##DESCRIPTION##', $page_settings['description'], $message); 
			}



		}
		else{
			$message = $page_settings['description'];
		}

		/*Custom message ends*/


	 	if($common_settings['facebook_appid'] &&  $common_settings['facebook_appsecret'] && $common_settings['facebook_accesstoken']) {
		

		//use Facebook\Facebook;
		
		$conf = array(
            'app_id' => $common_settings['facebook_appid'],
            'app_secret' => $common_settings['facebook_appsecret'],
            'default_graph_version' => 'v2.6',
            'fileUpload' => true
        );

		$fb = new Facebook($conf);

		$linkData = array(
            'link' => $post_link,
            'message' => $message,
            'source' => $file_name,
           );
		
        try {    // Returns a `Facebook\FacebookResponse` object

            $response = $fb->post('/me/feed', $linkData, $common_settings['facebook_accesstoken']);
		

        } catch (Facebook\Exceptions\FacebookResponseException $e) {   
        	$response=$e;
         //echo 'Graph returned an error: ' . $e->getMessage();
            die;
        } catch (Facebook\Exceptions\FacebookSDKException $e) { 
        	$response=$e; 
          //echo 'Facebook SDK returned an error: ' . $e->getMessage();
           die;
        }
        $graphNode = $response->getGraphNode();

    }

    return $response; 


}


/**
	 * Publish to Twitter
	 *
	 * @param 
	 * @param
	 * @return void
	 */
	public function publishTwitter($page_settings,$common_settings) {

		if($page_settings['uid']){

				$objectManager=GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
				
				$db_obj=$objectManager->get('PITSolutions\\SocialPublisher\\Controller\\UrlBackendController');
				$post_link = $db_obj->createLink($page_settings['uid']);
				$site_name = $db_obj->createTtle();

				 if($page_settings['tx_socialpublisher_file']){
		       $file_name = $db_obj->getfiledetailsbyId($page_settings['tx_socialpublisher_file']);
		       }
		       else{  $file_name = ''; }


		}
   		else{
   			/*IF IT IS A TEST POST*/
   			$post_link = $page_settings['description'].' ... '.$page_settings['test_link'];
   			$file_name = '';

   		}


		/*Custom message*/
		if($common_settings['twitter_message']){
			$message = $common_settings['twitter_message']; 

			$matches = array();
			$start="##";
			$end="##";
		    $regex = "/$start([a-zA-Z0-9_]*)$end/";
		    preg_match_all($regex, $common_settings['twitter_message'], $matches);
		    

		    if (in_array("TITLE", $matches[1])) {
			   $message = str_replace('##TITLE##', $page_settings['title'], $message); 
			}
			 if (in_array("SITENAME", $matches[1])) {
			    $message = str_replace('##SITENAME##', $site_name, $message);
			}
			
			 if (in_array("DESCRIPTION", $matches[1])) {

			 		if((strlen($message)+ strlen($page_settings['description'])) < 140){

			    		 $message = str_replace('##DESCRIPTION##', $page_settings['description'], $message); 
			 		}
			 		else
			 		{
			 			 $message = str_replace('##DESCRIPTION##', '', $message); 
			 		}
			}

			/* if (in_array("URL", $matches[1])) {
				
			    $message = str_replace('##URL##', $post_link, $message);
				
			}*/


			/*$tweet_count=strlen($message); // +23 for image
			if($tweet_count > 140)
			{
				if($file_name){  //if image file, truncates more content to show image
					//$message = substr($message, 0, 105);
					$message = substr($message, 0, 85);
				    $message=$message.'..'.$post_link;
				}
				else{
				$message = substr($message, 0, 100);
				$message=$message.'..'.$post_link;
				}
			}*/
			$tweet_count=strlen($message);

			if($file_name){
					if((140 - $tweet_count) > 50){
						$message=$message.'..'.$post_link;
					}
					else
					{
						$message = substr($message, 0, 85);
				    	$message=$message.'..'.$post_link;
					}
			}
			else{

					if((140 - $tweet_count) > 25){
						$message=$message.'..'.$post_link;
					}
					else
					{
						$message = substr($message, 0, 110);
				    	$message=$message.'..'.$post_link;
					}

			}



		}
		else{
			$message = $post_link;
		}


		

		/*Custom message ends*/
		//$tweet="New post ".$page_settings['title']." on ".$site_name." Link: ". $post_link;
		
			if($common_settings['twitter_consumer_key'] &&  $common_settings['twitter_consumer_secret'] && $common_settings['twitter_access_token'] &&  $common_settings['twitter_access_token_secret']) {

				$consumerkey = $common_settings['twitter_consumer_key'];
				$consumersecret = $common_settings['twitter_consumer_secret'];
				$access_token = $common_settings['twitter_access_token'];
				$access_token_secret = $common_settings['twitter_access_token_secret'];

				$connection = new TwitterOAuth($consumerkey, $consumersecret, $access_token, $access_token_secret);
				$content = $connection->get("account/verify_credentials");

				if($file_name){
					$file_path=$file_name;
					/*$file_path='';*/
					$result = $connection->upload('media/upload', array('media' => $file_path));
					$mediaID = $result->media_id;
					//$parameters = array('status' => $tweet,'media_ids' => $mediaID);
					$parameters = array('status' => $message,'media_ids' => $mediaID);
				}else{
					$parameters = array('status' => $message);
				}
				$response = $connection->post('statuses/update', $parameters);
						

			}
			//return 1;
			return $response;
		}

		/**
	 * Publish to Linkedin
	 *
	 * @param 
	 * @param
	 * @return void
	 */
	public function publishLinkedin($page_settings,$common_settings) {

			$response=array();

			if($page_settings['uid']){
					$objectManager=GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
					
					$db_obj=$objectManager->get('PITSolutions\\SocialPublisher\\Controller\\UrlBackendController');
					$post_link = $db_obj->createLink($page_settings['uid']);
					$site_name = $db_obj->createTtle();

					if($page_settings['tx_socialpublisher_file']){
			      	 $file_name = $db_obj->getfiledetailsbyId($page_settings['tx_socialpublisher_file']);
			       	}
			       else{  $file_name = ''; }
			}
	   		else{
	   			
	   			/*IF IT IS A TEST POST*/
	   			$post_link = $page_settings['test_link'];
	   			$file_name = '';

	   		}       
	  

		/*Custom message*/
		if($common_settings['linkedin_message']){
			$message = $common_settings['linkedin_message']; 

			$matches = array();
			$start="##";
			$end="##";
		    $regex = "/$start([a-zA-Z0-9_]*)$end/";
		    preg_match_all($regex, $common_settings['linkedin_message'], $matches);
		    

		    if (in_array("TITLE", $matches[1])) {
			   $message = str_replace('##TITLE##', $page_settings['title'], $message); 
			}
			 if (in_array("SITENAME", $matches[1])) {
			    $message = str_replace('##SITENAME##', $site_name, $message);
			}
			 if (in_array("URL", $matches[1])) {
			    $message = str_replace('##URL##', $post_link, $message);
			}
			 if (in_array("DESCRIPTION", $matches[1])) {
			     $message = str_replace('##DESCRIPTION##', $page_settings['description'], $message); 
			}



		}
		else{
			$message = $page_settings['description'];
		}

		/*Custom message ends*/





		if($common_settings['linkedin_consumer_key'] &&  $common_settings['linkedin_consumer_secret'] &&  $common_settings['linkedin_pageid'] && $common_settings['linkedin_accesstoken']) {


				$consumerkey = $common_settings['linkedin_consumer_key'];
				$consumersecret = $common_settings['linkedin_consumer_secret'];
				//$callback_url = $common_settings['linkedin_callback_url'];
				$page_id = $common_settings['linkedin_pageid'];
				$access_token = $common_settings['linkedin_accesstoken'];



				$obj = new \LinkedIn();
				$res= $obj->fnLinkedInConnect($consumerkey,$consumersecret,$access_token);


				$response=$obj->fnPostMessage($page_settings['title'],$message,$file_name,$post_link,$common_settings['linkedin_pageid']);
				//return $response['updateUrl'];
				//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($response);

			}

			return $response;
	}


	/**
	 * Publish to Xing
	 *
	 * @param 
	 * @param
	 * @return void
	 */
	public function publishXing($page_settings,$common_settings) {



			define("XING_CONSUMER_KEY", $common_settings['xing_consumer_key']); // get it from https://dev.xing.com/
			define("XING_CONSUMER_SECRET", $common_settings['xing_consumer_secret']); // get it from https://dev.xing.com/
			define("XING_CALLBACK_URL", $common_settings['xing_callback_url']);
			define('OAUTH_TMP_DIR', "/tmp/"); // change to your local tmp dir

			define("XING_OAUTH_HOST", "https://api.xing.com");
			define("XING_REQUEST_TOKEN_URL", XING_OAUTH_HOST . "/v1/request_token");
			define("XING_AUTHORIZE_URL", XING_OAUTH_HOST . "/v1/authorize");
			define("XING_ACCESS_TOKEN_URL", XING_OAUTH_HOST . "/v1/access_token");

			//  Init the OAuthStore https://api.xing.com
			/*$options = array(
			  'consumer_key' => XING_CONSUMER_KEY,
			  'consumer_secret' => XING_CONSUMER_SECRET,
			  'server_uri' => XING_OAUTH_HOST,
			  'request_token_uri' => XING_REQUEST_TOKEN_URL,
			  'authorize_uri' => XING_AUTHORIZE_URL,
			  'access_token_uri' => XING_ACCESS_TOKEN_URL
			);*/

			// Note: do not use "Session" storage in production. Prefer a database
			// storage, such as MySQL.
			$OAuthStore_obj = new \OAuthStore();
			//$OAuthStore_obj->instance("Session", $options);
			


			$db_arr =   $GLOBALS['TYPO3_CONF_VARS']['DB'];
			$options = array(
			  'server' => $db_arr['host'],
			  'username' => $db_arr['username'],
			  'password' => $db_arr['password'],
			  'database' => $db_arr['database']
			  );
				//OAuthStore::instance("MySQLi", $options);
				$store = $OAuthStore_obj->instance("MySQLi", $options);
				/*Added*/
				$consumer_key = XING_CONSUMER_KEY;
				//$user_id = 1;
				$user_id = $common_settings['xing_usa_id_ref'];
		
			
			

			  if($common_settings['xing_consumer_key']) {




		/*Object initialization*/
		if($page_settings['uid']){
				$objectManager=GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
				
				$db_obj=$objectManager->get('PITSolutions\\SocialPublisher\\Controller\\UrlBackendController');
				$post_link = $db_obj->createLink($page_settings['uid']);
				$site_name = $db_obj->createTtle();

				/*if($page_settings['tx_socialpublisher_file']){
		      	 $file_name = $db_obj->getfiledetailsbyId($page_settings['tx_socialpublisher_file']);
		       	}
		       else{  $file_name = ''; }*/
		    }
			   		else{
	   			
	   			/*IF IT IS A TEST POST*/
	   			$post_link = $page_settings['test_link'];
	   			$file_name = '';

	   		}       
	  

		/*Custom message*/
		if($common_settings['xing_message']){
			$message = $common_settings['xing_message']; 

			$matches = array();
			$start="##";
			$end="##";
		    $regex = "/$start([a-zA-Z0-9_]*)$end/";
		    preg_match_all($regex, $common_settings['xing_message'], $matches);
		    

		    if (in_array("TITLE", $matches[1])) {
			   $message = str_replace('##TITLE##', $page_settings['title'], $message); 
			}
			 if (in_array("SITENAME", $matches[1])) {
			    $message = str_replace('##SITENAME##', $site_name, $message);
			}
			 
			 if (in_array("DESCRIPTION", $matches[1])) {
			     $message = str_replace('##DESCRIPTION##', $page_settings['description'], $message); 
			}

				$xing_count=strlen($message);
				if($xing_count > 420)
				{
					$message = substr($message, 0, 415);
					$message=$message.'..';
				}

		}
		else{
			$message = $page_settings['description'];

				$xing_count=strlen($message);
				if($xing_count > 420)
				{
					$message = substr($message, 0, 415);
					$message=$message.'..';
				}
		}

		/*Custom message ends*/


			  
			    $oauth_options = array();
			     //$url = 'https://api.xing.com/v1/users/'.$responseBody["user_id"].'/status_message?message=posting content first cc';
			    $link= urlencode ($post_link );
			    $url = 'https://api.xing.com/v1/users/me/share/link?uri='. $link.'&text='.$message;
			  
			   
			    //$request = new \XingOAuthRequester($url, 'POST', array());
			    $request = new \OAuthRequester($url, 'POST', $oauth_options);
			    //$result = $request->doRequest($user_id);

					   try {
							    $result = $request->doRequest($user_id);
							} catch (OAuthException $e){
							    // Handle error.
							     $result=$e;
							}
			  }
			return $result;


	}


	/**
	 * Facebook test publish
	 *
	 * @param array $params Array of parameters from the AJAX interface, currently unused
	 * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj Object of type AjaxRequestHandler
	 * @return void
	 */
	public function testpublishAction($params = array(), \TYPO3\CMS\Core\Http\AjaxRequestHandler &$ajaxObj = NULL) {

		$common_settings=array();
		$page_settings=array();

		$common_settings['facebook_appid'] = GeneralUtility:: _POST("facebook_appid");
		$common_settings['facebook_appsecret'] = GeneralUtility:: _POST("facebook_appsecret");
		$common_settings['facebook_accesstoken']= GeneralUtility:: _POST("facebook_accesstoken");
		$common_settings['social_settings_facebook']= GeneralUtility:: _POST("social_settings_facebook");
		$common_settings['facebook_message']=false;

		$page_settings['uid']=false;
		$page_settings['description'] = "This is a sample post for testing. Please ignore this";
	


		$pieces = explode("/typo3/", $_SERVER['HTTP_REFERER']);
		$page_settings['test_link'] = $pieces[0];

				
					
				/*Facebook*/
				if($common_settings['social_settings_facebook'] == 'true') {
					$fb_response = $this->publishFacebook($page_settings,$common_settings);
					
						/*If success set value to 1 else 2*/
						if(($fb_response->getHttpStatusCode() == 200) &&  !($fb_response->isError())) {
							echo 1;
						}
						else{
							echo 2;
						}
				}
				else{
					/*If not configured or no need to publish set value to 1*/
					echo 3;					
				}	
		}

	/**
	 * Twitter test publish
	 *
	 * @param array $params Array of parameters from the AJAX interface, currently unused
	 * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj Object of type AjaxRequestHandler
	 * @return void
	 */
	public function twittertestpublishAction($params = array(), \TYPO3\CMS\Core\Http\AjaxRequestHandler &$ajaxObj = NULL) {
		$common_settings=array();
		$page_settings=array();

		$common_settings['twitter_consumer_key'] = GeneralUtility:: _POST("twitter_consumer_key");
		$common_settings['twitter_consumer_secret'] = GeneralUtility:: _POST("twitter_consumer_secret");
		$common_settings['twitter_access_token']= GeneralUtility:: _POST("twitter_access_token");
		$common_settings['twitter_access_token_secret']= GeneralUtility:: _POST("twitter_access_token_secret");
		$common_settings['social_settings_twitter']= GeneralUtility:: _POST("social_settings_twitter");
		$common_settings['twitter_message']=false;

		$page_settings['uid']=false;
		$page_settings['description'] = "This is a sample post for testing. Please ignore this.";

		$pieces = explode("/typo3/", $_SERVER['HTTP_REFERER']);
		$page_settings['test_link'] = $pieces[0];



		/*Twitter*/
				if($common_settings['social_settings_twitter'] == 'true') {
					$twitter_response = $this->publishTwitter($page_settings,$common_settings);

					
				//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($twitter_response);
					
					//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump( $twitter_response->errors[0]->message);
						/*If success set value to 1 else 2*/
						if(!$twitter_response->errors) {
							echo 1;
						}
						else if($twitter_response->errors[0]->message){
							echo $twitter_response->errors[0]->message;
						}
						else
						{
							echo 2;
						}
				}
				else{
					/*If not configured or no need to publish set value to 1*/
					echo 3;					
				}	
	}


/**
	 * Linkedin test publish
	 *
	 * @param array $params Array of parameters from the AJAX interface, currently unused
	 * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj Object of type AjaxRequestHandler
	 * @return void
	 */
	public function linkedintestpublishAction($params = array(), \TYPO3\CMS\Core\Http\AjaxRequestHandler &$ajaxObj = NULL) {
		$common_settings=array();
		$page_settings=array();

		$common_settings['linkedin_consumer_key'] = GeneralUtility:: _POST("linkedin_consumer_key");
		$common_settings['linkedin_consumer_secret'] = GeneralUtility:: _POST("linkedin_consumer_secret");
		$common_settings['linkedin_pageid'] = GeneralUtility:: _POST("linkedin_pageid");
		$common_settings['linkedin_accesstoken']= GeneralUtility:: _POST("linkedin_accesstoken");
		$common_settings['social_settings_linkedin']= GeneralUtility:: _POST("social_settings_linkedin");
		$common_settings['linkedin_message'] = false;

		$page_settings['uid']=false;
		$page_settings['description'] = "This is a sample post for testing. Please ignore this.";

		$pieces = explode("/typo3/", $_SERVER['HTTP_REFERER']);
		$page_settings['test_link'] = $pieces[0];



		
				if($common_settings['social_settings_linkedin'] == 'true') {

					$linkedin_response = $this->publishLinkedin($page_settings,$common_settings);

						if($linkedin_response['updateKey'] && $linkedin_response['updateUrl']) {
							echo 1;
						}
						else if($linkedin_response['errorCode'] == 0 && $linkedin_response['message']){
							echo $linkedin_response['message'];
						}
						else
						{
							echo 2;
						}

					    
					}
				else{
					/*If not configured or no need to publish set value to 3*/
					echo 3;					
				}	

	}

	/**
	 * Xing test publish
	 *
	 * @param array $params Array of parameters from the AJAX interface, currently unused
	 * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj Object of type AjaxRequestHandler
	 * @return void
	 */
	public function xingtestpublishAction($params = array(), \TYPO3\CMS\Core\Http\AjaxRequestHandler &$ajaxObj = NULL) {
			$common_settings=array();
			$page_settings=array();

		$common_settings['xing_consumer_key'] = GeneralUtility:: _GET("xing_consumer_key");
		$common_settings['xing_consumer_secret'] = GeneralUtility:: _GET("xing_consumer_secret");
		$common_settings['xing_callback_url'] = GeneralUtility:: _GET("xing_callback_url");
		$common_settings['xing_usa_id_ref'] = GeneralUtility:: _GET("xing_usa_id_ref");
		$social_settings_xing= GeneralUtility:: _GET("social_settings_xing");		
		$common_settings['xing_message'] = false;

		$page_settings['uid']=false;
		$page_settings['description'] = "This is a sample post for testing. Please ignore this.";

		$pieces = explode("/typo3/", $_SERVER['HTTP_REFERER']);
		$page_settings['test_link'] = $pieces[0];

		if($social_settings_xing == 'true') {

					$xing_response = $this->publishXing($page_settings,$common_settings);				

						if($xing_response['code'] == 201) {
							echo 1;
						}



		}
		else{
					/*If not configured or no need to publish set value to 3*/
					echo 3;					
		}	
	}


/**
	 * Save ajax exception
	 *
	 * @param 
	 * @param
	 * @return void
	 */
	public function saveerrorAction($error) {	
		$thrownError = GeneralUtility:: _GET("thrownError");	

		$obj_manager=GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$db_obj=$obj_manager->get('PITSolutions\\SocialPublisher\\Domain\\Repository\\SocialpublisherRepository');
	
						//$error_log['platform']="Facebook/Xing";						
						$error_log['platform']="";
						$error_log['response']=$thrownError;							
						
						$error = $db_obj->saveErrorlog($error_log);
						
	}
	 

}