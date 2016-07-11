<?php
if (!defined('TYPO3_MODE')) {
        die ('Access denied.');
}



/**/
$GLOBALS['TCA']['pages']['types'][(string)\TYPO3\CMS\Frontend\Page\PageRepository::DOKTYPE_DEFAULT]['showitem'].=
',--div--;LLL:EXT:social_publisher/Resources/Private/Language/locallang_db.xlf:pages.tabs.socialtab,
--palette--;Social;introheader,';

/*$GLOBALS['TCA']['pages']['types'] = array(
        // normal
        (string)\TYPO3\CMS\Frontend\Page\PageRepository::DOKTYPE_DEFAULT => array(
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.standard;standard,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.title;title,
                                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.visibility;visibility,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access;access,
                                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.abstract;abstract,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.metatags;metatags,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.editorial;editorial,
                                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.appearance,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.layout;layout,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.replace;replace,
                                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.behaviour,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.links;links,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.caching;caching,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.language;language,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.miscellaneous;miscellaneous,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.module;module,
                                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.resources,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.media;media,
                                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.config;config,
                                --div--;Social share,
                                --palette--;introheader;introheader,
                ')
        );*/

$temporaryColumns = array (
        
        'tx_socialpublisher_disable_facebook' => array (
                'exclude' => 0,
                'label' => 'LLL:EXT:social_publisher/Resources/Private/Language/locallang_db.xlf:pages.tx_socialpublisher_disable_facebook',
                'config' => array (
                        'type' => 'check',
                                        
                        
                )
        ),
        'tx_socialpublisher_disable_twitter' => array (
                'exclude' => 0,
                'label' => 'LLL:EXT:social_publisher/Resources/Private/Language/locallang_db.xlf:pages.tx_socialpublisher_disable_twitter',
                'config' => array (
                        'type' => 'check',
                                           
                        
                )
        ),

      /*  'tx_socialpublisher_disable_gplus' => array (
                'exclude' => 0,
                'label' => 'LLL:EXT:social_publisher/Resources/Private/Language/locallang_db.xlf:pages.tx_socialpublisher_disable_gplus',
                'config' => array (
                        'type' => 'check',
                                           
                        
                )
        ),*/

        'tx_socialpublisher_disable_linkedin' => array (
                'exclude' => 0,
                'label' => 'LLL:EXT:social_publisher/Resources/Private/Language/locallang_db.xlf:pages.tx_socialpublisher_disable_linkedin',
                'config' => array (
                        'type' => 'check',
                                           
                        
                )
        ),

        'tx_socialpublisher_disable_xing' => array (
                'exclude' => 0,
                'label' => 'LLL:EXT:social_publisher/Resources/Private/Language/locallang_db.xlf:pages.tx_socialpublisher_disable_xing',
                'config' => array (
                        'type' => 'check',
                                           
                        
                )
        ),
//File upload
 'tx_socialpublisher_file' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:test/Resources/Private/Language/locallang_db.xlf:pages.tx_socialpublisher_image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'tx_socialpublisher_file',
                array(
                'type' => 'inline',
                    'appearance' => array(
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                    ),
                    'foreign_types' => array(
                        '0' => array(
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => array(
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => array(
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => array(
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => array(
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => array(
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        )
                    ),
                    'maxitems' => 1
                ),
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ),
       
//File upload ends





        'tx_socialpublisher_publish' => array(
                'label' => 'LLL:EXT:social_publisher/Resources/Private/Language/locallang_db.xlf:pages.tx_socialpublisher_publish',
                'config' => array(
                        'type' => 'user',
                        'userFunc' => 'PITSolutions\SocialPublisher\Utility\Publish->render',
                        'parameters' => array(
                                'latitude' => 'lat',
                                 'longitude' => 'lng',
                                ),
                 ),
        ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'pages',
        $temporaryColumns
);
/*\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'tx_socialpublisher_disable_facebook, tx_socialpublisher_disable_twitter, tx_socialpublisher_disable_gplus, tx_socialpublisher_disable_linkedin, tx_socialpublisher_disable_xing'
);*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'introheader',
        '--linebreak--,tx_socialpublisher_disable_facebook'
        /*'after:subtitle'*/
        
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'introheader',
        '--linebreak--,tx_socialpublisher_disable_twitter',
        'after:tx_socialpublisher_disable_facebook'
);

/*\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'introheader',
        '--linebreak--,tx_socialpublisher_disable_gplus',
        'after:tx_socialpublisher_disable_twitter'
);*/

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'introheader',
        '--linebreak--,tx_socialpublisher_disable_linkedin',
        'after:tx_socialpublisher_disable_twitter'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'introheader',
        '--linebreak--,tx_socialpublisher_disable_xing',
        'after:tx_socialpublisher_disable_linkedin'
);

//File
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'introheader',
        '--linebreak--,tx_socialpublisher_file',
        'after:tx_socialpublisher_disable_xing'
);


//File ends

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'introheader',
        '--linebreak--,tx_socialpublisher_publish',
        'after:tx_socialpublisher_file'
);


