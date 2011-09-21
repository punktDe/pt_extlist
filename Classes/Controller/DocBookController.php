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
 * Controller for generating docbook documentation for this extension
 * 
 * @author Michael Knoll 
 *
 * @package Controller
 */
class Tx_PtExtlist_Controller_DocBookController extends Tx_PtExtlist_Controller_AbstractController {

	/**
     * Holds an array of fields to be used as @field={value} within comments section of
     * TS values
     * @var array
     */
	protected $commentTypes = array('purpose', 'description', 'datatype', 'example');
	
	
	
	/**
	 * Holds an array of TS keys to render documentation for
	 *
	 * @var array
	 */
	protected $tsKeysToCreateDocFor = array('plugin.tx_ptextlist.settings', 'extensionName');
	
	
	protected $tsString = "
            
	        # Kommentar
	        plugin.tx_ptextlist.settings < plugin.tx_ptextlist.settings
            plugin.tx_ptextlist.settings.persistence {
	            # here comes the extension name
                # and the comment goes over 2 lines
                # and has a @key={value}
	            storagePid = 12
		        
            }
            
            # here comes the extension name
            # and the comment goes over 2 lines
            # and has a @key={value}
            extensionName = PtExtlist
            
            # Den soll er nicht finden
            pluginName = pi1
            controller = List
            action = list
            switchableControllerActions {
               10 {
                   controller = List
                   action = list
               }
            
            }";
	
	
	
	/**
	 * Holds array of parsed TS data
	 *
	 * @var array
	 */
	protected $tsArray = array();
	
	
	
	/**
	 * Holds documentation for each TS key of the form:
	 * 
	 * array( 
	 *     '<ts_key (e.g. "settings">'  => array(  
	 *         'rawComment'  => <all_comments_above_key>,
	 *         'purpose'     => <content of @purpose={...}>,
	 *         'description' => <content of @description={...}>,
	 *         'datatype'    => <content of @datatype={...}>,
	 *         'example'     => <content of @example={...}>
	 *     )          
	 * )
	 *
	 * @var array
	 */
	protected $docArray = array();
	
	
	
	/**
	 * Holds an instance of typoscript parser
	 *
	 * @var t3lib_TSparser
	 */
	protected $tsParser;
	
	
	
	/**
	 * Initializes controller
	 */
	public function injectSettings(array $settings) {
		parent::injectSettings($settings);
		$this->tsParser = t3lib_div::makeInstance('t3lib_TSparser'); 
	}
	
	
	
	/**
     * Action for rendering docbook ts reference
     * 
     * @return string The rendered docbook as XML
     */
	public function createTsDocBookAction() {
		$this->parseTs();
		$this->createDocArray();
	}
	
	
	
	/**
	 * Parses TS settings
	 */
	protected function parseTs() {
        $this->tsParser->regComments = 1;
        $this->tsParser->parse($this->tsString);
        $this->tsArray = $this->tsParser->setup;
	}
	
	
	
	protected function createDocArray() {
		foreach ($this->tsKeysToCreateDocFor as $tsKey) {
			$this->createDocArrayForTsKey($tsKey);
		}
	}
	
	
	
	protected function createDocArrayForTsKey($tsKey) {
		$currentDocArrayPart = &$this->docArray;
		$currentTsArrayPart = &$this->tsArray;

		// remove last dot if it is one
		if (substr($tsKey,-1,1) == '.') $tsKey = substr($tsKey, 0, -1);
		$tsPath = explode('.', $tsKey);
		
		for($depth = 0; $depth < count($tsPath) - 1; $depth++) {
			// Step down in TS array to depth of comment-level (comment is on same level as actual key!)
			if (!array_key_exists($tsPath[$depth] . '.', $currentTsArrayPart)) break;
            $currentTsArrayPart = &$currentTsArrayPart[$tsPath[$depth] . '.'];			
		}
		
		if (array_key_exists($tsPath[$depth] . '..', $currentTsArrayPart)) {
		    $currentDocArrayPart[$tsKey] = array('rawComment' => $currentTsArrayPart[$tsPath[$depth] . '..']);
		    // add documentation for children
		} else {
		}
	    foreach ($currentTsArrayPart[$tsPath[$depth] . '.'] as $childKey => $childValue) {
	    	print_r('In recursion: $tsKey ' . $tsKey . ' - $childKey ' . $childKey);
	    	if (substr($childKey,-1,1) == '.') {
	    	    $this->createDocArrayForTsKey($tsKey . '.' . $childKey);
	    	}
	    }
		
	}
	
	
}