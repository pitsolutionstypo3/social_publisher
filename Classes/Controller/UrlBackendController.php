<?php
namespace PITSolutions\SocialPublisher\Controller;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Frontend\Utility\EidUtility;

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
 * SocialpublisherBackendController
 */
class UrlBackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController 
{

/*
* Initialise tsfe
*/
function initTSFE($id = 1, $typeNum = 0) {
   EidUtility::initTCA();
   if (!is_object($GLOBALS['TT'])) {
       $GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
       $GLOBALS['TT']->start();
   }
   $GLOBALS['TSFE'] = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], $id, $typeNum);
   $GLOBALS['TSFE']->connectToDB();
   $GLOBALS['TSFE']->initFEuser();
   $GLOBALS['TSFE']->determineId();
   $GLOBALS['TSFE']->initTemplate();
   $GLOBALS['TSFE']->getConfigArray();
   if (ExtensionManagementUtility::isLoaded('realurl')) {
       $rootline = BackendUtility::BEgetRootLine($id);
       $host = BackendUtility::firstDomainRecord($rootline);
       $_SERVER['HTTP_HOST'] = $host;
   }
}


public function createLink($detailUid) {
   $this->initTSFE();
   $domain='';

   /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj*/
   $cObj = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

   $my_url = $cObj->typoLink_URL(array(
       'parameter' => intval($detailUid),
       //'additionalParams' => '&kvt='.$my_additionalParameters,
       //'additionalParams.insertData' => 1,
       'returnLast' => 'url',
       'useCacheHash' => 1
   ));

if(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))
{
 $domain= \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
}

   //returns the url
   return $domain.$my_url; 
}


public function createTtle() {
     $this->initTSFE();
     $arr  = $GLOBALS['TSFE']->rootLine;
     $titlArr = array_shift(array_values( $arr ));
     return $titlArr['title'];
   
   }

   public function getfiledetailsbyId($uid) {

     $fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
    //$fileObjects = $fileRepository->findByRelation('pages', 'tx_socialpublisher_file', $page_settings['uid']);
      $ref=$fileRepository->findFileReferenceByUid($uid);  

          if(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))
        {
         $domain= \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        }

       return $domain.'fileadmin'.$ref->getIdentifier();
 

   }

}