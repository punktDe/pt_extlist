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
 * Class implements a bookmark domain object to store session information for filters etc.
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 * @entity
 */
class Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark extends Tx_Extbase_DomainObject_AbstractEntity {
	
	/**
	 * Name for bookmark
	 *
	 * @var string
	 */
	public $name;
	
	
	
	/**
	 * Frontend user that saved bookmark
	 *
	 * @var Tx_Extbase_Domain_Model_FrontendUser
	 */
	public $feUser;
	
	
	
	/**
	 * Description for bookmark
	 *
	 * @var string
	 */
	public $description;
	
	
	
	/**
	 * Information to be stored by bookmark
	 *
	 * @var string
	 */
	public $content;
	
	
	
	/**
	 * Identifier of list bookmark stores information for
	 *
	 * @var string
	 */
	public $listId;
	
	
	
	/**
	 * Date on which bookmark was created (timestamp)
	 *
	 * @var int
	 */
    public $createDate;	
	
	
	
	/**
	 * Constructor for bookmark 
	 */
	public function __construct() {
	
	}
	
	
	
	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}
	
	
	
	/**
	 * @return int
	 */
	public function getCreateDate() {
		return $this->date;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	
	
	/**
	 * @return Tx_Extbase_Domain_Model_FrontendUser
	 */
	public function getFeUser() {
		return $this->feUser;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getListId() {
		return $this->listId;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	
	
	/**
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	
	
	/**
	 * @param int $date
	 */
	public function setCreateDate($date) {
		$this->date = $date;
	}
	
	
	
	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
	
	
	
	/**
	 * @param Tx_Extbase_Domain_Model_FrontendUser $feUser
	 */
	public function setFeUser($feUser) {
		$this->feUser = $feUser;
	}
	
	
	
	/**
	 * @param string $listId
	 */
	public function setListId($listId) {
		$this->listId = $listId;
	}
	
	
	
	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

}

?>
