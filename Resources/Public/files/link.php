<?php
session_start();
//$key ="7531ezw99nrwmy";
//$secret = "uJ49jbEdo2jrNM04";

$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$pageURL = (@$_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
//$red_url= parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$red_url= $pageURL.$_SERVER['HTTP_HOST'] . $uri_parts[0];;

if(isset($_GET['key'])  && isset($_GET['secret'])){
	$_SESSION['key'] = $_GET['key'];
	$_SESSION['secret'] =$_GET['secret'];
	$_SESSION['red_url'] =$red_url;

}
$key =$_SESSION['key'];
$secret =$_SESSION['secret'];


$redirect_url = urlencode($_SESSION['red_url']);

 
if(isset($_GET['code'])){
	$acode = $_GET['code'];
	$url = "http://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code=$acode&redirect_uri=$redirect_url&client_id=$key&client_secret=$secret";
	 $tocken_string = file_get_contents($url);
	 $t=  json_decode($tocken_string) ;
	 //echo  $t->access_token;

	 /*echo "<script>window.opener.$('#linkedin_accesstoken').val('".$t->access_token."');
			window.close();</script>";*/
			echo "<script>window.opener.jQuery('#linkedin_accesstoken').val('".$t->access_token."');
			window.close();</script>";
		}
/*else if(isset($_GET['access_token'])){

}*/
else
{

$num = '123456789';
$shuffled_state = str_shuffle($num);

header("Location:https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=$key&redirect_uri=$redirect_url&state=$shuffled_state");
 
/*echo "<a href='https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=$key&redirect_uri=$redirect_url&state=987654321'>Click Here to authorize</a>";*/
}

?>