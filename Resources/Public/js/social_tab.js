jQuery.noConflict();
jQuery( document ).ready(function() {
 jQuery(".tabs-menu a").click(function(event) {
        event.preventDefault();
        jQuery(this).parent().addClass("current");
        jQuery(this).parent().siblings().removeClass("current");
        var tab = jQuery(this).attr("href");
        jQuery(".tab-content").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
    });

jQuery("#show_format").click(function(event) {
 	jQuery("#format_info").toggle("slow");

 	if(jQuery("#show_format").text() == 'Show Format Info'){
 		jQuery("#show_format").html('Hide Format Info');
 	}
 	else{
 		
 		jQuery("#show_format").html('Show Format Info');
 	}
 	
 	
 });

jQuery("#show_format_twitter").click(function(event) {
 	jQuery("#format_info_twitter").toggle("slow");

 	if(jQuery("#show_format_twitter").text() == 'Show Format Info'){
 		jQuery("#show_format_twitter").html('Hide Format Info');
 	}
 	else{
 		
 		jQuery("#show_format_twitter").html('Show Format Info');
 	}
 	
 	
 });

jQuery("#show_format_linkedin").click(function(event) {
 	jQuery("#format_info_linkedin").toggle("slow");

 	if(jQuery("#show_format_linkedin").text() == 'Show Format Info'){
 		jQuery("#show_format_linkedin").html('Hide Format Info');
 	}
 	else{
 		
 		jQuery("#show_format_linkedin").html('Show Format Info');
 	}
 	
 	
 });

jQuery("#show_format_xing").click(function(event) {
 	jQuery("#format_info_xing").toggle("slow");

 	if(jQuery("#show_format_xing").text() == 'Show Format Info'){
 		jQuery("#show_format_xing").html('Hide Format Info');
 	}
 	else{
 		
 		jQuery("#show_format_xing").html('Show Format Info');
 	}
 	
 	
 });


jQuery("#get_accesstoken_linkedin").click(function(event) {

	
var url=jQuery("#linkedin_accesstoken_link").val();
var key=jQuery("#linkedin_consumer_key").val();
var secret=jQuery("#linkedin_consumer_secret").val();
   
    url=url+'?key='+key+'&secret='+secret;

    window.open(url, "Linkedin", "width=500,height=500,scrollbars=yes");
    return false;

 });



jQuery("#get_accesstoken_xing").click(function(event) {

	 
var url=jQuery("#xing_accesstoken_link").val();
var key=jQuery("#xing_consumer_key").val();
var secret=jQuery("#xing_consumer_secret").val();
var xing_script=jQuery("#crd_array").val();
var xing_usa_id_ref=jQuery("#xing_usa_id_ref").val();

    /*var url = "/typo3seven_test/typo3conf/ext/social_publisher/link.php";*/
    url=url+'?key='+key+'&secret='+secret+'&xing_script='+xing_script+'&xing_usa_id_ref='+xing_usa_id_ref;
 window.open(url, "Xing", "width=500,height=500,scrollbars=yes");
   
    return false;

 });



});

function testFacebook()
{
  
jQuery("#facebook_success").html("<img src='/typo3conf/ext/social_publisher/Resources/Public/Icons/loading.gif' />");
    
var facebook_appid = jQuery("#facebook_appid").val();
var facebook_appsecret = jQuery("#facebook_appsecret").val();
var facebook_accesstoken = jQuery("#facebook_accesstoken").val();
var social_settings_facebook = jQuery('#social_settings_facebook').is(":checked");


var ajaxUrl = TYPO3.settings.ajaxUrls['SocialpublisherBackendController::testpublishAction'];

              jQuery.ajax({
              method: "POST",
              url: ajaxUrl,
              data:  {"facebook_appid":facebook_appid, "facebook_appsecret":facebook_appsecret, "facebook_accesstoken":facebook_accesstoken,"social_settings_facebook":social_settings_facebook},
              success:function( msg ) {     
                        if(msg == 1){            
                            jQuery('#facebook_error').html('');
                            jQuery('#facebook_success').html("Test Content Posted Successfully");
                        }
                        else if(msg == 2)
                        {
                            jQuery('#facebook_success').html('');
                            jQuery('#facebook_error').html('Facebook responded with an error');
                        }
                         else if(msg == 3)
                        {
                            jQuery('#facebook_success').html('');
                            jQuery('#facebook_error').html('Please select facebook publish option');
                        }
                        else
                        {
                            jQuery('#facebook_success').html('');
                            jQuery('#facebook_error').html('Error occurred');
                        }
              },
              error:function (xhr, ajaxOptions, thrownError){
                   
                    jQuery('#facebook_success').html('');
                    jQuery('#facebook_error').html('Error in settings. Please check your settings and try again');
                }
            });

}

function testTwitter()
{

jQuery("#twitter_success").html("<img src='/typo3conf/ext/social_publisher/Resources/Public/Icons/loading.gif' />");
   
var twitter_consumer_key = jQuery("#twitter_consumer_key").val();
var twitter_consumer_secret = jQuery("#twitter_consumer_secret").val();
var twitter_access_token = jQuery("#twitter_access_token").val();
var twitter_access_token_secret = jQuery("#twitter_access_token_secret").val();
var social_settings_twitter = jQuery('#social_settings_twitter').is(":checked");

var ajaxUrl = TYPO3.settings.ajaxUrls['SocialpublisherBackendController::twittertestpublishAction'];

              jQuery.ajax({
              method: "POST",
              url: ajaxUrl,
              data:  {"twitter_consumer_key":twitter_consumer_key, "twitter_consumer_secret":twitter_consumer_secret, "twitter_access_token":twitter_access_token,"twitter_access_token_secret":twitter_access_token_secret,"social_settings_twitter":social_settings_twitter},
              success:function( msg ) {     
              
                       if(msg == 1){            
                            jQuery('#twitter_error').html('');
                            jQuery('#twitter_success').html("Test Content Tweeted Successfully");
                        }
                        else if(msg == 2)
                        {
                            jQuery('#twitter_success').html('');
                            jQuery('#twitter_error').html('Twitter responded with an error');
                        }
                         else if(msg == 3)
                        {
                            jQuery('#twitter_success').html('');
                            jQuery('#twitter_error').html('Please select twitter publish option');
                        }
                         
                        else
                        {
                            jQuery('#twitter_success').html('');
                            jQuery('#twitter_error').html('Twitter responded with an error : '+msg);
                        }
              },
              error:function (xhr, ajaxOptions, thrownError){
                   
                    jQuery('#twitter_success').html('');
                    jQuery('#twitter_error').html('Error in settings. Please check your settings and try again');
                }
            });

}
function testLinkedin()
{
    jQuery("#linkedin_success").html("<img src='/typo3conf/ext/social_publisher/Resources/Public/Icons/loading.gif' />");
   
var linkedin_consumer_key = jQuery("#linkedin_consumer_key").val();
var linkedin_consumer_secret = jQuery("#linkedin_consumer_secret").val();
var linkedin_pageid = jQuery("#linkedin_pageid").val();
var linkedin_accesstoken = jQuery("#linkedin_accesstoken").val();
var social_settings_linkedin = jQuery('#social_settings_linkedin').is(":checked");

var ajaxUrl = TYPO3.settings.ajaxUrls['SocialpublisherBackendController::linkedintestpublishAction'];


 jQuery.ajax({
              method: "POST",
              url: ajaxUrl,
              data:  {"linkedin_consumer_key":linkedin_consumer_key, "linkedin_consumer_secret":linkedin_consumer_secret, "linkedin_pageid":linkedin_pageid,"linkedin_accesstoken":linkedin_accesstoken,"social_settings_linkedin":social_settings_linkedin},
              success:function( msg ) {


                    if(msg == 1){            
                            jQuery('#linkedin_error').html('');
                            jQuery('#linkedin_success').html("Test Content Posted Successfully");
                        }
                        else if(msg == 2)
                        {
                            jQuery('#linkedin_success').html('');
                            jQuery('#linkedin_error').html('Linkedin responded with an error');
                        }
                         else if(msg == 3)
                        {
                            jQuery('#linkedin_success').html('');
                            jQuery('#linkedin_error').html('Please select Linkedin publish option');
                        }
                         
                        else
                        {
                            jQuery('#linkedin_success').html('');
                            jQuery('#linkedin_error').html('Linkedin responded with an error : '+msg);
                        }




                 },
              error:function (xhr, ajaxOptions, thrownError){
                   
                    jQuery('#linkedin_success').html('');
                    jQuery('#linkedin_error').html('Error in settings. Please check your settings and try again');
                }
            });
}

function testXing()
{
    jQuery("#xing_success").html("<img src='/typo3conf/ext/social_publisher/Resources/Public/Icons/loading.gif' />");
   
var xing_consumer_key = jQuery("#xing_consumer_key").val();
var xing_consumer_secret = jQuery("#xing_consumer_secret").val();
var xing_callback_url = jQuery("#xing_callback_url").val();
var xing_usa_id_ref=jQuery("#xing_usa_id_ref").val();

var social_settings_xing = jQuery('#social_settings_xing').is(":checked");

var ajaxUrl = TYPO3.settings.ajaxUrls['SocialpublisherBackendController::xingtestpublishAction'];

 jQuery.ajax({
             /* method: "POST",*/
              url: ajaxUrl,
              data:  {"xing_consumer_key":xing_consumer_key, "xing_consumer_secret":xing_consumer_secret, "xing_callback_url":xing_callback_url,"social_settings_xing":social_settings_xing, "xing_usa_id_ref":xing_usa_id_ref},
              success:function( msg ) {

                 if(msg == 1){            
                            jQuery('#xing_error').html('');
                            jQuery('#xing_success').html("Test Content Posted Successfully");
                        }
                        
                         else if(msg == 3)
                        {
                            jQuery('#xing_success').html('');
                            jQuery('#xing_error').html('Please select xing publish option');
                        }
                        else
                        {
                            jQuery('#xing_success').html('');
                            jQuery('#xing_error').html('Error occurred');
                        }

                 },
              error:function (xhr, ajaxOptions, thrownError){
                    
                    jQuery('#xing_success').html('');
                    jQuery('#xing_error').html('Error occurred');
                }
            });

}
