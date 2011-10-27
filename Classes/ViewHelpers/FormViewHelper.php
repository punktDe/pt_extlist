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
 * Form Viewhelper Patch
 * This viewhelper patches the original viewhelper which does not work with method GET.
 * Bug filed on 2010-12-22
 * 
 * @author Daniel Lienert 
 * @package ViewHelpers
 */
class Tx_PtExtlist_ViewHelpers_FormViewHelper extends Tx_Fluid_ViewHelpers_FormViewHelper {

	
	protected  $formActionUri;
	
	
	/**
	 * Sets the "action" attribute of the form tag
	 *
	 * @return void
	 */
	protected function setFormActionUri() {
		if (array_key_exists('actionUri', $this->arguments)) {
			$formActionUri = $this->arguments['actionUri'];
		} else {
			$uriBuilder = $this->controllerContext->getUriBuilder();
			$formActionUri = $uriBuilder
				->reset()
				->setTargetPageUid($this->arguments['pageUid'])
				->setTargetPageType($this->arguments['pageType'])
				->setNoCache($this->arguments['noCache'])
				->setUseCacheHash(!$this->arguments['noCacheHash'])
				->setSection($this->arguments['section'])
				->setCreateAbsoluteUri($this->arguments['absolute'])
				->setArguments((array)$this->arguments['additionalParams'])
				->setAddQueryString($this->arguments['addQueryString'])
				->setArgumentsToBeExcludedFromQueryString((array)$this->arguments['argumentsToBeExcludedFromQueryString'])
				->setFormat($this->arguments['format'])
				->uriFor($this->arguments['action'], $this->arguments['arguments'], $this->arguments['controller'], $this->arguments['extensionName'], $this->arguments['pluginName']);
			$this->formActionUriArguments = $uriBuilder->getArguments();
		}
		
		$this->formActionUri = $formActionUri;
		$this->tag->addAttribute('action', $formActionUri);
	}
	

	/**
	 * Renders hidden form fields for referrer information about
	 * the current controller and action.
	 *
	 * @return string Hidden fields with referrer information
	 * @todo filter out referrer information that is equal to the target (e.g. same packageKey)
	 */
	protected function renderHiddenReferrerFields() {
		
		$request = $this->controllerContext->getRequest();
		$extensionName = $request->getControllerExtensionName();
		$controllerName = $request->getControllerName();
		$actionName = $request->getControllerActionName();
		
		$result = chr(10);
		
		// quick ugly hack 
		if(strtolower($this->arguments['method']) === 'get') {
			$uriTemp = explode('?', $this->formActionUri);
			$uriTemp = explode('&', $uriTemp[1]);
			
			foreach($uriTemp as $parameter) {
				$paramTemp = explode('=', $parameter);
				$result .= '<input type="hidden" name="' . $paramTemp[0] . '" value="' . $paramTemp[1] . '" />' . chr(10);
			}
		}
		
		
		$extBaseContext = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_PtExtlist_Extbase_ExtbaseContext');
		
		if($extBaseContext->isInCachedMode()) {
			$listIdentifier = $extBaseContext->getCurrentListIdentifier();
			$stateHash = Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance()->getSessionDataHash();
			
			$result .=  '<input type="hidden" name="' . $this->prefixFieldName($listIdentifier.'[state]') . '" value="' . $stateHash . '" />' . chr(10);
		}
		
		
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[extensionName]') . '" value="' . $extensionName . '" />' . chr(10);
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[controllerName]') . '" value="' . $controllerName . '" />' . chr(10);
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[actionName]') . '" value="' . $actionName . '" />' . chr(10);	
		return $result;
	}
}
?>