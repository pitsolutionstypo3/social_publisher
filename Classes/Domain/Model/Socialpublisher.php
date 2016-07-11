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
 * Socialpublisher
 */
class Socialpublisher extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * facebookpage
	 *
	 * @var
	 */
	protected $facebookpage = NULL;

	/**
	 * twitterpage
	 *
	 * @var
	 */
	protected $twitterpage = NULL;

	/**
	 * linkedinpage
	 *
	 * @var
	 */
	protected $linkedinpage = NULL;

	/**
	 * xingpage
	 *
	 * @var
	 */
	protected $xingpage = NULL;

	/**
	 * Returns the facebookpage
	 *
	 * @return $facebookpage
	 */
	public function getFacebookpage() {
		return $this->facebookpage;
	}

	/**
	 * Sets the facebookpage
	 *
	 * @param string $facebookpage
	 * @return void
	 */
	public function setFacebookpage($facebookpage) {
		$this->facebookpage = $facebookpage;
	}

	/**
	 * Returns the twitterpage
	 *
	 * @return $twitterpage
	 */
	public function getTwitterpage() {
		return $this->twitterpage;
	}

	/**
	 * Sets the twitterpage
	 *
	 * @param string $twitterpage
	 * @return void
	 */
	public function setTwitterpage($twitterpage) {
		$this->twitterpage = $twitterpage;
	}

	/**
	 * Returns the linkedinpage
	 *
	 * @return $linkedinpage
	 */
	public function getLinkedinpage() {
		return $this->linkedinpage;
	}

	/**
	 * Sets the linkedinpage
	 *
	 * @param string $linkedinpage
	 * @return void
	 */
	public function setLinkedinpage($linkedinpage) {
		$this->linkedinpage = $linkedinpage;
	}

	/**
	 * Returns the xingpage
	 *
	 * @return $xingpage
	 */
	public function getXingpage() {
		return $this->xingpage;
	}

	/**
	 * Sets the xingpage
	 *
	 * @param string $xingpage
	 * @return void
	 */
	public function setXingpage($xingpage) {
		$this->xingpage = $xingpage;
	}

}