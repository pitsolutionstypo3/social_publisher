<?php
namespace PITSolutions\SocialPublisher\Domain\Model;


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
 * Log
 */
class Log extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * platform
     *
     * @var
     */
    protected $platform = null;
    
    /**
     * response
     *
     * @var
     */
    protected $response = null;
    
    /**
     * responsetime
     *
     * @var
     */
    protected $responsetime = null;
    
    /**
     * Returns the platform
     *
     * @return  $platform
     */
    public function getPlatform()
    {
        return $this->platform;
    }
    
    /**
     * Sets the platform
     *
     * @param string $platform
     * @return void
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }
    
    /**
     * Returns the response
     *
     * @return  $response
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * Sets the response
     *
     * @param string $response
     * @return void
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }
    
    /**
     * Returns the responsetime
     *
     * @return  $responsetime
     */
    public function getResponsetime()
    {
        return $this->responsetime;
    }
    
    /**
     * Sets the responsetime
     *
     * @param string $responsetime
     * @return void
     */
    public function setResponsetime($responsetime)
    {
        $this->responsetime = $responsetime;
    }

}