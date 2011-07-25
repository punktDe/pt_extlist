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
 * @author Christoph Ehscheidt 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Configuration\Renderer
 */
class Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {

	/**
	 * @var boolean 
	 */
	protected $enabled;

	
	
	/**
	 * Name of the renderer Class name
	 * @var string
	 */
	protected $rendererClassName;
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Renderer_RenderConfigCollection
	 */
	protected $renderConfigCollection;
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Tx_PtExtbase_Configuration_AbstractConfiguration::init()
	 */
	protected function init() {

		$this->setBooleanIfExistsAndNotNothing('enabled');
		
		$this->setRequiredValue('rendererClassName', 'No class name given for renderer. 1280408323');
		Tx_PtExtbase_Assertions_Assert::isTrue(class_exists($this->rendererClassName), array('message' => 'Given renderer class ' . $this->rendererClassName . ' does not exist or is not loaded! 1279541306'));
	}

	
	
	/**
	 * @return boolean enables
	 */
	public function isEnabled() {
		return $this->enabled;
	}
	
	
	
	/**
	 * @return string rendererClassName
	 */
	public function getRendererClassName() {
		return $this->rendererClassName;
	}
}
?>