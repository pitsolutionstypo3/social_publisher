<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'PITSolutions.' . $_EXTKEY,
	'Socialpublisher',
	array(
		'Socialpublisher' => 'list, show',
		
	),
	// non-cacheable actions
	array(
		'Socialpublisher' => '',
		
	)
);


/*$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY]
=
'EXT:social_publisher/Classes/Hook/class.tx_socialpublisher_hook.php:tx_socialpublisher_hook';*/

if (TYPO3_MODE === 'BE') {
    $class = 'TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher';
    $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($class);
    $dispatcher->connect(
        'TYPO3\\CMS\\Extensionmanager\\Service\\ExtensionManagementService',
        'hasInstalledExtensions',
        'PITSolutions\\SocialPublisher\\Controller\\OauthController',
        'createoauthTable'
    );
}

