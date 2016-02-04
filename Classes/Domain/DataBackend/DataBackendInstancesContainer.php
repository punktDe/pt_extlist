<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
*
*
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
 * Class implements a container for data backend instances which is a singleton.
 *
 * TODO refactor this into an abstract class that holds basic functionality
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package Domain
 * @subpackage DataBackend
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_DataBackendInstancesContainerTest
 */
class Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Holds an array of data backend instances as list-identifier based singletons
     *
     * @var array<Tx_PtExtlist_Domain_DataBackend_DataBackendInterface>
     */
    private $instances = array();



    /**
     * Adds a given databackend to the instances of this container.
     *
     * Use set() if you want to overwrite existing instances.
     *
     * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     * @throws Exception if a databackend for this list identifier has already been added to this container.
     */
    public function add(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend)
    {
        if (empty($this->instances[$dataBackend->getListIdentifier()])) {
            $this->instances[$dataBackend->getListIdentifier()] = $dataBackend;
        } else {
            throw new Exception('A data backend for this list identifier has already been added. Please check first, whether this container already has an instance for the given list identifier! 1363856865');
        }
    }



    /**
     * Sets a given data backend as instance. Overwrites already set instance.
     *
     * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     */
    public function set(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend)
    {
        $this->instances[$dataBackend->getListIdentifier()] = $dataBackend;
    }



    /**
     * Returns true, if a data backend for given list identifier exists.
     *
     * @param $listIdentifier
     * @return bool
     */
    public function contains($listIdentifier)
    {
        return array_key_exists($listIdentifier, $this->instances);
    }



    /**
     * Returns instance of data backend for given list identifier
     *
     * @param $listIdentifier
     * @return null|Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
     */
    public function get($listIdentifier)
    {
        if (array_key_exists($listIdentifier, $this->instances)) {
            return $this->instances[$listIdentifier];
        } else {
            return null;
        }
    }


    /**
     * Clear all instances of this container
     */
    public function clear()
    {
        $this->instances = array();
    }
}
