<?php
/***************************************************************
 *  Copyright notice
 *  
 *  (c) 2009 Fabrizio Branca (mail@fabrizio-branca.de)
 *  All rights reserved
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
 * Demolist data renderer
 *
 * @version 	$Id$
 * @author		Fabrizio Branca <mail@fabrizio-branca.de>
 * @since		2009-01-30
 */
class tx_ptextlist_demolist_renderer {
	
	
	/**
	 * This renderer appends an image with the current country flag (if available) to the current content
	 *
	 * @param 	array 	array('currentContent' => <currentContent>, 'values' => array('<dataDescriptionIdentifier>' => '<value>'))
	 * @return 	string 	rendered content
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 * @since	2009-02-10
	 */
	public static function iso2CodeRenderer(array $params) {
		$values = $params['values'];
	
		$currentContent = $params['currentContent'];
		
		$value = $values['iso2'];
		
		if (isset($value)) {
			// check if file exists
			$flagFileName =  'gfx/flags/' . strtolower($value) . '.gif';
			if (is_file(PATH_typo3 . $flagFileName)) {
				$currentContent .= ' <img src="/typo3/'. $flagFileName .'" />'; 
			}
		}
		return $currentContent;
	}
	
	
	
	/**
	 * Name and capital renderer
	 * Note: This is only for demo purposes. This can be done in typoscript much easier!
	 *
	 * @param 	array	parameters
	 * @return 	string	rendered content
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 * @since	2009-02-10
	 */
	public static function nameAndCapitalRenderer(array $params) {
		$values = $params['values'];
		return sprintf('%s (%s)', $values['name_local'], $values['capital']);
	}
	

}

?>