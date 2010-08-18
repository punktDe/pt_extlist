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
 * Utility to render values by renderObject or renderUserFunction
 * Caching for rendered Values is implemented here
 * 
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Utility_RenderValue {
	
	
	protected static $cObj;
	
	
	protected static $renderCache = array();
	
	
	
	/**
	 * Render data by cObject or renderUserFunction and cache it
	 *
	 * @param array $data data values to be rendered
	 * @param array $renderObjectConfig config for cObj rendering
	 * @param array $renderUserFunctionConfig array and config for multiple renderuserfunctions
	 * @return string rendered value
	 */
	public static function render(array $data, array $renderObjectConfig = NULL, array $renderUserFunctionConfig = NULL) {
		$cacheKey = md5(serialize(func_get_args()));
		if(!self::$renderCache[$cacheId]) {
			self::$renderCache[$cacheKey] = self::renderUncached($data, $renderObjectConfig, $renderUserFunctionConfig);
		}
		return self::$renderCache[$cacheKey];
	}
	
	
	
	/**
	 * Render the given Data by configuration from the configuration object
	 * 
	 * @param array $data data to be rendered
	 * @param Tx_PtExtlist_Domain_Configuration_RenderConfigInterface $renderConfig
	 */
	public static function renderByConfigObject(array $data, Tx_PtExtlist_Domain_Configuration_RenderConfigInterface $renderConfig) {
		return self::render($data, $renderConfig->getRenderObj(), $renderConfig->getRenderUserFunctions());
	}
	
	
	/**
	 * Render the given Data by configuration from the configuration object uncached
	 * 
	 * @param array $data data to be rendered
	 * @param Tx_PtExtlist_Domain_Configuration_RenderConfigInterface $renderConfig
	 */
	public static function renderByConfigObjectUncached(array $data, Tx_PtExtlist_Domain_Configuration_RenderConfigInterface $renderConfig) {
		return self::renderUncached($data, $renderConfig->getRenderObj(), $renderConfig->getRenderUserFunctions());
	}
	
	
	/**
	 * Render data by cObject or renderUserFunction and cache it
	 *
	 * @param array $data data values to be rendered
	 * @param array $renderObjectConfig config for cObj rendering
	 * @param array $renderUserFunctionConfig array config for multiple renderuserfunction
	 * @return string rendered value
	 */
	public static function renderUncached(array $data, array $renderObjectConfig = NULL, array $renderUserFunctionConfig = NULL) {
		$content = '';
		
		if($renderUserFunctionConfig) {
			$content = self::renderValueByRenderUserFunctionArray($data, $renderUserFunctionConfig);
		}
		
		if($renderObjectConfig) {
			$content = self::renderValueByRenderObject($data, $renderObjectConfig, $content);
		}	

		if(!$renderUserFunctionConfig && !$renderObjectConfig) {
			$content = self::renderDefault($data);
		}
		
		return $content;
	}
	
	
	/**
	 * Render data in default mode, eg implode with ','
	 * 
	 * @param array $data
	 * @return string rendered data
	 */
	public static function renderDefault(array $data) {
		return implode(', ', $data);	
	}

	
	
	/**
	 * Render the given dataValues with cObj
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
	 * Render content by renderuserfunction array
	 * 
	 * @param array $data dataFields
	 * @param array $renderUserFunctionConfig array of renderUserFunctions
	 */
	public static function renderValueByRenderUserFunctionArray(array $data, array $renderUserFunctionConfig) {
		$params['values'] = $data; 
		$content = '';
		$dummRef = ''; 

		foreach ($renderUserFunctionConfig as $key => $rendererUserFunc) {
			$params['currentContent'] = $content;
			$params['conf'] = $userFunctions[$key]; 
			$content = t3lib_div::callUserFunction($rendererUserFunc, $params, $dummRef);
		}
		
		return $content;
	}
		
	
	/**
	 * return the cObj object 
	 * 
	 * @return tslib_cObj;
	 */
	protected static function getCobj() {
		if(!self::$cObj) {
			self::$cObj = $GLOBALS['TSFE']->cObj;
		}
		
		return self::$cObj;
	}
	
	
}