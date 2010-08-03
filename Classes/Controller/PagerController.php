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

	/**
	 * Shows a pager as a frontend plugin
	 *
	 * @return string Rendered pager action HTML source
	 */
	public function showAction() {
		$pager = $this->getPagerInstance();
		$this->view->assign('pager', $pager);
	}
	
	
	
	/**
	 * Updates the pager model.
	 * 
	 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
	 * @param string $page
	 * @dontvalidate $page
	 * @return string Rendered pager action HTML source
	 */
	public function submitAction(string $page) {
		//TODO: check why this method is not called
		$this->redirect('showAction');
	}
	
	
	
	/**
	 * Returns an initialized pager object
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
	 */
	protected function getPagerInstance() {
		$pagerConfiguration = new Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration($this->configurationBuilder);
        $pager = Tx_PtExtlist_Domain_Model_Pager_PagerFactory::getInstance($this->configurationBuilder, $pagerConfiguration);
        $pager->setItemCount($this->dataBackend->getTotalItemsCount());
        return $pager;
	}
}

?>