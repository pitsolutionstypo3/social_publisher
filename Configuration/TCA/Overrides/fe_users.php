<?php
if (!defined('TYPO3_MODE')) {
        die ('Access denied.');
}


// Add some fields to FE Users table to show TCA fields definitions
// USAGE: TCA Reference > $TCA array reference > ['columns'][fieldname]['config'] / TYPE: "select"
$temporaryColumns = array (
        
        'tx_examples_special' => array (
                'exclude' => 0,
                'label' => 'Generalss',
                'config' => array (
                        'type' => 'user',
                        'size' => '30',                       
                        'parameters' => array(
                                'color' => 'blue'
                        )
                )
        ),
);

/*\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'fe_users',
        $temporaryColumns
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users',
        'tx_examples_options, tx_examples_special'
);*/