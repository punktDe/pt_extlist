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
 * @see Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkTest
 */
class Tx_PtExtlist_Domain_Model_Bookmark_Bookmark
    extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
    implements Tx_PtExtbase_State_IdentifiableInterface
{
    const PTEXTLIST_BOOKMARK_PUBLIC = 1;
    const PTEXTLIST_BOOKMARK_PRIVATE = 2;
    const PTEXTLIST_BOOKMARK_GROUP = 3;


    /**
     * Name for bookmark
     *
     * @var string
     */
    protected $name;
    
    
    
    /**
     * Frontend user that saved bookmark
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $feUser;
    
    
    
    /**
     * Frontend group bookmark should be shown to
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
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
     * @var integer
     */
    protected $createDate;
    
    
    
    /**
     * Type of bookmark (public, private, group)
     *
     * @var integer
     */
    protected $type;
    
    
    
    /**
     * PID of bookmark
     *
     * @var integer
     */
    protected $pid;
    
    
    
    /**
     * @see Tx_PtExtbase_State_IdentifiableInterface::getObjectNamespace()
     *
     * @return String
     */
    public function getObjectNamespace()
    {
        return $this->listId . '.bookmark.' . $this->getUid();
    }
    
    
    
    /**
     * Getter for PID
     *
     * @return integer
     */
    public function getPid()
    {
        return $this->pid;
    }
    
    
    
    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    
    
    /**
     * @return integer
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
    
    
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    
    
    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    public function getFeUser()
    {
        return $this->feUser;
    }
    
    
    
    /**
     * Returns FE group to which this bookmark should be shown to
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
     */
    public function getFeGroup()
    {
        return $this->feGroup;
    }
    
    
    
    /**
     * @return string
     */
    public function getListId()
    {
        return $this->listId;
    }
    
    
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }



    /**
     * Getter for type property
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }
    
    
    
    /**
     * Setter for PID
     *
     * @param integer $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }
    
    
    
    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    
    
    /**
     * @param integer $date
     */
    public function setCreateDate($date)
    {
        $this->createDate = $date;
    }
    
    
    
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    
    
    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $feUser
     */
    public function setFeUser($feUser)
    {
        $this->feUser = $feUser;
    }
    
    
    
    /**
     * Setter for fe group to which this bookmark should be shown to
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $feGroup
     */
    public function setFeGroup($feGroup)
    {
        $this->feGroup = $feGroup;
    }
    
    
    
    /**
     * @param string $listId
     */
    public function setListId($listId)
    {
        $this->listId = $listId;
    }
    
    
    
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * Setter for type property
     *
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
