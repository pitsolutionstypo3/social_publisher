<?php
namespace PITSolutions\SocialPublisher\Domain\Repository;

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
 * The repository for Socialpublishers
 */
class SocialpublisherRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
     * @param $args
     */
    public function savesocialSettings($args) {

    	$toInsertArray['social_settings_facebook']	= $args['social_settings_facebook'];
		$toInsertArray['social_settings_twitter']	= $args['social_settings_twitter'];
		$toInsertArray['social_settings_linkedin']		= $args['social_settings_linkedin'];
		$toInsertArray['social_settings_gplus']	= $args['social_settings_gplus'];
		$toInsertArray['social_settings_xing']	=$args['social_settings_xing'];

		$toInsertArray['facebook_appid']		= $args['facebook_appid'];
		$toInsertArray['facebook_appsecret']	= $args['facebook_appsecret'];
		$toInsertArray['facebook_accesstoken']	= $args['facebook_accesstoken'];
		$toInsertArray['facebook_message']	= $args['facebook_message'];

		$toInsertArray['twitter_consumer_key']		= $args['twitter_consumer_key'];
		$toInsertArray['twitter_consumer_secret']	= $args['twitter_consumer_secret'];
		$toInsertArray['twitter_access_token']	= $args['twitter_access_token'];
		$toInsertArray['twitter_access_token_secret']	= $args['twitter_access_token_secret'];
		$toInsertArray['twitter_message']	= $args['twitter_message'];

		$toInsertArray['linkedin_consumer_key']	= $args['linkedin_consumer_key'];
		$toInsertArray['linkedin_consumer_secret']	= $args['linkedin_consumer_secret'];
		//$toInsertArray['linkedin_callback_url']	= $args['linkedin_callback_url'];
		$toInsertArray['linkedin_message']	= $args['linkedin_message'];

	$toInsertArray['linkedin_pageid']	= $args['linkedin_pageid'];	
	$toInsertArray['linkedin_accesstoken']	= $args['linkedin_accesstoken'];	


		$toInsertArray['xing_consumer_key']	= $args['xing_consumer_key'];
		$toInsertArray['xing_consumer_secret']	= $args['xing_consumer_secret'];
		$toInsertArray['xing_callback_url']	= $args['xing_callback_url'];
		$toInsertArray['xing_accesstoken']	= $args['xing_accesstoken'];
		$toInsertArray['xing_accesstoken_secret']	= $args['xing_accesstoken_secret'];
		$toInsertArray['xing_userid']	= $args['xing_userid'];
		$toInsertArray['xing_message']	= $args['xing_message'];
		$toInsertArray['xing_usa_id_ref']	= $args['xing_usa_id_ref'];		

		if($args['settings_uid'])
		{
			$where_clause	='uid = '.$args['settings_uid'];
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery ('tx_socialpublisher_domain_model_socialpublisher',
			$where_clause,$toInsertArray, TRUE);
		}
		else{
		
		$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
		$GLOBALS['TYPO3_DB']->exec_INSERTquery 	( 'tx_socialpublisher_domain_model_socialpublisher', $toInsertArray);
		$GLOBALS['TYPO3_DB']->debug_lastBuiltQuery ;
		}
    }

    /**
     * 
     */
    public function getSocialSettings() {
    	$select_fields	= "*";
		$from_table		= "tx_socialpublisher_domain_model_socialpublisher";
		$where_clause	= "hidden = 0 AND deleted = 0";
		$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					$select_fields,
					$from_table,
					$where_clause,
					$groupBy = '',
					$orderBy = '',
					$limit = '1'					
					);
		if($result){
		return $result[0];
	}

    }

    public function setOauthtables()
    {
    	$GLOBALS['TYPO3_DB'] -> sql_query("CREATE TABLE IF NOT EXISTS oauth_log(
		    olg_id                  int(11) not null auto_increment,
		    olg_osr_consumer_key    varchar(64) binary,
		    olg_ost_token           varchar(64) binary,
		    olg_ocr_consumer_key    varchar(64) binary,
		    olg_oct_token           varchar(64) binary,
		    olg_usa_id_ref          int(11),
		    olg_received            text not null,
		    olg_sent                text not null,
		    olg_base_string         text not null,
		    olg_notes               text not null,
		    olg_timestamp           timestamp not null default current_timestamp,
		    olg_remote_ip           bigint not null,

		    primary key (olg_id),
		    key (olg_osr_consumer_key, olg_id),
		    key (olg_ost_token, olg_id),
		    key (olg_ocr_consumer_key, olg_id),
		    key (olg_oct_token, olg_id),
		    key (olg_usa_id_ref, olg_id)
		    
		#   , foreign key (olg_usa_id_ref) references any_user_auth (usa_id_ref)
		#       on update cascade
		#       on delete cascade
		) engine=InnoDB default charset=utf8");

		$GLOBALS['TYPO3_DB'] -> sql_query("CREATE TABLE IF NOT EXISTS oauth_consumer_registry(
		    ocr_id                  int(11) not null auto_increment,
		    ocr_usa_id_ref          int(11),
		    ocr_consumer_key        varchar(128) binary not null,
		    ocr_consumer_secret     varchar(128) binary not null,
		    ocr_signature_methods   varchar(255) not null default 'HMAC-SHA1,PLAINTEXT',
		    ocr_server_uri          varchar(255) not null,
		    ocr_server_uri_host     varchar(128) not null,
		    ocr_server_uri_path     varchar(128) binary not null,

		    ocr_request_token_uri   varchar(255) not null,
		    ocr_authorize_uri       varchar(255) not null,
		    ocr_access_token_uri    varchar(255) not null,
		    ocr_timestamp           timestamp not null default current_timestamp,

		    primary key (ocr_id),
		    unique key (ocr_consumer_key, ocr_usa_id_ref, ocr_server_uri),
		    key (ocr_server_uri),
		    key (ocr_server_uri_host, ocr_server_uri_path),
		    key (ocr_usa_id_ref)

		#   , foreign key (ocr_usa_id_ref) references any_user_auth(usa_id_ref)
		#       on update cascade
		#       on delete set null
		) engine=InnoDB default charset=utf8");



		$GLOBALS['TYPO3_DB'] -> sql_query("CREATE TABLE IF NOT EXISTS oauth_consumer_token(
			    oct_id                  int(11) not null auto_increment,
			    oct_ocr_id_ref          int(11) not null,
			    oct_usa_id_ref          int(11) not null,
			    oct_name                varchar(64) binary not null default '',
			    oct_token               varchar(64) binary not null,
			    oct_token_secret        varchar(64) binary not null,
			    oct_token_type          enum('request','authorized','access'),
			    oct_token_ttl           datetime not null default '9999-12-31',
			    oct_timestamp           timestamp not null default current_timestamp,

			    primary key (oct_id),
			    unique key (oct_ocr_id_ref, oct_token),
			    unique key (oct_usa_id_ref, oct_ocr_id_ref, oct_token_type, oct_name),
				key (oct_token_ttl),

			    foreign key (oct_ocr_id_ref) references oauth_consumer_registry (ocr_id)
			        on update cascade
			        on delete cascade

			#   , foreign key (oct_usa_id_ref) references any_user_auth (usa_id_ref)
			#       on update cascade
			#       on delete cascade           
			) engine=InnoDB default charset=utf8");

		/*$GLOBALS['TYPO3_DB'] -> sql_query("CREATE TABLE IF NOT EXISTS oauth_server_registry(
			    osr_id                      int(11) not null auto_increment,
			    osr_usa_id_ref              int(11),
			    osr_consumer_key            varchar(64) binary not null,
			    osr_consumer_secret         varchar(64) binary not null,
			    osr_enabled                 tinyint(1) not null default '1',
			    osr_status                  varchar(16) not null,
			    osr_requester_name          varchar(64) not null,
			    osr_requester_email         varchar(64) not null,
			    osr_callback_uri            varchar(255) not null,
			    osr_application_uri         varchar(255) not null,
			    osr_application_title       varchar(80) not null,
			    osr_application_descr       text not null,
			    osr_application_notes       text not null,
			    osr_application_type        varchar(20) not null,
			    osr_application_commercial  tinyint(1) not null default '0',
			    osr_issue_date              datetime not null,
			    osr_timestamp               timestamp not null default current_timestamp,

			    primary key (osr_id),
			    unique key (osr_consumer_key),
			    key (osr_usa_id_ref)

			#   , foreign key (osr_usa_id_ref) references any_user_auth(usa_id_ref)
			#       on update cascade
			#       on delete set null
			) engine=InnoDB default charset=utf8");

		$GLOBALS['TYPO3_DB'] -> sql_query("CREATE TABLE IF NOT EXISTS oauth_server_nonce(
			    osn_id                  int(11) not null auto_increment,
			    osn_consumer_key        varchar(64) binary not null,
			    osn_token               varchar(64) binary not null,
			    osn_timestamp           bigint not null,
			    osn_nonce               varchar(80) binary not null,

			    primary key (osn_id),
			    unique key (osn_consumer_key, osn_token, osn_timestamp, osn_nonce)
			) engine=InnoDB default charset=utf8");


		$GLOBALS['TYPO3_DB'] -> sql_query("CREATE TABLE IF NOT EXISTS oauth_server_token(
				    ost_id                  int(11) not null auto_increment,
				    ost_osr_id_ref          int(11) not null,
				    ost_usa_id_ref          int(11) not null,
				    ost_token               varchar(64) binary not null,
				    ost_token_secret        varchar(64) binary not null,
				    ost_token_type          enum('request','access'),
				    ost_authorized          tinyint(1) not null default '0',
					ost_referrer_host       varchar(128) not null default '',
					ost_token_ttl           datetime not null default '9999-12-31',
				    ost_timestamp           timestamp not null default current_timestamp,
				    ost_verifier            char(10),
				    ost_callback_url        varchar(512),

					primary key (ost_id),
				    unique key (ost_token),
				    key (ost_osr_id_ref),
					key (ost_token_ttl),

					foreign key (ost_osr_id_ref) references oauth_server_registry (osr_id)
				        on update cascade
				        on delete cascade

				#   , foreign key (ost_usa_id_ref) references any_user_auth (usa_id_ref)
				#       on update cascade
				#       on delete cascade           
				) engine=InnoDB default charset=utf8");*/
    

			return true;
    }


    /**
     * 
     */
    public function saveErrorlog($error_log) {
    	 $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_socialpublisher_domain_model_log', array('platform' => $error_log['platform'], 'response' => $error_log['response'], 'responsetime'=> time()));

			}
	
}