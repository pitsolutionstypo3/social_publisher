<?php
namespace PITSolutions\SocialPublisher\Utility;

//use PITSolutions\SocialPublisher\Controller\SocialpublisherBackendController;
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

class Publish {

	/**
	 * Renders the Publish button.
	 *
	 * @param array $PA
	 * @param t3lib_TCEforms $pObj
	 * @return string
	 */
	public function render(&$PA, $pObj) {
		

		$out = array();
		//$latitude = (float) $PA['row'][$PA['parameters']['latitude']];
		//$longitude = (float) $PA['row'][$PA['parameters']['longitude']];

		//$baseElementId = isset($PA['itemFormElID']) ? $PA['itemFormElID'] : $PA['table'] . '_map';
		/*var MY_AJAX_ACTION_URL = '<f:uri.action action="publishto" controller="SocialpublisherBackendController" pageType="5000" />';*/
		//		
		//$obj= new PITSolutions\SocialPublisher\Controller\SocialpublisherBackendController;
		//$obj->publishto();
	//$out[] = \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($PA['row']);
$data_array= json_encode($PA['row']);

		$out[] = '<script type="text/javascript">';
		$out[] = 'var text = '.$data_array.';';
		$out[] = <<<EOT
		
		text=JSON.stringify(text);		
		var ajaxUrl = TYPO3.settings.ajaxUrls['SocialpublisherBackendController::publishtoAction'];
		var error_ajaxUrl = TYPO3.settings.ajaxUrls['SocialpublisherBackendController::saveerrorAction'];

		if (typeof Socialpublish == 'undefined') Socialpublish = {};
		Socialpublish.publisAll = function() {		

		/*var del = new Ajax.Request(TYPO3.settings.ajaxUrls['SocialpublisherBackendController::publishtoAction'], {
        parameters : '&text=' + text,
        onComplete : function(result){
		        	TYPO3.jQuery("#publish_message").html(result);
		    	}
		});	*/

			TYPO3.jQuery("#publish_message").html("<img src='/typo3conf/ext/social_publisher/Resources/Public/Icons/loading.gif' />");

			TYPO3.jQuery.ajax({				
				url: ajaxUrl,				
				data :"tx_socialpublisher="+text,				 						
				success: function(result){
					if(result == 1){
		        	TYPO3.jQuery("#publish_message").html("<img src='/typo3conf/ext/social_publisher/Resources/Public/Icons/success.png' />");
		        	}
		        	else
		        	{
		        		TYPO3.jQuery("#publish_message").html("<span class='error' style='color: red;'>Error occurred. Try again</span>");
		        	}
		    	},
		    	error:function (xhr, ajaxOptions, thrownError){ 

				    	TYPO3.jQuery.ajax({				
						url: error_ajaxUrl,				
						data :"thrownError="+thrownError,
						success: function(result){							
							}
						});
		    	              
                    TYPO3.jQuery("#publish_message").html("<span class='error' style='color: red;'>Error occurred. Try again</span>");
                }

			});
		}

EOT;
		$out[] = '</script>';
		$out[] = '<div id="">';
		$out[] = '			
			<input type="button" value="Publish to Socialmedia" onclick="Socialpublish.publisAll()">
			<div id="publish_message"></div>
			
		';		
		$out[] = '</div>'; // id=$baseElementId

		return implode('', $out);
	}

}

?>
