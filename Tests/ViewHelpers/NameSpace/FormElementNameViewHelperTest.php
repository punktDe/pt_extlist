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
 * Testcase for getPostPropertyViewHelper
 * 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Domain\Model\ViewHelpers\NameSpace
 */
class Tx_PtExtlist_Tests_ViewHelpers_Namespace_FormElementNameViewHelper_testcase extends Tx_PtExtlist_Tests_BaseTestcase {


	/**
	 * @dataProvider
	 * @return array
	 */
	public function argumentDataProvider() {
		$identifiableObjectFixture = new Tx_Yag_Tests_ViewHelpers_Namespace_IdentifiableObjectFixture();

		$argumentData = array(
			'oneWordPropertyWithoutPrefix' => array(
				'object' => $identifiableObjectFixture,
				'property' => 'key',
				'addExtPrefix' => false,
				'expectedResult' => 'test[object][namespace][key]'
			),
			'oneWordPropertyWithPrefix' => array(
				'object' => $identifiableObjectFixture,
				'property' => 'key',
				'addExtPrefix' => true,
				'expectedResult' => 'tx_ptextlist_pi1[test][object][namespace][key]'
			),
			'threeWordPropertyWithoutPrefix' => array(
				'object' => $identifiableObjectFixture,
				'property' => 'key.foo.bar',
				'addExtPrefix' => false,
				'expectedResult' => 'test[object][namespace][key][foo][bar]'
			),
			'threeWordPropertyWithPrefix' => array(
				'object' => $identifiableObjectFixture,
				'property' => 'key.foo.bar',
				'addExtPrefix' => true,
				'expectedResult' => 'tx_ptextlist_pi1[test][object][namespace][key][foo][bar]'
			)
		);

		return $argumentData;
	}


	/**
	 * @param Tx_PtExtbase_State_IdentifiableInterface $object
	 * @param $property
	 * @param $addExtPrefix
	 * @param $expectedResult
	 *
	 * @test
	 * @dataProvider argumentDataProvider
	 */
	public function render(Tx_PtExtbase_State_IdentifiableInterface $object, $property, $addExtPrefix, $expectedResult) {
		$linkViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_FormElementNameViewHelper();
		$result = $linkViewHelper->render($object, $property, $addExtPrefix);
		$this->assertEquals($expectedResult, $result);
	}

}


	class Tx_Yag_Tests_ViewHelpers_Namespace_IdentifiableObjectFixture implements Tx_PtExtbase_State_IdentifiableInterface {

		/**
		 * Generates an unique namespace for an object to be used
		 * for addressing object specific session data and gp variables.
		 *
		 * Expected notation: ns1.ns2.ns3.(...)
		 *
		 * @return String Unique namespace for object
		 */
		public function getObjectNamespace() {
			return 'test.object.namespace';
		}
	}

?>