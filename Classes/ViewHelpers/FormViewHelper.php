<?php

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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
		if ($this->arguments->hasArgument('actionUri')) {
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
		
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[extensionName]') . '" value="' . $extensionName . '" />' . chr(10);
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[controllerName]') . '" value="' . $controllerName . '" />' . chr(10);
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[actionName]') . '" value="' . $actionName . '" />' . chr(10);
			
		return $result;
	}
}
?>