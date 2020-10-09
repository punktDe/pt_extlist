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

use PunktDe\PtExtlist\ViewHelpers\Namespaces\FormElementNameViewHelper;

/**
 * Testcase for getPostPropertyViewHelper
 * 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Domain\Model\ViewHelpers\NameSpace
 * @see FormElementNameViewHelper
 */
class Tx_PtExtlist_Tests_ViewHelpers_Namespace_FormElementNameViewHelperTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * @dataProvider
     * @return array
     */
    public function argumentDataProvider()
    {
        $identifiableObjectFixture = new PunktDe_Yag_Tests_ViewHelpers_Namespace_IdentifiableObjectFixture();

        $argumentData = [
            'oneWordPropertyWithoutPrefix' => [
                'object' => $identifiableObjectFixture,
                'property' => 'key',
                'addExtPrefix' => false,
                'expectedResult' => 'test[object][namespace][key]'
            ],
            'oneWordPropertyWithPrefix' => [
                'object' => $identifiableObjectFixture,
                'property' => 'key',
                'addExtPrefix' => true,
                'expectedResult' => 'tx_ptextlist_pi1[test][object][namespace][key]'
            ],
            'threeWordPropertyWithoutPrefix' => [
                'object' => $identifiableObjectFixture,
                'property' => 'key.foo.bar',
                'addExtPrefix' => false,
                'expectedResult' => 'test[object][namespace][key][foo][bar]'
            ],
            'threeWordPropertyWithPrefix' => [
                'object' => $identifiableObjectFixture,
                'property' => 'key.foo.bar',
                'addExtPrefix' => true,
                'expectedResult' => 'tx_ptextlist_pi1[test][object][namespace][key][foo][bar]'
            ]
        ];

        return $argumentData;
    }


    /**
     * @param \PunktDe_PtExtbase_State_IdentifiableInterface $object
     * @param $property
     * @param $addExtPrefix
     * @param $expectedResult
     *
     * @test
     * @dataProvider argumentDataProvider
     */
    public function render(\PunktDe_PtExtbase_State_IdentifiableInterface $object, $property, $addExtPrefix, $expectedResult)
    {
        $linkViewHelper = new FormElementNameViewHelper();
        $result = $linkViewHelper->render($object, $property, $addExtPrefix);
        $this->assertEquals($expectedResult, $result);
    }
}


class PunktDe_Yag_Tests_ViewHelpers_Namespace_IdentifiableObjectFixture implements PunktDe_PtExtbase_State_IdentifiableInterface
{
    /**
     * Generates an unique namespace for an object to be used
     * for addressing object specific session data and gp variables.
     *
     * Expected notation: ns1.ns2.ns3.(...)
     *
     * @return String Unique namespace for object
     */
    public function getObjectNamespace()
    {
        return 'test.object.namespace';
    }
}
