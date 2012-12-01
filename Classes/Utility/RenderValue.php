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
 * Utility to render values by renderObject or renderUserFunction
 * Caching for rendered Values is implemented here
 *
 * @package Utility
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Utility_RenderValue {

	/**
	 * @var tslib_cObj
	 */
	protected static $cObj;



	/**
	 * @var Tx_Fluid_View_TemplateView
	 */
	protected static $fluidRenderer;



	/**
	 * @var array
	 */
	protected static $renderCache = array();



	/**
	 * Render data by cObject or renderUserFunction and cache it
	 *
	 * @param array $data data values to be rendered
	 * @param array $renderObjectConfig config for cObj rendering
	 * @param array $renderUserFunctionConfig array and config for multiple renderuserfunctions
	 * @param string $renderTemplate
	 * @return string rendered value
	 */
	public static function render(array $data, array $renderObjectConfig = NULL, array $renderUserFunctionConfig = NULL, $renderTemplate = NULL) {

		try {
			/* if $data contains an extbase model object like we get them with the extbase backend, on some systems this
			 * causes an exception: Serialization of 'Closure' is not allowed
			 */
			$cacheKey = md5(serialize(func_get_args()));
		} catch(Exception $e) {
			return self::renderUncached($data, $renderObjectConfig, $renderUserFunctionConfig, $renderTemplate);
		}

		if(!self::$renderCache[$cacheKey]) {
			self::$renderCache[$cacheKey] = self::renderUncached($data, $renderObjectConfig, $renderUserFunctionConfig, $renderTemplate);
		}

		return self::$renderCache[$cacheKey];
	}



	/**
	 * Render the given Data by configuration from the configuration object
	 *
	 * @param array $data data to be rendered
	 * @param Tx_PtExtlist_Domain_Configuration_RenderConfigInterface $renderConfig
	 * @param bool $caching Set to true if you want to get caching for cell rendering. Default is FALSE
	 * @return string
	 */
	public static function renderByConfigObject(array $data, Tx_PtExtlist_Domain_Configuration_RenderConfigInterface $renderConfig, $caching = FALSE) {
		if ($caching) {
			return self::render($data, $renderConfig->getRenderObj(), $renderConfig->getRenderUserFunctions(), $renderConfig->getRenderTemplate());
		} else {
			return self::renderUncached($data, $renderConfig->getRenderObj(), $renderConfig->getRenderUserFunctions(), $renderConfig->getRenderTemplate());
		}
	}



	/**
	 * Render the given Data by configuration from the configuration object uncached
	 *
	 * @param array $data data to be rendered
	 * @param Tx_PtExtlist_Domain_Configuration_RenderConfigInterface $renderConfig
	 * @return string
	 */
	public static function renderByConfigObjectUncached(array $data, Tx_PtExtlist_Domain_Configuration_RenderConfigInterface $renderConfig) {
		return self::renderUncached($data, $renderConfig->getRenderObj(), $renderConfig->getRenderUserFunctions(), $renderConfig->getRenderTemplate());
	}


	/**
	 * Render data by cObject or renderUserFunction and cache it
	 *
	 * @param array $data data values to be rendered
	 * @param array $renderObjectConfig config for cObj rendering
	 * @param array $renderUserFunctionConfig array config for multiple renderuserfunction
	 * @param string $renderTemplate
	 * @return string rendered value
	 */
	public static function renderUncached(array $data, array $renderObjectConfig = NULL, array $renderUserFunctionConfig = NULL, $renderTemplate = NULL) {
		$content = '';

		if($renderUserFunctionConfig) {
			$content = self::renderValueByRenderUserFunctionArray($data, $renderUserFunctionConfig);
		}

		if($renderObjectConfig) {
			$content = self::renderValueByRenderObject($data, $renderObjectConfig, $content);
		}

		if($renderTemplate) {
			$content = self::renderValueByTemplate($data, $renderTemplate);
		}

		if(!$renderUserFunctionConfig && !$renderObjectConfig && !$renderTemplate) {
			$content = self::renderDefault($data);
		}

		return $content;
	}



	/**
	 * Render data in default mode, eg implode with ','
	 *
	 * @param array $data
	 * @return mixed rendered data
	 */
	public static function renderDefault($data) {
		$renderedFields = array();
		
		foreach($data as $fieldIdentifier => $field) {
			
			// If $data is an object - print all accessible attributes
			if(is_object($field)) {
				$renderedFields[] = self::renderDefaultObject($field);
				
			// If $data is an array of key/values write a key/value list
			} elseif(is_array($field)) {
				$renderedFields[] = self::renderDefaultArray($field);
	
			} else {
				$renderedFields[] = $field;
			}
		}
		
		if(count($renderedFields) > 1) {
			foreach($renderedFields as $key => $renderField) {
				if(is_object($renderField)) $renderedFields[$key] = get_class($renderField);
			}
			
			return implode(', ', $renderedFields);
		} else {
			return current($renderedFields);
		}
	}

	
	
	/**
	 * Print the given array as key-value list
	 * 
	 * @param array $array
	 * @return string
	 */
	protected static function renderDefaultArray(array $array) {
		$renderedFields = array();
		
		foreach($array as $label => $field) {
			$renderedFields[] = $label . ' : ' . $field;
		}

		return implode(', ', $renderedFields);
	}
	
	
	
	/**
	 * Render a key value list of the given object
	 * 
	 * @param object $object
	 * @return string
	 */
	protected static function renderDefaultObject($object) {
		return $object;
		$renderedFields = array();
		
		$objectMethods = get_class_methods(get_class($object));
		
		foreach($objectMethods as $objectMethod) {
			if(substr($objectMethod,0,3) == 'get') {
				$key = substr($objectMethod,3);
				$value = $object->$objectMethod();
				
				if(is_object($value)) $value = $key . ' (OBJECT)';
				$renderedFields[] = $key . ' : ' . $value;
			}
		}
		
		return implode(', ', $renderedFields);
	}

	

	/**
	 * Render the given dataValues with cObj and data
	 *
	 * @param array $data data values
	 * @param array $renderObjectConfig Array with two values in first level 'renderObj' => 'TEXT|COA|IMAGE|...', 'renderObj.' => array(...)
	 * @param mixed $currentData
	 * @return string renderedData
	 */
	public static function renderValueByRenderObject(array $data, array $renderObjectConfig, $currentData = NULL) {
		$renderObjectConfig['renderObj.']['setCurrent'] = $currentData;

		self::getCobj()->start($data);
		return self::getCobj()->cObjGetSingle($renderObjectConfig['renderObj'], $renderObjectConfig['renderObj.']);
	}
	
	
	
	/**
	 * Renders given data by a given configuration array
	 * 
	 * Configuration for rendering has to be in the following form:
	 * 
	 * array {
     *    "dataWrap"=> "{field:label} equals {field:value}",
     *    "_typoScriptNodeValue"=>"TEXT"
     * }
	 * 
	 * Which is the result of the following TS:
	 * 
	 * whateverKey = TEXT
	 * whateverKey {
	 *     dataWrap = {field:label} equals {field:value}
	 * }
	 *
	 * @param array $data Data to be rendered
	 * @param array $configArray Configuration to render data with
	 * @return string The rendered data
	 */
	public static function renderDataByConfigArray($data, $configArray) {
		self::getCobj()->start($data);
		return self::getCobj()->cObjGetSingle($configArray['_typoScriptNodeValue'], $configArray);
	}



	/**
	 * Render the given data by the defined Fluid Template
	 *
	 * @param array $data data values
	 * @param string $templatePath
	 * @return string
	 */
	public static function renderValueByTemplate(array $data, $templatePath) {
		if(!file_exists($templatePath)) {
			$templatePath = t3lib_div::getFileAbsFileName($templatePath);
		}

		self::getFluidRenderer()->setTemplatePathAndFilename($templatePath);
		self::$fluidRenderer->assign('data', $data);
		$rendered = self::$fluidRenderer->render();

		return $rendered;
	}



	/**
	 * Render content by renderuserfunction array
	 *
	 * @param array $data dataFields
	 * @param array $renderUserFunctionConfigArray array of renderUserFunctions
	 * @return string
	 */
	public static function renderValueByRenderUserFunctionArray(array $data, array $renderUserFunctionConfigArray) {
		$params['values'] = $data;
		$content = '';
		$dummRef = '';

		foreach ($renderUserFunctionConfigArray as $key => $rendererUserFuncConfig) {
			$params['currentContent'] = $content;

			$params['conf'] = $rendererUserFuncConfig;
			
			$rendererUserFunc = is_array($rendererUserFuncConfig) && array_key_exists('_typoScriptNodeValue', $rendererUserFuncConfig) ? $rendererUserFuncConfig['_typoScriptNodeValue'] : $rendererUserFuncConfig;

			$content = t3lib_div::callUserFunction($rendererUserFunc, $params, $dummRef, NULL);
		}

		return $content;
	}



	/**
	 * return the cObj object
	 *
	 * @return tslib_cObj;
	 * @deprecated Use Tx_PtExtbase_Div::getCobj instead
	 */
	public static function getCobj() {
		if(!self::$cObj) {
			if(TYPO3_MODE == 'FE') {
				if(!is_a($GLOBALS['TSFE']->cObj,'tslib_cObj')) {
					$GLOBALS['TSFE']->newCObj();
				}
			} else {
				t3lib_div::makeInstance('Tx_PtExtbase_Utility_FakeFrontendFactory')->createFakeFrontend();
			}
		}

		self::$cObj = $GLOBALS['TSFE']->cObj;
		return self::$cObj;
	}



	/**
	 * Build a fluid renderer object
	 *
	 * @return Tx_Fluid_View_TemplateView
	 */
	protected static function getFluidRenderer() {

		if(!self::$fluidRenderer) {

			$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');

			self::$fluidRenderer = $objectManager->get('Tx_Fluid_View_TemplateView');
			
			$controllerContext = $objectManager->get('Tx_PtExtlist_Extbase_ExtbaseContext')->getControllerContext();
			self::$fluidRenderer->setControllerContext($controllerContext);
		}

		return self::$fluidRenderer;
	}



	/**
	 * If the given value is a plain array, it is converted
	 * to a typoscript array and rendered with stdWrap.
	 * Otherwise the input value is returned
	 *
	 * @param mixed $tsConfigValue
	 * @return rendered typoscript value or plain input value
	 */
	public static function stdWrapIfPlainArray($tsConfigValue) {
		if(!is_array($tsConfigValue)) return $tsConfigValue;

		$tsArray = Tx_PtExtbase_Compatibility_Extbase_Service_TypoScript::convertPlainArrayToTypoScriptArray(array('tsConfigArray' => $tsConfigValue));
		$content = self::getCobj()->cObjGetSingle($tsArray['tsConfigArray'],$tsArray['tsConfigArray.']);

		return $content;
	}
	
	
	
	/**
	 * Render the given dataValues with cObj
	 *
	 * @param array $tsConfigValue Array with two values in first level 'renderObj' => 'TEXT|COA|IMAGE|...', 'renderObj.' => array(...)
	 * @return string renderedData
	 */
	public static function renderCObjectWithPlainArray($tsConfigValue) {
		if(!is_array($tsConfigValue) && array_key_exists('cObject', $tsConfigValue)) return $tsConfigValue;
		
		$tsArray = Tx_PtExtbase_Compatibility_Extbase_Service_TypoScript::convertPlainArrayToTypoScriptArray($tsConfigValue);

		return self::getCobj()->cObjGetSingle($tsArray['cObject'], $tsArray['cObject.']);
	}
	

}