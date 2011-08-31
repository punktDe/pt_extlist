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
 * Class implements a bookmark domain object to store session information for filters etc.
 * 
 * @author Michael Knoll 
 * @package Domain
 * @subpackage Model\Bookmarks
 * @entity
 */
class Tx_PtExtlist_Domain_Model_Bookmarks_Bookmark 
    extends Tx_Extbase_DomainObject_AbstractEntity 
    implements Tx_PtExtbase_State_IdentifiableInterface {
	
	/**
	 * Name for bookmark
	 *
	 * @var string
	 */
	protected $name;
	
	
	
	/**
	 * Frontend user that saved bookmark
	 *
	 * @var Tx_Extbase_Domain_Model_FrontendUser
	 */
	protected $feUser;
	
	
	
	/**
     * Frontend group bookmark should be shown to
     *
     * @var Tx_Extbase_Domain_Model_FrontendUserGroup
     */
	protected $feGroup;
	
	
	
	/**
	 * Description for bookmark
	 *
	 * @var string
	 */
	protected $description;
	
	
	
	/**
	 * Information to be stored by bookmark
	 *
	 * @var string
	 */
	protected $content;
	
	
	
	/**
	 * Identifier of list bookmark stores information for
	 *
	 * @var string
	 */
	protected $listId;
	
	
	
	/**
	 * Date on which bookmark was created (timestamp)
	 *
	 * @var int
	 */
    protected $createDate;	
    
    
    
    /**
     * True, if bookmark is public
     *
     * @var bool
     */
    protected $isPublic;
    
    
    
    /**
     * PID of bookmark
     *
     * @var int
     */
    protected $pid;
	
    
    
	/**
	 * @see Tx_PtExtbase_State_IdentifiableInterface::getObjectNamespace()
	 *
	 * @return String
	 */
	public function getObjectNamespace() {
		return $this->listIdentifier . '.bookmark.' . $this->getUid();
	}
	
	
	
	/**
	 * Constructor for bookmark 
	 */
	public function __construct() {
	
	}
	
	
	
	/**
	 * Getter for PID
	 *
	 * @return int
	 */
	public function getPid() {
		return $this->pid;
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
	 * Returns FE group to which this bookmark should be shown to
	 *
	 * @return Tx_Extbase_Domain_Model_FrontendUserGroup
	 */
	public function getFeGroup() {
		return $this->feGroup;
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
	 * Getter for is public property
	 *
	 * @return bool
	 */
	public function getIsPublic() {
		return $this->isPublic;
	}
    
    
    
    /**
     * Setter for PID
     *
     * @param int $pid
     */
    public function setPid($pid) {
        $this->pid = $pid;
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
	 * Setter for fe group to which this bookmark should be shown to
	 *
	 * @param Tx_Extbase_Domain_Model_FrontendUserGroup $feGroup
	 */
	public function setFeGroup($feGroup) {
		$this->feGroup = $feGroup;
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
	
	

	/**
	 * Setter for isPublic property
	 *
	 * @param bool $isPublic
	 */
	public function setIsPublic($isPublic) {
		$this->isPublic = $isPublic;
	}

}

?>
