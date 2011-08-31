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
 * Class implements a collection of messages
 * 
 * @author Michael Knoll 
 * @package Domain
 * @subpackage Model\Messaging
 */
class Tx_PtExtlist_Domain_Model_Messaging_MessageCollection extends Tx_PtExtbase_Collection_ObjectCollection {

	/**
	 * Restricts collection to hold only objects of given type
	 *
	 * @var string
	 */
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_Messaging_Message';
	
	
	
	/**
	 * Adds message to collection by given message key
	 *
	 * @param Tx_PtExtlist_Domain_Model_Messaging_Message $message
	 * @param string $messageKey
	 */
	public function addMessage(Tx_PtExtlist_Domain_Model_Messaging_Message $message, $messageKey=0) {
		$this->addItem($message, $messageKey);
	}
	
	
	
	/**
	 * Returns message for a given id
	 *
	 * @param string $messageKey
	 * @return Tx_PtExtlist_Domain_Model_Messaging_Message
	 */
	public function getMessageByKey($messageKey) {
		if ($this->hasItem($messageKey)) {
		    return $this->getItemById($messageKey);
		}
	}
 	
}