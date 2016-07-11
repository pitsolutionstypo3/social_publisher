<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'PITSolutions.' . $_EXTKEY,
	'Socialpublisher',
	'Social Publisher'
);

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'PITSolutions.' . $_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'socialpublisher',	// Submodule key
		'',						// Position
		array(
			'SocialpublisherBackend' => 'list, show, publishto, testpublish, twittertestpublish, linkedintestpublish, xingtestpublish, saveerror',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_socialpublisher.xlf',
		)
	);

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Social Publisher');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_socialpublisher_domain_model_socialpublisher', 'EXT:social_publisher/Resources/Private/Language/locallang_csh_tx_socialpublisher_domain_model_socialpublisher.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_socialpublisher_domain_model_socialpublisher');

/*Register ajax handler - Publish to*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
        'SocialpublisherBackendController::publishtoAction',
        'PITSolutions\\SocialPublisher\\Controller\\SocialpublisherBackendController->publishtoAction'
);


/*Register ajax handler - Test Publish*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
        'SocialpublisherBackendController::testpublishAction',
        'PITSolutions\\SocialPublisher\\Controller\\SocialpublisherBackendController->testpublishAction'
);

/*Register ajax handler - Twitter test Publish*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
        'SocialpublisherBackendController::twittertestpublishAction',
        'PITSolutions\\SocialPublisher\\Controller\\SocialpublisherBackendController->twittertestpublishAction'
);

/*Register ajax handler - Linkedin test Publish*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
        'SocialpublisherBackendController::linkedintestpublishAction',
        'PITSolutions\\SocialPublisher\\Controller\\SocialpublisherBackendController->linkedintestpublishAction'
);

/*Register ajax handler - Xing test Publish*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
        'SocialpublisherBackendController::xingtestpublishAction',
        'PITSolutions\\SocialPublisher\\Controller\\SocialpublisherBackendController->xingtestpublishAction'
);

/*Register ajax handler - Manage internal server error and save result*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
        'SocialpublisherBackendController::saveerrorAction',
        'PITSolutions\\SocialPublisher\\Controller\\SocialpublisherBackendController->saveerrorAction'
);
