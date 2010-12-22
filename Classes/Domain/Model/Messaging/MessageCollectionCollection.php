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
 * Class implements a collection of a collection of messages
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Domain
 * @subpackage Model\Messaging
 */
class Tx_PtExtlist_Domain_Model_Messaging_MessageCollectionCollection extends tx_pttools_objectCollection {

    /**
     * Restricts collection to hold only objects of given type
     *
     * @var string
     */
    protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_Messaging_MessageCollection';
    
    
    
    /**
     * Adds messagecollection to collection by given messagecollection key
     *
     * @param Tx_PtExtlist_Domain_Model_Messaging_MessageCollection $messageCollection
     * @param string $messageCollectionKey
     */
    public function addMessageCollection(Tx_PtExtlist_Domain_Model_Messaging_MessageCollection $messageCollection, $messageCollectionKey=0) {
        $this->addItem($messageCollection, $messageCollectionKey);
    }
    
    
    
    /**
     * Returns messagecollection for a given id
     *
     * @param string $messageCollectionKey
     * @return Tx_PtExtlist_Domain_Model_Messaging_MessageCollection
     */
    public function getMessageByKey($messageCollectionKey) {
        if ($this->hasItem($messageCollectionKey)) {
            return $this->getItemById($messageCollectionKey);
        }
    }
    
}