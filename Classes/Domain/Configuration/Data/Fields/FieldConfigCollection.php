<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Data\Fields;

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
 * Class implements a collection of field configurations.
 *
 * @package Domain
 * @subpackage Configuration\Data\Fields
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Data_Fields_FieldConfigCollectionTest
 */
class FieldConfigCollection extends \PunktDe\PtExtbase\Collection\ObjectCollection
{
    /**
     * This collection is restricted to objects of type FieldConfig
     *
     * @var string
     */
    protected $restrictedClassName = FieldConfig::class;



    /**
     * Adds a field configuration object to collection
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $fieldConfig
     */
    public function addFieldConfig(FieldConfig $fieldConfig)
    {
        $this->addItem($fieldConfig, $fieldConfig->getIdentifier());
    }



    /**
     * Returns a field configuration object for a given identifier
     *
     * @param string $identifier
     * @return FieldConfig
     * @throws \Exception
     */
    public function getFieldConfigByIdentifier($identifier)
    {
        if ($this->hasItem($identifier)) {
            return $this->getItemById($identifier);
        } else {
            throw new \Exception('Field configuration for key ' . $identifier . ' does not exist!', 1280772114);
        }
    }



    /**
     * get part of the collection with entrys selected by the array
     *
     * @param array $fieldIdentifierList
     * @return FieldConfigCollection ;
     */
    public function extractCollectionByIdentifierList(array $fieldIdentifierList)
    {
        if (current($fieldIdentifierList) == '*') {
            return $this;
        }

        $collection = new FieldConfigCollection();
        foreach ($fieldIdentifierList as $fieldIdentifier) {
            $collection->addFieldConfig($this->getFieldConfigByIdentifier($fieldIdentifier));
        }

        return $collection;
    }
}
