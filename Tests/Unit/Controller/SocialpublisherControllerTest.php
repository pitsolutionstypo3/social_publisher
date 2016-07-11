<?php
namespace PITSolutions\SocialPublisher\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Siju E Raju <siju.er@pitsolutions.com>, PIT Solutions
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class PITSolutions\SocialPublisher\Controller\SocialpublisherController.
 *
 * @author Siju E Raju <siju.er@pitsolutions.com>
 */
class SocialpublisherControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \PITSolutions\SocialPublisher\Controller\SocialpublisherController
	 */
	protected $subject = NULL;

	public function setUp() {
		$this->subject = $this->getMock('PITSolutions\\SocialPublisher\\Controller\\SocialpublisherController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllSocialpublishersFromRepositoryAndAssignsThemToView() {

		$allSocialpublishers = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$socialpublisherRepository = $this->getMock('PITSolutions\\SocialPublisher\\Domain\\Repository\\SocialpublisherRepository', array('findAll'), array(), '', FALSE);
		$socialpublisherRepository->expects($this->once())->method('findAll')->will($this->returnValue($allSocialpublishers));
		$this->inject($this->subject, 'socialpublisherRepository', $socialpublisherRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('socialpublishers', $allSocialpublishers);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenSocialpublisherToView() {
		$socialpublisher = new \PITSolutions\SocialPublisher\Domain\Model\Socialpublisher();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('socialpublisher', $socialpublisher);

		$this->subject->showAction($socialpublisher);
	}
}
