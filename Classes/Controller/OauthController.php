<?php
namespace PITSolutions\SocialPublisher\Controller;
use TYPO3\CMS\Core\Utility\GeneralUtility;


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
 * OauthController
 */
class OauthController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController 
{

/**
   * socialpublisherRepository
   *
   * @var \PITSolutions\SocialPublisher\Domain\Repository\SocialpublisherRepository
   * @inject
   */
  protected $socialpublisherRepository = NULL;

public function createoauthTable($extname = null ){
        if($extname == 'social_publisher'){
      

          $oauthTables = $this->socialpublisherRepository->setOauthtables();
          
        

        }

        
        }



}