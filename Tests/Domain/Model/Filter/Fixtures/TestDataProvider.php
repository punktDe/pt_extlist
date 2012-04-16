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
 * Test Data Provider
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_Fixture_TestDataProvider  {

	/**
	 * array of options
	 * 
	 * @var array
	 */
	protected $options;


    /**
     * @param $renderedOptions
     */
    public function setOptions($options) {
        $this->options = $options;
    }


	/**
     * @return array
     */
	public function getRenderedOptions() {
		$renderedOptions = array();

        foreach($this->options as $key => $value) {
            $renderedOptions[$key] = array(
                'value' => $value,
                'selected' => false,
            );
        }

        return $renderedOptions;
	}
}
?>