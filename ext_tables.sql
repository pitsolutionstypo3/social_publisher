#
# Table structure for table 'tx_socialpublisher_domain_model_socialpublisher'
#
CREATE TABLE tx_socialpublisher_domain_model_socialpublisher (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	facebookpage int(11) unsigned DEFAULT '0',
	twitterpage int(11) unsigned DEFAULT '0',
	linkedinpage int(11) unsigned DEFAULT '0',
	xingpage int(11) unsigned DEFAULT '0',


	social_settings_facebook int(11) unsigned DEFAULT '0',
	social_settings_twitter int(11) unsigned DEFAULT '0',
	social_settings_linkedin int(11) unsigned DEFAULT '0',
	social_settings_gplus int(11) unsigned DEFAULT '0',
	social_settings_xing int(11) unsigned DEFAULT '0',


	facebook_appid varchar(255) DEFAULT '' NOT NULL,
	facebook_appsecret varchar(255) DEFAULT '' NOT NULL,
	facebook_accesstoken varchar(255) DEFAULT '' NOT NULL,
	facebook_message text DEFAULT '' NOT NULL,


	twitter_consumer_key varchar(255) DEFAULT '' NOT NULL,
	twitter_consumer_secret varchar(255) DEFAULT '' NOT NULL,
	twitter_access_token varchar(255) DEFAULT '' NOT NULL,
	twitter_access_token_secret varchar(255) DEFAULT '' NOT NULL,
	twitter_message text DEFAULT '' NOT NULL,

	linkedin_consumer_key varchar(255) DEFAULT '' NOT NULL,
	linkedin_consumer_secret varchar(255) DEFAULT '' NOT NULL,
	linkedin_callback_url varchar(255) DEFAULT '' NOT NULL,
	linkedin_pageid varchar(255) DEFAULT '' NOT NULL,
	linkedin_accesstoken varchar(255) DEFAULT '' NOT NULL,
	linkedin_message text DEFAULT '' NOT NULL,

	xing_consumer_key varchar(255) DEFAULT '' NOT NULL,
	xing_consumer_secret varchar(255) DEFAULT '' NOT NULL,
	xing_callback_url varchar(255) DEFAULT '' NOT NULL,
	xing_accesstoken varchar(255) DEFAULT '' NOT NULL,
	xing_accesstoken_secret varchar(255) DEFAULT '' NOT NULL,
	xing_userid varchar(255) DEFAULT '' NOT NULL,
	xing_message text DEFAULT '' NOT NULL,
	xing_usa_id_ref int(11) unsigned DEFAULT '0' NOT NULL,	
	



	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_socialpublisher_domain_model_log'
#
CREATE TABLE tx_socialpublisher_domain_model_log (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	platform varchar(255) DEFAULT '' NOT NULL,
	response text DEFAULT '' NOT NULL,
	responsetime int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);


CREATE TABLE pages (
        tx_socialpublisher_disable_facebook int(11) DEFAULT '0' NOT NULL,
        tx_socialpublisher_disable_twitter int(11) DEFAULT '0' NOT NULL,
        tx_socialpublisher_disable_gplus int(11) DEFAULT '0' NOT NULL,
        tx_socialpublisher_disable_linkedin int(11) DEFAULT '0' NOT NULL,
        tx_socialpublisher_disable_xing int(11) DEFAULT '0' NOT NULL
        tx_socialpublisher_file int(11) DEFAULT '0' NOT NULL
);

