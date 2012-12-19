<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2012 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
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
 * This controller is meant to be used in a backend module, as in backend context we have only one controller
 * on one site we can
 *
 * @package Controller
 * @author Daniel Lienert
 */
abstract class Tx_PtExtlist_Controller_AbstractBackendListController extends Tx_PtExtlist_Controller_AbstractListApplicationController {

	public function initializeAction() {
		parent::initializeAction();
		$this->headerInclusionUtility->addCSSFile('EXT:pt_extlist/Resources/Public/CSS/Layout/Backend.css');
	}

}
?>