<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>, Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * Class implements a testcase for a get / post var adapter
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_StateAdapter_GetPostVarAdapter_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $getVars;
	protected $postVars;
	
	
	
	/**
	 * Instance of getPostVar Adapger
	 *
	 * @var Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter
	 */
	protected $gpVarAdapter;
	
	public function setup() {
		$this->getVars = array('key1' => array(
		    'key2' => array(
		        'key3' => array(
		            'key4' => 'value1',
		            'key5' => 'value2'		
		         )
		    )
		)
		);

		$this->postVars = array('key1' => array(
            'key2' => array(
                'key3' => array(
                    'key4' => 'value3',
                    'key5' => 'value4'      
                 )
            )
        )
        );
	
        $this->gpVarAdapter = new Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter();
	    $this->gpVarAdapter->injectGetVars($this->getVars);
	    $this->gpVarAdapter->injectPostVars($this->postVars);
	}
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter'));
		$this->assertTrue(class_exists('Tx_PtExtlist_Tests_Domain_StateAdapter_Stubs_GetPostVarObject'));
	}
	
	
	
	public function testGetGetVarsByNamespace() {
		$extractedValue = $this->gpVarAdapter->getGetVarsByNamespace('key1.key2.key3.key4');
		$this->assertEquals($extractedValue, 'value1');
	}
	
	
	
	public function testGetPostVarsByNamespace() {
		$extractedValue = $this->gpVarAdapter->getPostVarsByNamespace('key1.key2.key3.key4');
		$this->assertEquals($extractedValue, 'value3');
	}
	
	
	
	public function testGetGpVarsByNamespace() {
		$extractedValue = $this->gpVarAdapter->extractGpVarsByNamespace('key1.key2.key3.key4');
		$this->assertEquals($extractedValue, 'value1');
	}
	
	
	public function testGetPgVarsByNamespace() {
		$extractedValue = $this->gpVarAdapter->extractPgVarsByNamespace('key1.key2.key3.key4');
        $this->assertEquals($extractedValue, 'value3');
	}
	
	
    public function testGetPgVarsByNamespaceReturnedArray() {
        $extractedValue = $this->gpVarAdapter->extractPgVarsByNamespace('key1.key2.key3');
        $this->assertEquals($extractedValue, $this->postVars['key1']['key2']['key3']);
    }
    
    
    public function testGetGpVarsByNamespaceReturnedArray() {
        $extractedValue = $this->gpVarAdapter->extractGpVarsByNamespace('key1.key2.key3');
        $this->assertEquals($extractedValue, $this->getVars['key1']['key2']['key3']);
    }
    
    
    public function testGetParametersByObject() {

    	$object = new Tx_PtExtlist_Tests_Domain_StateAdapter_Stubs_GetPostVarObject();
    	$object->setObjectNamespace('key1.key2.key3');
    	
    	
    	$this->gpVarAdapter->getParametersByObject($object);
    	
    	$injectedValues = $object->getValues();

    	$this->assertEquals($injectedValues, $this->getVars['key1']['key2']['key3']);
    	
    	
    }
	
}

?>