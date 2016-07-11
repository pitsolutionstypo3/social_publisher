<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
include __ROOT__.'/social-media-api-LinkedIn/linkedin_app.php';

//require_once('linkedin_app.php');


$obj = new LinkedIn();
$obj->fnLinkedInConnect();
$response=$obj->fnPostMessage();
//var_dump(json_decode($response));
//echo "response";

?>