<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
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
 * Interface for configuration objects wich hold configuration for value rendering
 *
 * @package Domain
 * @subpackage Configuration
 * @author Daniel Lienert 
 */
interface Tx_PtExtlist_Domain_Configuration_RenderConfigInterface {
	
	/**
	 * Returns a configuration array in typoscript config array notation:
	 * array {
	 * 	'renderObj' => 'TEXT|COA|....'
	 *  'renderObj.' => {
	 *  	...
	 *  }
	 * @return array cObj Configuration
	 */
	public function getRenderObj();
	
	
	/**
	 * Returns a configuration array for user functions
	 * 
	 * @return array userFunctions Configuration
	 */
	public function getRenderUserFunctions();
	
	
	/**
	 * Returns a path to a fluid template file
	 * 
	 * @returns string template
	 */
	public function getRenderTemplate();



	/**
	 * Returns true, if rendering should be cached
	 *
	 * @return bool True, if rendering should be cached
	 */
	public function getCacheRendering();

}
?>
