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
 * Class implements factory for renderer chain. 
 *
 * @package Domain
 * @subpackage Renderer
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Renderer_RendererChainFactory implements t3lib_Singleton {

	/**
	 * @var Tx_Extbase_Object_ObjectManager;
	 */
	protected $objectManager;



	/**
	 * @var Tx_PtExtlist_Domain_Renderer_RendererFactory
	 */
	protected $rendererFactory;



	/**
	 * @param Tx_Extbase_Object_ObjectManager $objectManager
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManager $objectManager) {
		$this->objectManager = $objectManager;
	}



	/**
	 * @param Tx_PtExtlist_Domain_Renderer_RendererFactory $rendererFactory
	 */
	public function injectRendererFactory(Tx_PtExtlist_Domain_Renderer_RendererFactory $rendererFactory) {
		$this->rendererFactory = $rendererFactory;
	}



	/**
	 * Creates an instance of renderer chain object for given renderer chain configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig $rendererChainConfiguration
	 * @return Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	public function getRendererChain(Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig $rendererChainConfiguration) {
		$rendererChain = $this->objectManager->get('Tx_PtExtlist_Domain_Renderer_RendererChain', $rendererChainConfiguration);
		//$rendererChain = new Tx_PtExtlist_Domain_Renderer_RendererChain($rendererChainConfiguration);
		foreach ($rendererChainConfiguration as $rendererConfiguration) {
			$renderer = $this->rendererFactory->getRenderer($rendererConfiguration);
			$rendererChain->addRenderer($renderer);
		}
		return $rendererChain;
	}
	
}