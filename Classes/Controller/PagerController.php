<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>, Christoph Ehscheidt <ehscheidt@punkt.de>
*  All rights reserved
*
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
 * Class Pager Controller
 *
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Controller_PagerController extends Tx_PtExtlist_Controller_AbstractController {

	public function showAction() {
		$this->view->assign('pager', $this->dataBackend->getPager());
	}
	
	/**
	 * 
	 * Updates the pager model.
	 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
	 * @param string $page
	 * @dontvalidate $page
	 */
	public function submitAction(string $page) {
		$pager = $this->dataBackend->getPager();
		die();
		
		$pager->setCurrentPage(5);	
	
		$sessionPersistenceManager = Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory::getInstance();
        $sessionPersistenceManager->persistToSession($pager);
        
        $this->redirect('show');
		
	}
}

?>