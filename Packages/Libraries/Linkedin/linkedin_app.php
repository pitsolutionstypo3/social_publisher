<?php

#Include necessary class files

define('__ROOT__', dirname(dirname(__FILE__))); 

//require_once(__ROOT__.'/social-media-api-LinkedIn/config.php');
//require_once(__ROOT__.'/social-media-api-LinkedIn/LinkedIn.OAuth2.class.php');

include PATH_typo3conf.'/ext/social_publisher/Packages/Libraries/Linkedin/config.php';
include PATH_typo3conf.'/ext/social_publisher/Packages/Libraries/Linkedin/LinkedIn.OAuth2.class.php';
//http://www.pitsolutions.ch/pdd/linkedin/social-media-api-LinkedIn/test.php
/**
 * This is wrapper class file which will call LinkedIn API functions and return result to the controller.
 */
class LinkedIn
{
    /**
     * Function to get LinkedIn Authorize URL and access token
    */
    function fnLinkedInConnect($consumerkey,$consumersecret,$access_token)
    { 
        # Object of class
        $ObjLinkedIn = new LinkedInOAuth2();
        //$strApiKey = LINKEDIN_API_KEY;
        //$strSecreteKey = LINKEDIN_API_SECRETE_KEY;

        $strApiKey = $consumerkey;
        $strSecreteKey = $consumersecret;


        //put here your redirect url
        //$strRedirect_url = LINKEDIN_CALLBACK_URL;
        //$strRedirect_url = 'http://stage.pitsolutions.ch/pdd/linkedin/social-media-api-LinkedIn/test.php';
        // $strRedirect_url = $callback_url;

        $strCode = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';

        /*if ($strCode == "") {

            try {
                # Get LinkedIn Authorize URL
                #If the user authorizes your application they will be redirected to the redirect_uri that you specified in your request .
                $strGetAuthUrl = $ObjLinkedIn->getAuthorizeUrl($strApiKey, $strRedirect_url);
            } catch (Exception $e) {

            }
            //header("Location: ".$strGetAuthUrl); 
            exit;

        }*/

        # Get LinkedIn Access Token
        /**
         * Access token is unique to a user and an API Key. You need access tokens in order to make API calls to LinkedIn on behalf of the user who authorized your application.
         * The value of parameter expires_in is the number of seconds from now that this access_token will expire in (5184000 seconds is 60 days).
         * You should have a mechanism in your code to refresh the tokens before they expire in order to continue using the same access tokens.
         */
       /* $arrAccess_token = $ObjLinkedIn->getAccessToken($strApiKey, $strSecreteKey, $strRedirect_url, $strCode);
        $strAccess_token = $arrAccess_token["access_token"];*/

        $_SESSION['_oauth_token'] = $access_token;
    }

    /**
     * To Get List of LinkedIn Company Pages.
     */
    function fnGetLinkedCompanyPages()
    {
        $strAccess_token = $_SESSION['_oauth_token'];

        # Object of class
        $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

        # Get List of company pages
        try {
            $arrAdminCompany = $ObjLinkedin->getAdminCompanies();
        } catch (Exception $e) {

        }

        $arrAdminCompanyValue = $arrAdminCompany["values"];
        $intTotalCount = count($arrAdminCompany["_total"]);

        $arrLinkedInPages = array();
        $intCount = 0;
        if (is_array($arrAdminCompanyValue) && count($arrAdminCompanyValue) > 0) {
            foreach ($arrAdminCompanyValue as $arrAdminCompanyInfo) {
                $intFlag = 0;

                $arrLinkedInPages[$intCount]["id"] = (int) $arrAdminCompanyInfo["id"];
                $arrLinkedInPages[$intCount]["name"] = stripslashes($arrAdminCompanyInfo["name"]);
            }
        }
        return $arrLinkedInPages;
    }

    /**
     * To Get List of LinkedIn User Profiles.
     */
    function fnGetLinkedUserProfile()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intUserId = "USER ID";

        if ($intUserId > 0) {
            # Object of class
            $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

            # Get List of company pages
            try {
                $arrLinkedInProfile = $ObjLinkedin->getUserProfile($intUserId);
            } catch (Exception $e) {

            }
            return $arrLinkedInProfile;
        }
    }

    /**
     * To Get List of LinkedIn Company Information.
     */
    function fnGetLinkedCompanyInformation()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intCompanyId = "COMPANY ID";

        if ($intCompanyId > 0) {
            # Object of class
            $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

            # Get List of company pages
            try {
                $arrLinkedInCompanyInformation = $ObjLinkedin->getCompany($intCompanyId);
            } catch (Exception $e) {

            }
            return $arrLinkedInCompanyInformation;
        }
    }

    /**
     * To get LinkedIn company updates
     */
    function fnGetLinkedInCompanyUpdates()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intCompanyId = "COMPANY ID";

        if ($intCompanyId > 0) {

            $intStart = 0;
            $intCount = 10;
            $intPage = 1;
            if ($intPage) {
                $intStart = ($intPage - 1) * 10;
            }
            $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

            try {
                $arrCompanyUpdate = $ObjLinkedin->getCompanyUpdates($intCompanyId, $intStart, $intCount);
            } catch (Exception $e) {

            }
        }
    }

    /**
     * To get LinkedIn user profile updates
     */
    function fnGetLinkedInUserProfileUpdates()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intUserId = "USER ID";

        if ($intUserId > 0) {

            $intStart = 0;
            $intCount = 10;
            $intPage = 1;
            if ($intPage) {
                $intStart = ($intPage - 1) * 10;
            }
            $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

            try {
                $arrUserProfileUpdates = $ObjLinkedin->getUserStatuses($intUserId, true, $intStart, $intCount);
            } catch (Exception $e) {

            }
        }
    }

    /**
     * Function to Send status Message on LinkedIn company Pages
     */
  /*  function fnPostMessage()
    {
        $strAccess_token = $_SESSION['_oauth_token'];
        $intCompanyPageId = "COMPANY ID";
        $strStatusMessage = "STATUS MESSAGE";

        $ObjLinkedin = new LinkedInOAuth2($strAccess_token);

        try {
            $strErrorMessage = '';
            $arrResponse = $ObjLinkedin->postToCompany($intCompanyPageId, $strStatusMessage);
            // not post given error
            if ($arrResponse['updateKey'] == "") {
                $strErrorMessage = "SET ERROR MESSAGE";
            }

        } catch (Exception $e) {

        }
        return $strErrorMessage;
    }*/


    function fnPostStatusMessage()
    {
        //echo "status msg";
        $strAccess_token = $_SESSION['_oauth_token'];
       // echo "<br>".$strAccess_token;
        $args = array(
        'comment'=>' Testing with different image in stage',
        'title'=>' Testing title with different image in stage',
        'submitted-url'=>'http://developer.linkedin.com/docs/share-on-linkedin',
        'submitted-image-url'=>'http://stage.pitsolutions.ch/pdd/linkedin/social-media-api-LinkedIn/images/image2.jpeg',
        'description'=>'Test Description testing'
        );

    $ObjLinkedin = new LinkedInOAuth2($strAccess_token);
    $response=$ObjLinkedin->shareStatus($args);
    return $response;
    }



    /**
     * Function to Send status Message on LinkedIn company Pages
     */
    function fnPostMessage($title,$message,$file_name,$post_link,$linkedin_pageid)
   {
        $strAccess_token = $_SESSION['_oauth_token'];
        //$intCompanyPageId = "10825205";
        $intCompanyPageId = $linkedin_pageid;
        $strStatusMessage = "";

        $args = array(
        'comment'=>'',
        'title'=>$title,
        'submitted-url'=>$post_link,
        'submitted-image-url'=>$file_name,
        'description'=>$message
        );

        $ObjLinkedin = new LinkedInOAuth2($strAccess_token);
        $response = $ObjLinkedin->postToCompany($intCompanyPageId,$strStatusMessage,$args);
        //var_dump(json_decode($response));
        return $response;
    }
}