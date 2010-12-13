<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
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
 * Class implements base view for all pt_extlist views. 
 * 
 * Class type for base view can be changed by changing pt_extlist AbstractController::resolveView()
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 * @package View
 */
class Tx_PtExtlist_View_BaseView extends Tx_Fluid_View_TemplateView {

	
	/*
	 * TODO: Fix the template and partial resolving for the next major version of fluid (from TYPO3 4.5) and clean this mess up!!!!!
	 */
	
	/**
	 * Directory pattern for global partials. Not part of the public API, should not be changed for now.
	 * @var string
	 */
	private $partialPathAndFilenamePattern = '@partialRoot/@partial.@format';
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	
	/**
	 * Inject the configurationBuilder
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function injectConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
	}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/View/Tx_Fluid_View_TemplateView::initializeView()
	 */
	public function initializeView() {
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/View/Tx_Fluid_View_TemplateView::getPartialSource()
	 * 
	 * TODO: make it clean for the next major version
	 * @param string $partialName The name of the partial
	 * @return string the full path which should be used. The path definitely exists.
	 * @throws Tx_Fluid_View_Exception_InvalidTemplateResourceException
	 */
	protected function getPartialSource($partialName) {
		$paths = $this->expandGenericPathPattern($this->partialPathAndFilenamePattern, TRUE, TRUE);
	
		$paths[] = $this->resolvePartialPathAndFilename($partialName);
		
		$found = FALSE;
		foreach ($paths as &$partialPathAndFilename) {
			$partialPathAndFilename = str_replace('@partial', $partialName, $partialPathAndFilename);
			if (file_exists($partialPathAndFilename)) {
				$found = TRUE;
				break;
			}
		}
		if (!$found) {
			throw new Tx_Fluid_View_Exception_InvalidTemplateResourceException('The template files "' . implode('", "', $paths) . '" could not be loaded.', 1225709595);
		}
		$partialSource = file_get_contents($partialPathAndFilename);
		if ($partialSource === FALSE) {
			throw new Tx_Fluid_View_Exception_InvalidTemplateResourceException('"' . $partialPathAndFilename . '" is not a valid template resource URI.', 1257246929);
		}
		return $partialSource;
	}
	
	
	
	/**	  
	 * 
     * Figures out which partial to use.
     * 
     * We overwrite this method to make sure that we can use something like this in our partial:
     * 
     * partialPath = EXT:pt_extlist/Resources/Private/Partials/Filter/StringFilter.html
     *
     * @param string $partialName The name of the partial
     * @return string the full path which should be used. The path definitely exists.
     * @throws Tx_Fluid_View_Exception_InvalidTemplateResourceException
     */
	protected function resolvePartialPathAndFilename($partialName) {
		if (file_exists($partialName)) { // partial is given as absolute path (rather unusual :-) )
			return $partialName;
		} elseif (file_exists(t3lib_div::getFileAbsFileName($partialName))) { // partial is given as EXT:pt_extlist/Resources/Private/Partials/Filter/StringFilter.html
			return t3lib_div::getFileAbsFileName($partialName);
		} else {
			return $partialName;
		}
	}
	
	
	/**
     * Resolve the template path and filename for the given action. If $actionName
     * is NULL, looks into the current request.
     * 
     * Tries to read template path and filename from current settings.
     * Path can be set there by $controller->setTemplatePathAndFilename(Path to template)
     *
     * @param string $actionName Name of the action. If NULL, will be taken from request.
     * @return string Full path to template
     * @throws Tx_Fluid_View_Exception_InvalidTemplateResourceException
     */
	protected function resolveTemplatePathAndFilename($actionName = NULL) {
		if ($this->templatePathAndFilename != '') {
			if (file_exists($this->templatePathAndFilename)) {
			    return $this->configurationBuilder->getSettings('__templatePathAndFileName');
			} elseif (file_exists(t3lib_div::getFileAbsFileName($this->templatePathAndFilename))) { 
				return t3lib_div::getFileAbsFileName($this->templatePathAndFilename);
			}
		} else {
			return parent::resolveTemplatePathAndFilename($actionName);
		}
	}
	
}